<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Events\OrderCancelled;
use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderStatusChanged;
use App\Events\PreorderValidated;
use App\Exceptions\BusinessException;
use App\Exceptions\InsufficientMeasurementsException;
use App\Exceptions\InvalidOrderTransitionException;
use App\Models\Accessoire;
use App\Models\Commande;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Measurement\MesureService;
use App\Services\Pricing\TarificationService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private TarificationService $tarificationService,
        private MesureService $mesureService,
    ) {}

    public function creerCommande(array $data): Commande
    {
        return DB::transaction(function () use ($data) {
            $type = OrderType::from($data['type']);

            $statut = match ($type) {
                OrderType::Precommande => OrderStatus::Precommande,
                default => OrderStatus::Nouvelle,
            };

            $commande = $this->orderRepository->create([
                'reference' => $this->genererReference(),
                'client_id' => $data['client_id'],
                'modele_id' => $data['modele_id'],
                'type' => $type,
                'statut' => $statut,
                'date_commande' => $data['date_commande'] ?? now()->toDateString(),
                'date_livraison_prevue' => $data['date_livraison_prevue'],
                'notes_internes' => $data['notes_internes'] ?? null,
                'notes_client' => $data['notes_client'] ?? null,
                'lien_suivi' => Str::random(64),
            ]);

            if (!empty($data['accessoires'])) {
                $this->attacherAccessoires($commande, $data['accessoires']);
            }

            $this->tarificationService->recalculer($commande);
            $commande = $commande->fresh();

            event(new OrderCreated($commande));

            if ($statut === OrderStatus::Nouvelle) {
                $commande = $this->determinerProchainStatut($commande);
            }

            return $commande;
        });
    }

    public function mettreAJour(Commande $commande, array $data): Commande
    {
        if ($commande->statut->isTerminal()) {
            throw new BusinessException('Impossible de modifier une commande terminee.');
        }

        return DB::transaction(function () use ($commande, $data) {
            $champsModifiables = collect($data)->only([
                'date_livraison_prevue',
                'notes_internes',
                'notes_client',
                'prix_final',
            ])->filter()->toArray();

            if (!empty($champsModifiables)) {
                $this->orderRepository->update($commande, $champsModifiables);
            }

            if (isset($data['accessoires'])) {
                $this->synchroniserAccessoires($commande, $data['accessoires']);
                $this->tarificationService->recalculer($commande);
            }

            return $commande->fresh();
        });
    }

    public function changerStatut(Commande $commande, OrderStatus $nouveauStatut): Commande
    {
        $statutActuel = $commande->statut;

        if (!$statutActuel->canTransitionTo($nouveauStatut)) {
            throw new InvalidOrderTransitionException(
                "Transition impossible de '{$statutActuel->label()}' vers '{$nouveauStatut->label()}'."
            );
        }

        $this->validerPreConditions($commande, $nouveauStatut);

        $donneesMaj = ['statut' => $nouveauStatut];

        if ($nouveauStatut === OrderStatus::Livree) {
            $donneesMaj['date_livraison_reelle'] = now()->toDateString();
        }

        $this->orderRepository->update($commande, $donneesMaj);
        $commande = $commande->fresh();

        $this->emetreEvenementTransition($commande, $statutActuel, $nouveauStatut);

        return $commande;
    }

    public function validerPrecommande(Commande $commande): Commande
    {
        if ($commande->statut !== OrderStatus::Precommande) {
            throw new BusinessException('Cette commande n\'est pas une precommande en attente.');
        }

        $commande = $this->changerStatut($commande, OrderStatus::Nouvelle);
        event(new PreorderValidated($commande));

        return $this->determinerProchainStatut($commande);
    }

    public function rejeterPrecommande(Commande $commande, ?string $motif = null): Commande
    {
        if ($motif) {
            $this->orderRepository->update($commande, ['notes_internes' => $motif]);
        }

        return $this->annulerCommande($commande);
    }

    public function annulerCommande(Commande $commande): Commande
    {
        return $this->changerStatut($commande, OrderStatus::Annulee);
    }

    public function fixerPrixFinal(Commande $commande, float $prixFinal): Commande
    {
        if ($commande->statut->isTerminal()) {
            throw new BusinessException('Impossible de modifier le prix d\'une commande terminee.');
        }

        if ($prixFinal < 0) {
            throw new BusinessException('Le prix final ne peut pas etre negatif.');
        }

        $this->orderRepository->update($commande, ['prix_final' => $prixFinal]);

        return $commande->fresh();
    }

    public function trouverParLienSuivi(string $lienSuivi): ?Commande
    {
        return $this->orderRepository->findByLienSuivi($lienSuivi);
    }

    public function getCommandesEnRetard(): Collection
    {
        return $this->orderRepository->getEnRetard();
    }

    public function getCommandesClient(int $clientId): Collection
    {
        return $this->orderRepository->getForClient($clientId);
    }

    public function getCommandesParStatut(?OrderStatus $statut = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = Commande::with(['client', 'modele']);

        if ($statut) {
            $query->where('statut', $statut);
        }

        return $query->latest('date_commande')->paginate($perPage);
    }

    // Private methods

    private function determinerProchainStatut(Commande $commande): Commande
    {
        if ($commande->statut !== OrderStatus::Nouvelle) {
            return $commande;
        }

        $commande->load('modele.categorie');

        $mesuresCompletes = $this->mesureService->verifierCompletude(
            $commande->client_id,
            $commande->modele->categorie_modele_id,
            $commande->id
        );

        $prochainStatut = $mesuresCompletes
            ? OrderStatus::EnProduction
            : OrderStatus::EnAttenteMesures;

        return $this->changerStatut($commande, $prochainStatut);
    }

    private function validerPreConditions(Commande $commande, OrderStatus $cible): void
    {
        match ($cible) {
            OrderStatus::EnProduction => $this->validerPourProduction($commande),
            OrderStatus::Livree => $this->validerPourLivraison($commande),
            default => null,
        };
    }

    private function validerPourProduction(Commande $commande): void
    {
        if (!$commande->client->hasConsentement('collecte_mesures')) {
            throw new BusinessException(
                'Le consentement pour la collecte des mesures est requis avant la production.'
            );
        }

        $commande->load('modele.categorie');

        $mesuresCompletes = $this->mesureService->verifierCompletude(
            $commande->client_id,
            $commande->modele->categorie_modele_id,
            $commande->id
        );

        if (!$mesuresCompletes) {
            throw new InsufficientMeasurementsException();
        }
    }

    private function validerPourLivraison(Commande $commande): void
    {
        if (is_null($commande->prix_final)) {
            throw new BusinessException(
                'Le prix final doit etre renseigne avant de marquer la commande comme livree.'
            );
        }
    }

    private function emetreEvenementTransition(Commande $commande, OrderStatus $de, OrderStatus $vers): void
    {
        match ($vers) {
            OrderStatus::Livree => event(new OrderDelivered($commande)),
            OrderStatus::Annulee => event(new OrderCancelled($commande)),
            default => event(new OrderStatusChanged($commande, $de, $vers)),
        };
    }

    private function attacherAccessoires(Commande $commande, array $accessoiresData): void
    {
        foreach ($accessoiresData as $item) {
            $accessoire = Accessoire::findOrFail($item['accessoire_id']);

            if (!$accessoire->is_active) {
                throw new BusinessException("L'accessoire '{$accessoire->nom}' est indisponible.");
            }

            $commande->accessoires()->attach($accessoire->id, [
                'quantite' => $item['quantite'] ?? 1,
                'prix_unitaire_snapshot' => $accessoire->prix_unitaire,
                'fourni_par_client' => $item['fourni_par_client'] ?? false,
            ]);
        }
    }

    private function synchroniserAccessoires(Commande $commande, array $accessoiresData): void
    {
        $syncData = [];

        foreach ($accessoiresData as $item) {
            $accessoire = Accessoire::findOrFail($item['accessoire_id']);

            if (!$accessoire->is_active) {
                throw new BusinessException("L'accessoire '{$accessoire->nom}' est indisponible.");
            }

            $syncData[$accessoire->id] = [
                'quantite' => $item['quantite'] ?? 1,
                'prix_unitaire_snapshot' => $accessoire->prix_unitaire,
                'fourni_par_client' => $item['fourni_par_client'] ?? false,
            ];
        }

        $commande->accessoires()->sync($syncData);
    }

    private function genererReference(): string
    {
        $year = now()->format('Y');
        $lastOrder = $this->orderRepository->getLastOfYear($year);

        $nextNumber = $lastOrder
            ? ((int) substr($lastOrder->reference, -4)) + 1
            : 1;

        return sprintf('CMD-%s-%04d', $year, $nextNumber);
    }
}
