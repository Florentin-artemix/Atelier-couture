<?php

namespace App\Services\Catalogue;

use App\Models\CategorieModele;
use App\Models\Modele;
use App\Repositories\Contracts\CatalogueRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogueService
{
    public function __construct(
        private CatalogueRepositoryInterface $catalogueRepository,
    ) {}

    public function getModelesByCategorie(?int $categorieId = null, int $perPage = 12): LengthAwarePaginator
    {
        return $this->catalogueRepository->getActiveModeles($categorieId, $perPage);
    }

    public function getModeleDetails(int $id): ?Modele
    {
        return $this->catalogueRepository->findModele($id);
    }

    public function creerModele(array $data): Modele
    {
        $data['slug'] = Str::slug($data['nom']);

        if (isset($data['image_principale']) && $data['image_principale'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_principale'] = $data['image_principale']->store('modeles', config('ateliercouture.images_disk', 'public'));
        }

        return $this->catalogueRepository->createModele($data);
    }

    public function updateModele(Modele $modele, array $data): Modele
    {
        if (isset($data['nom'])) {
            $data['slug'] = Str::slug($data['nom']);
        }

        // Handle image upload
        if (isset($data['image_principale']) && $data['image_principale'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_principale'] = $data['image_principale']->store('modeles', config('ateliercouture.images_disk', 'public'));
        }

        $this->catalogueRepository->updateModele($modele, $data);
        return $modele->fresh();
    }

    public function getActiveCategories(): Collection
    {
        return $this->catalogueRepository->getActiveCategories();
    }
}
