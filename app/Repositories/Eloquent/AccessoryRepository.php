<?php

namespace App\Repositories\Eloquent;

use App\Models\Accessoire;
use App\Repositories\Contracts\AccessoryRepositoryInterface;
use Illuminate\Support\Collection;

class AccessoryRepository implements AccessoryRepositoryInterface
{
    public function find(int $id): ?Accessoire
    {
        return Accessoire::find($id);
    }

    public function create(array $data): Accessoire
    {
        return Accessoire::create($data);
    }

    public function update(Accessoire $accessoire, array $data): bool
    {
        return $accessoire->update($data);
    }

    public function delete(Accessoire $accessoire): bool
    {
        return $accessoire->delete();
    }

    public function getActive(): Collection
    {
        return Accessoire::active()->orderBy('nom')->get();
    }

    public function getAll(): Collection
    {
        return Accessoire::orderBy('nom')->get();
    }
}
