<?php

use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MeasurementController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', EnsureIsAdmin::class])
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Catalogue (modeles)
        Route::resource('catalogue', CatalogueController::class)->parameter('catalogue', 'modele');

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);

        // Clients
        Route::resource('clients', ClientController::class);

        // Commandes
        Route::resource('commandes', OrderController::class)->parameter('commandes', 'commande');
        Route::patch('commandes/{commande}/statut', [OrderController::class, 'updateStatus'])->name('commandes.updateStatus');
        Route::patch('commandes/{commande}/prix-final', [OrderController::class, 'setPrixFinal'])->name('commandes.setPrixFinal');
        Route::patch('commandes/{commande}/mesures-demandees', [OrderController::class, 'demanderMesures'])->name('commandes.demanderMesures');

        // Mesures
        Route::get('clients/{client}/mesures', [MeasurementController::class, 'index'])->name('mesures.index');
        Route::post('mesures', [MeasurementController::class, 'store'])->name('mesures.store');

        // Accessoires
        Route::resource('accessoires', AccessoryController::class)->except(['show']);

        // Portfolio
        Route::resource('portfolio', PortfolioController::class)->except(['show']);

        // Rappels
        Route::get('rappels', [ReminderController::class, 'index'])->name('rappels.index');
        Route::post('rappels', [ReminderController::class, 'store'])->name('rappels.store');
        Route::patch('rappels/{rappel}/done', [ReminderController::class, 'markDone'])->name('rappels.markDone');

        // Pricing (AJAX)
        Route::post('pricing/calculate', [PricingController::class, 'calculate'])->name('pricing.calculate');
        Route::post('pricing/{commande}/recalculate', [PricingController::class, 'recalculate'])->name('pricing.recalculate');
    });
