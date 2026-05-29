<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Models\Client;
use App\Services\Client\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $clientService,
    ) {}

    public function index(Request $request): View
    {
        $clients = $this->clientService->getClientsActifs($request->input('search'));

        return view('admin.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = $this->clientService->creerClient($request->validated());

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client cree avec succes.');
    }

    public function show(Client $client): View
    {
        $client->load(['commandes', 'mesures', 'consentements']);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientService->updateClient($client, $request->validated());

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client mis a jour.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprime.');
    }
}
