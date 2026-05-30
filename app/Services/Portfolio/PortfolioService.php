<?php

namespace App\Services\Portfolio;

use App\Models\RealisationPortfolio;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PortfolioService
{
    public function __construct(
        private PortfolioRepositoryInterface $portfolioRepository,
    ) {}

    public function getRealisations(?int $categorieId = null, int $perPage = 12): LengthAwarePaginator
    {
        return $this->portfolioRepository->getVisible($categorieId, $perPage);
    }

    public function creerRealisation(array $data): RealisationPortfolio
    {
        $data = $this->handleImageUpload($data);
        return $this->portfolioRepository->create($data);
    }

    public function supprimerRealisation(RealisationPortfolio $entry): void
    {
        $this->portfolioRepository->delete($entry);
    }

    public function getAllEntries(int $perPage = 15): LengthAwarePaginator
    {
        return $this->portfolioRepository->getAll($perPage);
    }

    public function updateEntry(RealisationPortfolio $entry, array $data): RealisationPortfolio
    {
        $data = $this->handleImageUpload($data);
        $this->portfolioRepository->update($entry, $data);
        return $entry->fresh();
    }

    private function handleImageUpload(array $data): array
    {
        if (isset($data['image_principale']) && $data['image_principale'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_principale'] = $data['image_principale']->store('portfolio', config('ateliercouture.images_disk', 'public'));
        } else {
            // Ne pas ecraser l'image existante si aucun fichier n'est envoye
            unset($data['image_principale']);
        }

        return $data;
    }
}
