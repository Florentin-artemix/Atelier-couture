<?php

namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function find(int $id): ?Client;
    public function create(array $data): Client;
    public function update(Client $client, array $data): bool;
    public function search(?string $term, int $perPage = 15): LengthAwarePaginator;
    public function findByTelephone(string $telephone): ?Client;
    public function findByLienSuivi(string $lienSuivi): ?Client;
}
