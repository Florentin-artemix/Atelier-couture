<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isClient()) {
            abort(403, 'Acces reserve aux clients.');
        }

        if (!$request->user()->client) {
            abort(403, 'Aucune fiche client associee a ce compte.');
        }

        return $next($request);
    }
}
