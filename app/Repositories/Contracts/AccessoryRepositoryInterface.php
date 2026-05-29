<?php

namespace App\Repositories\Contracts;

use App\Models\Accessoire;
use Illuminate\Support\Collection;

interface AccessoryRepositoryInterface
{
    public function find(int $id): ?Accessoire;
    public function create(array $data): Accessoire;
    public function update(Accessoire $accessoire, array $data): bool;
    public function delete(Accessoire $accessoire): bool;
    public function getActive(): Collection;
    public function getAll(): Collection;
}
