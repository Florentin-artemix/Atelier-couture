<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Modele;
use Illuminate\View\View;

class PreorderController extends Controller
{
    public function create(Modele $modele): View
    {
        return view('public.preorder.create', compact('modele'));
    }

    public function confirmation(string $lien): View
    {
        $commande = Commande::where('lien_suivi', $lien)->firstOrFail();

        return view('public.preorder.confirmation', compact('commande'));
    }
}
