<?php

namespace App\Repositories\Contracts;

use App\Models\MesureClient;
use Illuminate\Support\Collection;

interface MeasurementRepositoryInterface
{
    public function createOrUpdate(array $data): MesureClient;
    public function getForClient(int $clientId, ?int $commandeId = null): Collection;
    public function deleteForClient(int $clientId): int;
}
