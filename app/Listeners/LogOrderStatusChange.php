<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Log;

class LogOrderStatusChange
{
    public function handle(OrderStatusChanged $event): void
    {
        Log::info('Changement de statut de commande', [
            'commande_id' => $event->commande->id,
            'reference' => $event->commande->reference,
            'de' => $event->from->value,
            'vers' => $event->to->value,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
