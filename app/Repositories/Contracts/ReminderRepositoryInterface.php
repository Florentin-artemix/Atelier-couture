<?php

namespace App\Repositories\Contracts;

use App\Models\Rappel;
use Illuminate\Support\Collection;

interface ReminderRepositoryInterface
{
    public function create(array $data): Rappel;
    public function getPending(): Collection;
    public function getOverdue(): Collection;
    public function getUpcoming(int $days = 7): Collection;
    public function archiveForCommande(int $commandeId): int;
}
