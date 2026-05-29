<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Services\Notification\NotificationService;

class CreateMeasurementReminder
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(OrderStatusChanged $event): void
    {
        if ($event->to === OrderStatus::EnAttenteMesures) {
            $this->notificationService->creerRappelMesures($event->commande);
        }
    }
}
