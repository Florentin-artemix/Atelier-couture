<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateProfileRequest;
use App\Services\Client\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ClientService $clientService,
    ) {}

    public function show(Request $request): View
    {
        $client = $request->user()->client;

        return view('client.profile.show', compact('client'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $client = $request->user()->client;
        $this->clientService->updateClient($client, $request->validated());

        return redirect()->route('client.profile.show')
            ->with('success', 'Profil mis a jour.');
    }
}
