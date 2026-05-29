<?php

namespace App\Repositories\Contracts;

use App\Models\CategorieModele;
use App\Models\Modele;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CatalogueRepositoryInterface
{
    public function findModele(int $id): ?Modele;
    public function findModeleBySlug(string $slug): ?Modele;
    public function createModele(array $data): Modele;
    public function updateModele(Modele $modele, array $data): bool;
    public function getActiveModeles(?int $categorieId, int $perPage = 12): LengthAwarePaginator;
    public function getActiveCategories(): Collection;
    public function createCategory(array $data): CategorieModele;
    public function updateCategory(CategorieModele $categorie, array $data): bool;
}
