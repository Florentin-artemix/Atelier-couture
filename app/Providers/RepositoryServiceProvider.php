<?php

namespace App\Providers;

use App\Repositories\Contracts\AccessoryRepositoryInterface;
use App\Repositories\Contracts\CatalogueRepositoryInterface;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\MeasurementRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Repositories\Eloquent\AccessoryRepository;
use App\Repositories\Eloquent\CatalogueRepository;
use App\Repositories\Eloquent\ClientRepository;
use App\Repositories\Eloquent\MeasurementRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PortfolioRepository;
use App\Repositories\Eloquent\ReminderRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        AccessoryRepositoryInterface::class => AccessoryRepository::class,
        CatalogueRepositoryInterface::class => CatalogueRepository::class,
        ClientRepositoryInterface::class => ClientRepository::class,
        MeasurementRepositoryInterface::class => MeasurementRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        PortfolioRepositoryInterface::class => PortfolioRepository::class,
        ReminderRepositoryInterface::class => ReminderRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
