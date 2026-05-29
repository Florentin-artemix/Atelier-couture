<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Events\OrderDelivered;
use App\Services\Notification\NotificationService;

class ArchiveOrderReminders
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(OrderDelivered|OrderCancelled $event): void
    {
        $this->notificationService->archiverPourCommande($event->commande);
    }
}
