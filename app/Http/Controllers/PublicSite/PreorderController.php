<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Modele;
use App\Services\Order\CommandeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PreorderController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
    ) {}

    public function create(Modele $modele): View
    {
        return view('public.preorder.create', compact('modele'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'modele_id' => ['required', 'exists:modeles,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $client = Client::where('telephone', $validated['telephone'])->first();

        if (!$client) {
            $client = Client::create([
                'nom' => $validated['nom'],
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'lien_suivi' => \Illuminate\Support\Str::random(64),
                'is_active' => true,
            ]);
        }

        $commande = $this->commandeService->creerCommande([
            'client_id' => $client->id,
            'modele_id' => $validated['modele_id'],
            'type' => 'precommande',
            'date_livraison_prevue' => null,
            'notes_client' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('public.preorder.confirmation', $commande->lien_suivi)
            ->with('success', 'Votre precommande a ete enregistree avec succes.');
    }

    public function confirmation(string $lien): View
    {
        $commande = Commande::where('lien_suivi', $lien)->firstOrFail();

        return view('public.preorder.confirmation', compact('commande'));
    }
}
