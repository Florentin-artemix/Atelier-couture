<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\CategorieModele;
use App\Models\Modele;
use App\Services\Catalogue\CatalogueService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    public function __construct(
        private CatalogueService $catalogueService,
    ) {}

    public function index(Request $request): View
    {
        // Le filtre envoie le slug de la categorie (?categorie=robe)
        $slug = $request->input('categorie');
        $categorie = $slug ? CategorieModele::where('slug', $slug)->first() : null;
        $selectedCategory = $categorie?->id;

        $modeles = $this->catalogueService->getModelesByCategorie($selectedCategory);
        $categories = $this->catalogueService->getActiveCategories();

        return view('public.catalogue.index', compact('modeles', 'categories', 'selectedCategory'));
    }

    public function show(Modele $modele): View
    {
        $modele->load('categorie');

        return view('public.catalogue.show', compact('modele'));
    }
}
