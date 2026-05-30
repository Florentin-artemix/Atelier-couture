<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicSite\CatalogueController;
use App\Http\Controllers\PublicSite\PortfolioController;
use App\Http\Controllers\PublicSite\PreorderController;
use App\Http\Controllers\PublicSite\SuiviController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/connexion', [LoginController::class, 'show'])->name('login');
Route::post('/connexion', [LoginController::class, 'login']);
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

// Home
Route::get('/', function () {
    return view('public.home');
})->name('home');

// Public Catalogue
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('public.catalogue.index');
Route::get('/catalogue/{modele:slug}', [CatalogueController::class, 'show'])->name('public.catalogue.show');

// Public Portfolio
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('public.portfolio.index');

// Preorder
Route::get('/precommande/{modele}', [PreorderController::class, 'create'])->name('public.preorder.create');
Route::post('/precommande', [PreorderController::class, 'store'])->name('public.preorder.store');
Route::get('/precommande/confirmation/{lien}', [PreorderController::class, 'confirmation'])->name('public.preorder.confirmation');

// Suivi public
Route::get('/suivi', [SuiviController::class, 'index'])->name('public.suivi.index');
Route::post('/suivi/recherche', [SuiviController::class, 'recherche'])->name('public.suivi.recherche');
Route::get('/suivi/commande/{lienSuivi}', [SuiviController::class, 'showCommande'])->name('public.suivi.commande');
Route::post('/suivi/commande/{lienSuivi}/mesures', [SuiviController::class, 'storeMesures'])->name('public.suivi.mesures');
Route::get('/suivi/client/{lienSuivi}', [SuiviController::class, 'showClient'])->name('public.suivi.client');

// Admin & Client route files
require __DIR__ . '/admin.php';
require __DIR__ . '/client.php';
