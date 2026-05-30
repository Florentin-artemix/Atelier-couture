<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\MesureClient;
use App\Models\MesureType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeasurementController extends Controller
{
    public function index(Client $client): View
    {
        $mesures = MesureClient::where('client_id', $client->id)
            ->with('mesureType')
            ->orderBy('created_at', 'desc')
            ->get();

        $mesureTypes = MesureType::orderBy('ordre_affichage')->get();

        return view('admin.mesures.index', compact('client', 'mesures', 'mesureTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'mesure_type_id' => ['required', 'exists:mesure_types,id'],
            'valeur' => ['required', 'numeric', 'min:0.1'],
            'date_prise' => ['nullable', 'date'],
        ]);

        MesureClient::updateOrCreate(
            [
                'client_id' => $validated['client_id'],
                'mesure_type_id' => $validated['mesure_type_id'],
                'commande_id' => null,
            ],
            [
                'valeur' => $validated['valeur'],
                'date_prise' => $validated['date_prise'] ?? now()->toDateString(),
            ]
        );

        return redirect()->back()
            ->with('success', 'Mesure enregistree.');
    }
}
