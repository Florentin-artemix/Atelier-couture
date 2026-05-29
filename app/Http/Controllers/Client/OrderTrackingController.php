<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(Request $request): View
    {
        $client = $request->user()->client;
        $commandes = $client->commandes()->with('modele')->latest('date_commande')->get();

        return view('client.orders.index', compact('commandes'));
    }

    public function show(Request $request, Commande $commande): View
    {
        $client = $request->user()->client;
        abort_if($commande->client_id !== $client->id, 403);

        $commande->load(['modele', 'accessoires']);

        return view('client.orders.show', compact('commande'));
    }
}
