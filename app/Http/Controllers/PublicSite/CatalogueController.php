<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
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
        $modeles = $this->catalogueService->getModelesByCategorie($request->input('categorie_id'));
        $categories = $this->catalogueService->getActiveCategories();

        return view('public.catalogue.index', compact('modeles', 'categories'));
    }

    public function show(Modele $modele): View
    {
        $modele->load('categorie');

        return view('public.catalogue.show', compact('modele'));
    }
}
