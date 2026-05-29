<?php

namespace App\Repositories\Contracts;

use App\Models\RealisationPortfolio;
use Illuminate\Pagination\LengthAwarePaginator;

interface PortfolioRepositoryInterface
{
    public function find(int $id): ?RealisationPortfolio;
    public function create(array $data): RealisationPortfolio;
    public function update(RealisationPortfolio $entry, array $data): bool;
    public function delete(RealisationPortfolio $entry): bool;
    public function getVisible(?int $categorieId, int $perPage = 12): LengthAwarePaginator;
    public function getAll(int $perPage = 15): LengthAwarePaginator;
}
