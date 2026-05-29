<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreModelRequest;
use App\Http\Requests\Admin\UpdateModelRequest;
use App\Models\Modele;
use App\Services\Catalogue\CatalogueService;
use Illuminate\Http\RedirectResponse;
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

        return view('admin.catalogue.index', compact('modeles'));
    }

    public function create(): View
    {
        $categories = $this->catalogueService->getActiveCategories();

        return view('admin.catalogue.create', compact('categories'));
    }

    public function store(StoreModelRequest $request): RedirectResponse
    {
        $modele = $this->catalogueService->creerModele($request->validated());

        return redirect()->route('admin.catalogue.show', $modele)
            ->with('success', 'Modele cree avec succes.');
    }

    public function show(Modele $modele): View
    {
        $modele->load('categorie');

        return view('admin.catalogue.show', compact('modele'));
    }

    public function edit(Modele $modele): View
    {
        $categories = $this->catalogueService->getActiveCategories();

        return view('admin.catalogue.edit', compact('modele', 'categories'));
    }

    public function update(UpdateModelRequest $request, Modele $modele): RedirectResponse
    {
        $this->catalogueService->updateModele($modele, $request->validated());

        return redirect()->route('admin.catalogue.show', $modele)
            ->with('success', 'Modele mis a jour.');
    }

    public function destroy(Modele $modele): RedirectResponse
    {
        $modele->delete();

        return redirect()->route('admin.catalogue.index')
            ->with('success', 'Modele supprime.');
    }
}
