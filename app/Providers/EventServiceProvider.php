<?php

namespace App\Providers;

use App\Events\OrderCancelled;
use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderStatusChanged;
use App\Listeners\ArchiveOrderReminders;
use App\Listeners\CreateAutomaticReminder;
use App\Listeners\CreateMeasurementReminder;
use App\Listeners\LogOrderStatusChange;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            CreateAutomaticReminder::class,
        ],
        OrderStatusChanged::class => [
            CreateMeasurementReminder::class,
            LogOrderStatusChange::class,
        ],
        OrderDelivered::class => [
            ArchiveOrderReminders::class,
        ],
        OrderCancelled::class => [
            ArchiveOrderReminders::class,
        ],
    ];
}
