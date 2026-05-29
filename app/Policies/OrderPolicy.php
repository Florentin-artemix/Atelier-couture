<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Commande $commande): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->client?->id === $commande->client_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Commande $commande): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Commande $commande): bool
    {
        return $user->isAdmin() && !$commande->statut->isTerminal();
    }

    public function updateStatus(User $user, Commande $commande): bool
    {
        return $user->isAdmin() && !$commande->statut->isTerminal();
    }
}
