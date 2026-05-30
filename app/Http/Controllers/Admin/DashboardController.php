<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $data = $this->dashboardService->getDonneesDashboard();

        return view('admin.dashboard.index', $data);
    }
}
