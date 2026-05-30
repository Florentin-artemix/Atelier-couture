<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Commande;
use App\Services\Order\CommandeService;
use App\Services\Pricing\TarificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
        private TarificationService $tarificationService,
    ) {}

    public function index(Request $request): View
    {
        $statut = $request->input('statut') ? OrderStatus::from($request->input('statut')) : null;
        $commandes = $this->commandeService->getCommandesParStatut($statut);

        return view('admin.commandes.index', compact('commandes', 'statut'));
    }

    public function create(): View
    {
        $clients = \App\Models\Client::active()->orderBy('nom')->get();
        $modeles = \App\Models\Modele::where('is_active', true)->orderBy('nom')->get();
        $accessoires = \App\Models\Accessoire::where('is_active', true)->orderBy('nom')->get();

        return view('admin.commandes.create', compact('clients', 'modeles', 'accessoires'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $commande = $this->commandeService->creerCommande($validated);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande creee avec succes.');
    }

    public function show(Commande $commande): View
    {
        $commande->load(['client', 'modele', 'accessoires', 'rappels']);
        $details = $this->tarificationService->calculerDetails($commande);

        return view('admin.commandes.show', compact('commande', 'details'));
    }

    public function edit(Commande $commande): View
    {
        $commande->load(['client', 'modele', 'accessoires']);

        return view('admin.commandes.edit', compact('commande'));
    }

    public function update(UpdateOrderRequest $request, Commande $commande): RedirectResponse
    {
        $validated = $request->validated();
        $this->commandeService->mettreAJour($commande, $validated);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Commande mise a jour.');
    }

    public function updateStatus(Request $request, Commande $commande): RedirectResponse
    {
        $this->commandeService->changerStatut($commande, OrderStatus::from($request->input('statut')));

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Statut mis a jour.');
    }

    public function setPrixFinal(Request $request, Commande $commande): RedirectResponse
    {
        $this->commandeService->fixerPrixFinal($commande, $request->input('prix_final'));

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Prix final enregistre.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $this->commandeService->annulerCommande($commande);

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Commande annulee.');
    }
}
