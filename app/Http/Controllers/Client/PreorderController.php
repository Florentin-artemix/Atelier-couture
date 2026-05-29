<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePreorderRequest;
use App\Services\Order\CommandeService;
use Illuminate\Http\RedirectResponse;

class PreorderController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
    ) {}

    public function store(StorePreorderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['client_id'] = $request->user()->client->id;
        $validated['type'] = 'precommande';

        $commande = $this->commandeService->creerCommande($validated);

        return redirect()->route('client.orders.show', $commande)
            ->with('success', 'Precommande enregistree avec succes.');
    }
}
