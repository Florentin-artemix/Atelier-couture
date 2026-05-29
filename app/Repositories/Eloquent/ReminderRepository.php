<?php

namespace App\Repositories\Eloquent;

use App\Models\Rappel;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use Illuminate\Support\Collection;

class ReminderRepository implements ReminderRepositoryInterface
{
    public function create(array $data): Rappel
    {
        return Rappel::create($data);
    }

    public function getPending(): Collection
    {
        return Rappel::pending()
            ->with('commande', 'client')
            ->orderBy('date_echeance')
            ->get();
    }

    public function getOverdue(): Collection
    {
        return Rappel::overdue()
            ->with('commande', 'client')
            ->orderBy('date_echeance')
            ->get();
    }

    public function getUpcoming(int $days = 7): Collection
    {
        return Rappel::upcoming($days)
            ->with('commande', 'client')
            ->orderBy('date_echeance')
            ->get();
    }

    public function archiveForCommande(int $commandeId): int
    {
        return Rappel::where('commande_id', $commandeId)
            ->where('is_fait', false)
            ->update([
                'is_fait' => true,
                'date_fait' => now(),
            ]);
    }
}
