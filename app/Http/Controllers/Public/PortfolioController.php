<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\Portfolio\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        private PortfolioService $portfolioService,
    ) {}

    public function index(Request $request): View
    {
        $realisations = $this->portfolioService->getRealisations($request->input('categorie_id'));

        return view('public.portfolio.index', compact('realisations'));
    }
}
