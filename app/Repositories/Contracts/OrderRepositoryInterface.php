<?php

namespace App\Repositories\Contracts;

use App\Enums\OrderStatus;
use App\Models\Commande;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Commande;
    public function create(array $data): Commande;
    public function update(Commande $commande, array $data): bool;
    public function findByLienSuivi(string $lienSuivi): ?Commande;
    public function getByStatut(OrderStatus $statut): Collection;
    public function getEnRetard(): Collection;
    public function getALivrerCetteSemaine(): Collection;
    public function getRecent(int $limit = 10): Collection;
    public function getLastOfYear(string $year): ?Commande;
    public function getForClient(int $clientId): Collection;
}
