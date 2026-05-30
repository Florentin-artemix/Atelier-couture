<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\Order\CommandeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuiviController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
    ) {}

    public function index(): View
    {
        return view('public.suivi.index');
    }

    public function recherche(Request $request): RedirectResponse
    {
        $request->validate([
            'telephone' => ['required', 'string', 'max:30'],
        ]);

        $client = Client::where('telephone', $request->input('telephone'))->first();

        if (!$client || !$client->lien_suivi) {
            return redirect()->route('public.suivi.index')
                ->with('error', 'Aucun client trouve avec ce numero de telephone.');
        }

        return redirect()->route('public.suivi.client', $client->lien_suivi);
    }

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
