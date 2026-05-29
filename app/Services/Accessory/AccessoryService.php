<?php

namespace App\Services\Accessory;

use App\Exceptions\BusinessException;
use App\Models\Accessoire;
use App\Repositories\Contracts\AccessoryRepositoryInterface;
use Illuminate\Support\Collection;

class AccessoryService
{
    public function __construct(
        private AccessoryRepositoryInterface $accessoryRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->accessoryRepository->getAll();
    }

    public function creer(array $data): Accessoire
    {
        return $this->accessoryRepository->create($data);
    }

    public function update(Accessoire $accessoire, array $data): Accessoire
    {
        $this->accessoryRepository->update($accessoire, $data);
        return $accessoire->fresh();
    }

    public function toggleActive(Accessoire $accessoire): Accessoire
    {
        $this->accessoryRepository->update($accessoire, [
            'is_active' => !$accessoire->is_active,
        ]);
        return $accessoire->fresh();
    }

    public function getActive(): Collection
    {
        return $this->accessoryRepository->getActive();
    }

    public function delete(Accessoire $accessoire): void
    {
        if ($accessoire->commandes()->exists()) {
            throw new BusinessException(
                "L'accessoire '{$accessoire->nom}' est utilise dans des commandes et ne peut pas etre supprime."
            );
        }
        $this->accessoryRepository->delete($accessoire);
    }
}
