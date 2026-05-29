<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\Notification\NotificationService;

class CreateAutomaticReminder
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $this->notificationService->creerRappelAutomatique($event->commande);
    }
}
