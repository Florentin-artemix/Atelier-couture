<?php

namespace App\Repositories\Eloquent;

use App\Models\MesureClient;
use App\Repositories\Contracts\MeasurementRepositoryInterface;
use Illuminate\Support\Collection;

class MeasurementRepository implements MeasurementRepositoryInterface
{
    public function createOrUpdate(array $data): MesureClient
    {
        return MesureClient::updateOrCreate(
            [
                'client_id' => $data['client_id'],
                'mesure_type_id' => $data['mesure_type_id'],
                'commande_id' => $data['commande_id'] ?? null,
            ],
            [
                'valeur' => $data['valeur'],
                'date_prise' => $data['date_prise'],
                'notes' => $data['notes'] ?? null,
            ]
        );
    }

    public function getForClient(int $clientId, ?int $commandeId = null): Collection
    {
        $query = MesureClient::where('client_id', $clientId)->with('mesureType');

        if ($commandeId) {
            $query->where(function ($q) use ($commandeId) {
                $q->where('commande_id', $commandeId)
                  ->orWhereNull('commande_id');
            });
        } else {
            $query->whereNull('commande_id');
        }

        return $query->get();
    }

    public function deleteForClient(int $clientId): int
    {
        return MesureClient::where('client_id', $clientId)->delete();
    }
}
