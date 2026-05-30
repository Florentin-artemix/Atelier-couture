<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\CategorieModele;
use App\Services\Catalogue\CatalogueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CatalogueService $catalogueService,
    ) {}

    public function index(): View
    {
        $categories = $this->catalogueService->getActiveCategories();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->catalogueService->creerCategorie($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categorie creee avec succes.');
    }

    public function edit(CategorieModele $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, CategorieModele $category): RedirectResponse
    {
        $this->catalogueService->updateCategorie($category, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categorie mise a jour.');
    }
}
