<?php

namespace App\Repositories\Eloquent;

use App\Enums\OrderStatus;
use App\Models\Commande;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(int $id): ?Commande
    {
        return Commande::with(['client', 'modele.categorie', 'accessoires'])->find($id);
    }

    public function create(array $data): Commande
    {
        return Commande::create($data);
    }

    public function update(Commande $commande, array $data): bool
    {
        return $commande->update($data);
    }

    public function findByLienSuivi(string $lienSuivi): ?Commande
    {
        return Commande::where('lien_suivi', $lienSuivi)->with(['modele', 'client'])->first();
    }

    public function getByStatut(OrderStatus $statut): Collection
    {
        return Commande::byStatut($statut)->with('client', 'modele')->latest('created_at')->get();
    }

    public function getEnRetard(): Collection
    {
        return Commande::enRetard()->with('client', 'modele')->orderBy('date_livraison_prevue')->get();
    }

    public function getALivrerCetteSemaine(): Collection
    {
        return Commande::aLivrerCetteSemaine()->with('client', 'modele')->orderBy('date_livraison_prevue')->get();
    }

    public function getRecent(int $limit = 10): Collection
    {
        return Commande::with('client', 'modele')->latest('created_at')->limit($limit)->get();
    }

    public function getLastOfYear(string $year): ?Commande
    {
        return Commande::where('reference', 'like', "CMD-{$year}-%")
            ->orderByDesc('reference')
            ->first();
    }

    public function getForClient(int $clientId): Collection
    {
        return Commande::where('client_id', $clientId)
            ->with('modele')
            ->latest('date_commande')
            ->get();
    }
}
