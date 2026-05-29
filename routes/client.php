<?php

use App\Http\Controllers\Client\OrderTrackingController;
use App\Http\Controllers\Client\PreorderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Middleware\EnsureIsClient;
use Illuminate\Support\Facades\Route;

Route::prefix('espace-client')
    ->name('client.')
    ->middleware(['auth', EnsureIsClient::class])
    ->group(function () {
        // Order tracking
        Route::get('/commandes', [OrderTrackingController::class, 'index'])->name('orders.index');
        Route::get('/commandes/{commande}', [OrderTrackingController::class, 'show'])->name('orders.show');

        // Preorder
        Route::post('/precommande', [PreorderController::class, 'store'])->name('preorder.store');

        // Profile
        Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    });
