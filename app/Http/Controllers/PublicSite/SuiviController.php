<?php

namespace App\Http\Controllers\PublicSite;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\Client\ClientService;
use App\Services\Measurement\MesureService;
use App\Services\Order\CommandeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuiviController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
        private MesureService $mesureService,
        private ClientService $clientService,
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

    public function showCommande(string $lienSuivi): View|RedirectResponse
    {
        $commande = $this->commandeService->trouverParLienSuivi($lienSuivi);

        if (!$commande) {
            return redirect()->route('public.suivi.index')
                ->with('error', 'Lien de suivi invalide ou expire. Verifiez le lien ou recherchez par telephone.');
        }

        $commande->load(['modele.categorie', 'accessoires', 'client']);

        // Si la commande attend les mesures, preparer le formulaire de saisie
        $typesMesures = collect();
        $mesuresExistantes = collect();

        if ($commande->statut === OrderStatus::EnAttenteMesures && $commande->modele) {
            $typesMesures = $this->mesureService->getTypesParCategorie($commande->modele->categorie_modele_id);
            $mesuresExistantes = $this->mesureService
                ->getMesuresClient($commande->client_id, $commande->id)
                ->keyBy('mesure_type_id');
        }

        return view('public.suivi.commande', compact('commande', 'typesMesures', 'mesuresExistantes'));
    }

    public function storeMesures(Request $request, string $lienSuivi): RedirectResponse
    {
        $commande = $this->commandeService->trouverParLienSuivi($lienSuivi);

        if (!$commande) {
            return redirect()->route('public.suivi.index')
                ->with('error', 'Lien de suivi invalide ou expire.');
        }

        $commande->load('modele');

        $validated = $request->validate([
            'consentement' => ['accepted'],
            'mesures' => ['required', 'array'],
            'mesures.*' => ['nullable', 'numeric', 'min:0.1'],
        ], [
            'consentement.accepted' => 'Vous devez accepter la collecte de vos mesures.',
            'mesures.required' => 'Veuillez renseigner vos mesures.',
        ]);

        // Verifier que toutes les mesures de base (obligatoires) sont fournies
        $typesMesures = $this->mesureService->getTypesParCategorie($commande->modele->categorie_modele_id);
        $manquantes = [];

        foreach ($typesMesures as $type) {
            $valeur = $validated['mesures'][$type->id] ?? null;
            if ($type->is_base && (is_null($valeur) || $valeur === '')) {
                $manquantes[] = $type->libelle;
            }
        }

        if (!empty($manquantes)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mesures de base obligatoires manquantes : ' . implode(', ', $manquantes));
        }

        // Enregistrer le consentement du client (collecte publique)
        $this->clientService->recordConsent(
            $commande->client,
            'collecte_mesures',
            true,
            'formulaire_suivi',
            $request->ip()
        );

        // Construire le lot de mesures (uniquement les valeurs renseignees)
        $lot = [];
        foreach ($typesMesures as $type) {
            $valeur = $validated['mesures'][$type->id] ?? null;
            if (!is_null($valeur) && $valeur !== '') {
                $lot[] = [
                    'mesure_type_id' => $type->id,
                    'valeur' => $valeur,
                ];
            }
        }

        try {
            $this->mesureService->enregistrerMesuresEnLot($commande->client_id, $lot, $commande->id);
        } catch (\App\Exceptions\BusinessException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('public.suivi.commande', $commande->lien_suivi)
            ->with('success', 'Merci ! Vos mesures ont ete enregistrees. Le couturier va pouvoir lancer la confection.');
    }

    public function showClient(string $lienSuivi): View|RedirectResponse
    {
        // Le lien client pointe vers un CLIENT (pas une commande)
        $client = Client::where('lien_suivi', $lienSuivi)->first();

        if (!$client) {
            return redirect()->route('public.suivi.index')
                ->with('error', 'Lien de suivi invalide ou expire. Verifiez le lien ou recherchez par telephone.');
        }

        $commandes = $this->commandeService->getCommandesClient($client->id);

        return view('public.suivi.client', compact('client', 'commandes'));
    }
}
