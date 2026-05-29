<?php

namespace App\Services\Notification;

use App\Enums\OrderStatus;
use App\Enums\ReminderType;
use App\Exceptions\BusinessException;
use App\Models\Commande;
use App\Models\Rappel;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use Illuminate\Support\Collection;

class NotificationService
{
    public function __construct(
        private ReminderRepositoryInterface $reminderRepository,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function creerRappelAutomatique(Commande $commande): Rappel
    {
        $joursAvant = (int) config('ateliercouture.rappel_pre_livraison_jours', 2);
        $dateEcheance = $commande->date_livraison_prevue->subDays($joursAvant);

        if ($dateEcheance->isPast()) {
            $dateEcheance = now();
        }

        return $this->reminderRepository->create([
            'commande_id' => $commande->id,
            'client_id' => $commande->client_id,
            'type' => ReminderType::Automatique,
            'titre' => "Livraison proche : {$commande->reference}",
            'description' => "La commande {$commande->reference} doit etre livree le {$commande->date_livraison_prevue->format('d/m/Y')}.",
            'date_echeance' => $dateEcheance->toDateString(),
        ]);
    }

    public function creerRappelManuel(array $data): Rappel
    {
        $data['type'] = ReminderType::Manuel;
        return $this->reminderRepository->create($data);
    }

    public function marquerFait(Rappel $rappel): Rappel
    {
        if ($rappel->is_fait) {
            throw new BusinessException('Ce rappel est deja marque comme traite.');
        }

        $rappel->markAsDone();
        return $rappel->fresh();
    }

    public function archiverPourCommande(Commande $commande): int
    {
        return $this->reminderRepository->archiveForCommande($commande->id);
    }

    public function creerRappelMesures(Commande $commande): Rappel
    {
        return $this->reminderRepository->create([
            'commande_id' => $commande->id,
            'client_id' => $commande->client_id,
            'type' => ReminderType::Automatique,
            'titre' => "Mesures manquantes : {$commande->reference}",
            'description' => "Contacter le client pour completer les mesures.",
            'date_echeance' => now()->addDay()->toDateString(),
        ]);
    }

    public function creerRappelRetard(Commande $commande): Rappel
    {
        return $this->reminderRepository->create([
            'commande_id' => $commande->id,
            'client_id' => $commande->client_id,
            'type' => ReminderType::Automatique,
            'titre' => "RETARD : {$commande->reference}",
            'description' => "La commande {$commande->reference} est en retard de {$commande->joursRetard()} jour(s).",
            'date_echeance' => now()->toDateString(),
        ]);
    }

    public function detecterRetards(): int
    {
        $commandesEnRetard = $this->orderRepository->getEnRetard();
        $joursRelance = (int) config('ateliercouture.rappel_relance_retard_jours', 3);
        $count = 0;

        foreach ($commandesEnRetard as $commande) {
            $dernierRappelRetard = Rappel::where('commande_id', $commande->id)
                ->where('titre', 'like', '%RETARD%')
                ->where('is_fait', false)
                ->where('created_at', '>=', now()->subDays($joursRelance))
                ->exists();

            if (!$dernierRappelRetard) {
                $this->creerRappelRetard($commande);
                $count++;
            }
        }

        return $count;
    }

    public function getRappelsEnAttente(): Collection
    {
        return $this->reminderRepository->getPending();
    }

    public function getRappelsEnRetard(): Collection
    {
        return $this->reminderRepository->getOverdue();
    }
}
