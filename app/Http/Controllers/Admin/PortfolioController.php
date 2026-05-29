<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePortfolioRequest;
use App\Models\RealisationPortfolio;
use App\Services\Portfolio\PortfolioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
    ) {}

    public function index(): View
    {
        $entries = $this->portfolioService->getAllEntries();

        return view('admin.portfolio.index', compact('entries'));
    }

    public function create(): View
    {
        return view('admin.portfolio.create');
    }

    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $this->portfolioService->creerRealisation($request->validated());

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Realisation ajoutee au portfolio.');
    }

    public function edit(RealisationPortfolio $portfolio): View
    {
        return view('admin.portfolio.edit', compact('portfolio'));
    }

    public function update(StorePortfolioRequest $request, RealisationPortfolio $portfolio): RedirectResponse
    {
        $this->portfolioService->updateEntry($portfolio, $request->validated());

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Realisation mise a jour.');
    }

    public function destroy(RealisationPortfolio $portfolio): RedirectResponse
    {
        $this->portfolioService->supprimerRealisation($portfolio);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Realisation supprimee.');
    }
}
