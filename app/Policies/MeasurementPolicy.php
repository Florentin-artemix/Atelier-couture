<?php

namespace App\Policies;

use App\Models\MesureClient;
use App\Models\User;

class MeasurementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, MesureClient $mesure): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, MesureClient $mesure): bool
    {
        return $user->isAdmin();
    }
}
