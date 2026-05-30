<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\Order\CommandeService;
use Illuminate\View\View;

class SuiviController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
    ) {}

    public function showCommande(string $lienSuivi): View
    {
        $commande = $this->commandeService->trouverParLienSuivi($lienSuivi);
        abort_if(!$commande, 404);

        $commande->load(['modele', 'accessoires']);

        return view('public.suivi.commande', compact('commande'));
    }

    public function showClient(string $lienSuivi): View
    {
        $commande = $this->commandeService->trouverParLienSuivi($lienSuivi);
        abort_if(!$commande, 404);

        $client = $commande->client;
        $commandes = $this->commandeService->getCommandesClient($client->id);

        return view('public.suivi.client', compact('client', 'commandes'));
    }
}
