<?php

namespace App\Services\Measurement;

use App\Exceptions\BusinessException;
use App\Models\CategorieModele;
use App\Models\Client;
use App\Models\MesureClient;
use App\Models\MesureType;
use App\Repositories\Contracts\MeasurementRepositoryInterface;
use Illuminate\Support\Collection;

class MesureService
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function enregistrerMesure(array $data): MesureClient
    {
        $client = Client::findOrFail($data['client_id']);

        $this->verifierConsentement($client);

        $mesureType = MesureType::findOrFail($data['mesure_type_id']);
        $this->validerBornes($mesureType, (float) $data['valeur']);

        return $this->measurementRepository->createOrUpdate([
            'client_id' => $data['client_id'],
            'mesure_type_id' => $data['mesure_type_id'],
            'commande_id' => $data['commande_id'] ?? null,
            'valeur' => $data['valeur'],
            'date_prise' => $data['date_prise'] ?? now()->toDateString(),
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function enregistrerMesuresEnLot(int $clientId, array $mesures, ?int $commandeId = null): Collection
    {
        $resultats = collect();

        foreach ($mesures as $mesure) {
            $resultats->push($this->enregistrerMesure([
                'client_id' => $clientId,
                'mesure_type_id' => $mesure['mesure_type_id'],
                'valeur' => $mesure['valeur'],
                'date_prise' => $mesure['date_prise'] ?? now()->toDateString(),
                'commande_id' => $commandeId,
                'notes' => $mesure['notes'] ?? null,
            ]));
        }

        return $resultats;
    }

    public function verifierCompletude(int $clientId, int $categorieId, ?int $commandeId = null): bool
    {
        $typesRequis = $this->getTypesObligatoires($categorieId);
        $mesuresClient = $this->measurementRepository->getForClient($clientId, $commandeId);

        $typeIdsFournis = $mesuresClient->pluck('mesure_type_id')->unique()->toArray();

        foreach ($typesRequis as $typeId) {
            if (!in_array($typeId, $typeIdsFournis)) {
                return false;
            }
        }

        return true;
    }

    public function getMesuresManquantes(int $clientId, int $categorieId, ?int $commandeId = null): Collection
    {
        $typesRequis = $this->getTypesObligatoires($categorieId);
        $mesuresClient = $this->measurementRepository->getForClient($clientId, $commandeId);

        $typeIdsFournis = $mesuresClient->pluck('mesure_type_id')->unique()->toArray();
        $manquants = array_diff($typesRequis, $typeIdsFournis);

        return MesureType::whereIn('id', $manquants)->ordered()->get();
    }

    public function getMesuresClient(int $clientId, ?int $commandeId = null): Collection
    {
        return $this->measurementRepository->getForClient($clientId, $commandeId);
    }

    public function getTypesParCategorie(int $categorieId): Collection
    {
        $baseTypes = MesureType::base()->ordered()->get();

        $categorie = CategorieModele::find($categorieId);
        $catTypes = $categorie ? $categorie->mesureTypes()->orderBy('ordre_affichage')->get() : collect();

        return $baseTypes->merge($catTypes)->unique('id')->sortBy('ordre_affichage')->values();
    }

    private function getTypesObligatoires(int $categorieId): array
    {
        $baseTypeIds = MesureType::base()->pluck('id')->toArray();

        $catTypeIds = CategorieModele::find($categorieId)
            ?->mesureTypesObligatoires()
            ->pluck('mesure_types.id')
            ->toArray() ?? [];

        return array_values(array_unique(array_merge($baseTypeIds, $catTypeIds)));
    }

    private function verifierConsentement(Client $client): void
    {
        if (!$client->hasConsentement('collecte_mesures')) {
            throw new BusinessException(
                'Le consentement pour la collecte des mesures doit etre recueilli avant toute saisie.'
            );
        }
    }

    private function validerBornes(MesureType $type, float $valeur): void
    {
        if ($type->valeur_min !== null && $valeur < (float) $type->valeur_min) {
            throw new BusinessException(
                "La mesure '{$type->libelle}' : valeur {$valeur}{$type->unite} est inferieure au minimum autorise ({$type->valeur_min}{$type->unite})."
            );
        }

        if ($type->valeur_max !== null && $valeur > (float) $type->valeur_max) {
            throw new BusinessException(
                "La mesure '{$type->libelle}' : valeur {$valeur}{$type->unite} est superieure au maximum autorise ({$type->valeur_max}{$type->unite})."
            );
        }
    }
}
