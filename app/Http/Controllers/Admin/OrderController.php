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
        $commande->load(['client', 'modele.categorie', 'accessoires', 'rappels']);
        $details = $this->tarificationService->calculerDetails($commande);

        // Pour les precommandes : liste des mesures non-base (que le tailleur
        // peut demander en plus au client si le modele est complexe)
        $mesuresOptionnelles = \App\Models\MesureType::where('is_base', false)
            ->orderBy('libelle')
            ->get();

        return view('admin.commandes.show', compact('commande', 'details', 'mesuresOptionnelles'));
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
        $request->validate(['statut' => ['required', 'string']]);

        $nouveauStatut = OrderStatus::from($request->input('statut'));

        // Statut identique : on n'affiche pas d'erreur, juste une info.
        if ($commande->statut === $nouveauStatut) {
            return redirect()->route('admin.commandes.show', $commande)
                ->with('info', 'La commande est deja dans ce statut.');
        }

        try {
            $this->commandeService->changerStatut($commande, $nouveauStatut);
        } catch (\App\Exceptions\InvalidOrderTransitionException | \App\Exceptions\BusinessException $e) {
            return redirect()->route('admin.commandes.show', $commande)
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Statut mis a jour.');
    }

    public function setPrixFinal(Request $request, Commande $commande): RedirectResponse
    {
        $this->commandeService->fixerPrixFinal($commande, $request->input('prix_final'));

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Prix final enregistre.');
    }

    public function demanderMesures(Request $request, Commande $commande): RedirectResponse
    {
        $validated = $request->validate([
            'mesures_demandees' => ['nullable', 'array'],
            'mesures_demandees.*' => ['exists:mesure_types,id'],
        ]);

        $commande->update([
            'mesures_demandees' => array_map('intval', $validated['mesures_demandees'] ?? []),
        ]);

        return redirect()->route('admin.commandes.show', $commande)
            ->with('success', 'Mesures supplementaires a demander au client enregistrees.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $this->commandeService->annulerCommande($commande);

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Commande annulee.');
    }
}
