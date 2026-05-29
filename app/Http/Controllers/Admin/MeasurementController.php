<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMeasurementRequest;
use App\Models\Client;
use App\Services\Measurement\MesureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MeasurementController extends Controller
{
    public function __construct(
        private MesureService $mesureService,
    ) {}

    public function index(Client $client): View
    {
        $mesures = $this->mesureService->getMesuresClient($client->id);

        return view('admin.mesures.index', compact('client', 'mesures'));
    }

    public function store(StoreMeasurementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->mesureService->enregistrerMesuresEnLot(
            $validated['client_id'],
            $validated['mesures'],
            $validated['commande_id'] ?? null
        );

        return redirect()->back()
            ->with('success', 'Mesures enregistrees avec succes.');
    }
}
