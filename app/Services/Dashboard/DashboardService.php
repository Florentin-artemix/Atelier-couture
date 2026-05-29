<?php

namespace App\Services\Dashboard;

use App\Enums\OrderStatus;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Rappel;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ReminderRepositoryInterface $reminderRepository,
    ) {}

    /**
     * Retourne toutes les donnees necessaires au tableau de bord.
     */
    public function getDonneesDashboard(): array
    {
        return [
            'compteurs' => $this->getCompteurs(),
            'commandes_en_retard' => $this->orderRepository->getEnRetard(),
            'commandes_a_livrer_semaine' => $this->orderRepository->getALivrerCetteSemaine(),
            'commandes_recentes' => $this->orderRepository->getRecent(5),
            'rappels_urgents' => $this->getRappelsUrgents(),
            'precommandes_en_attente' => $this->orderRepository->getByStatut(OrderStatus::Precommande),
        ];
    }

    public function getCompteurs(): array
    {
        return [
            'commandes_en_cours' => Commande::enCours()->count(),
            'commandes_en_retard' => Commande::enRetard()->count(),
            'commandes_en_production' => Commande::byStatut(OrderStatus::EnProduction)->count(),
            'commandes_pretes' => Commande::byStatut(OrderStatus::Prete)->count(),
            'precommandes_en_attente' => Commande::byStatut(OrderStatus::Precommande)->count(),
            'en_attente_mesures' => Commande::byStatut(OrderStatus::EnAttenteMesures)->count(),
            'rappels_en_attente' => Rappel::pending()->count(),
            'rappels_urgents' => Rappel::overdue()->count(),
            'clients_actifs' => Client::active()->count(),
            'chiffre_affaires_mois' => $this->getChiffreAffairesMois(),
            'commandes_livrees_mois' => $this->getCommandesLivreesMois(),
        ];
    }

    public function getChiffreAffairesMois(): float
    {
        return (float) Commande::where('statut', OrderStatus::Livree)
            ->whereMonth('date_livraison_reelle', now()->month)
            ->whereYear('date_livraison_reelle', now()->year)
            ->sum('prix_final');
    }

    public function getCommandesLivreesMois(): int
    {
        return Commande::where('statut', OrderStatus::Livree)
            ->whereMonth('date_livraison_reelle', now()->month)
            ->whereYear('date_livraison_reelle', now()->year)
            ->count();
    }

    private function getRappelsUrgents(): Collection
    {
        return Rappel::pending()
            ->where('date_echeance', '<=', now()->toDateString())
            ->with(['commande', 'client'])
            ->orderBy('date_echeance')
            ->limit(10)
            ->get();
    }
}
