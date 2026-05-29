<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureConsentGiven
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientId = $request->input('client_id') ?? $request->route('client')?->id;

        if ($clientId) {
            $client = Client::find($clientId);

            if ($client && !$client->hasConsentement('collecte_mesures')) {
                return redirect()
                    ->back()
                    ->with('error', 'Le consentement du client pour la collecte des mesures est requis.');
            }
        }

        return $next($request);
    }
}
