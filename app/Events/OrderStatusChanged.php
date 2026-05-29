<?php

namespace App\Events;

use App\Enums\OrderStatus;
use App\Models\Commande;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Commande $commande,
        public OrderStatus $from,
        public OrderStatus $to,
    ) {}
}
