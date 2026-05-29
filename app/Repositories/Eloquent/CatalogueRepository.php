<?php

namespace App\Repositories\Eloquent;

use App\Models\CategorieModele;
use App\Models\Modele;
use App\Repositories\Contracts\CatalogueRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CatalogueRepository implements CatalogueRepositoryInterface
{
    public function findModele(int $id): ?Modele
    {
        return Modele::with('categorie')->find($id);
    }

    public function findModeleBySlug(string $slug): ?Modele
    {
        return Modele::where('slug', $slug)->with('categorie')->first();
    }

    public function createModele(array $data): Modele
    {
        return Modele::create($data);
    }

    public function updateModele(Modele $modele, array $data): bool
    {
        return $modele->update($data);
    }

    public function getActiveModeles(?int $categorieId, int $perPage = 12): LengthAwarePaginator
    {
        $query = Modele::active()->with('categorie');

        if ($categorieId) {
            $query->where('categorie_modele_id', $categorieId);
        }

        return $query->orderBy('nom')->paginate($perPage);
    }

    public function getActiveCategories(): Collection
    {
        return CategorieModele::active()->ordered()->get();
    }

    public function createCategory(array $data): CategorieModele
    {
        return CategorieModele::create($data);
    }

    public function updateCategory(CategorieModele $categorie, array $data): bool
    {
        return $categorie->update($data);
    }
}
