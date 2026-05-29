<?php

namespace App\Repositories\Eloquent;

use App\Models\RealisationPortfolio;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PortfolioRepository implements PortfolioRepositoryInterface
{
    public function find(int $id): ?RealisationPortfolio
    {
        return RealisationPortfolio::find($id);
    }

    public function create(array $data): RealisationPortfolio
    {
        return RealisationPortfolio::create($data);
    }

    public function update(RealisationPortfolio $entry, array $data): bool
    {
        return $entry->update($data);
    }

    public function delete(RealisationPortfolio $entry): bool
    {
        return $entry->delete();
    }

    public function getVisible(?int $categorieId, int $perPage = 12): LengthAwarePaginator
    {
        $query = RealisationPortfolio::visible()->ordered()->with('categorie');

        if ($categorieId) {
            $query->where('categorie_modele_id', $categorieId);
        }

        return $query->paginate($perPage);
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return RealisationPortfolio::ordered()->with('categorie')->paginate($perPage);
    }
}
