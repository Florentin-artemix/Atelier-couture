<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccessoryRequest;
use App\Models\Accessoire;
use App\Services\Accessory\AccessoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccessoryController extends Controller
{
    public function __construct(
        private AccessoryService $accessoryService,
    ) {}

    public function index(): View
    {
        $accessoires = $this->accessoryService->getAll();

        return view('admin.accessoires.index', compact('accessoires'));
    }

    public function create(): View
    {
        return view('admin.accessoires.create');
    }

    public function store(StoreAccessoryRequest $request): RedirectResponse
    {
        $this->accessoryService->creer($request->validated());

        return redirect()->route('admin.accessoires.index')
            ->with('success', 'Accessoire cree avec succes.');
    }

    public function edit(Accessoire $accessoire): View
    {
        return view('admin.accessoires.edit', compact('accessoire'));
    }

    public function update(StoreAccessoryRequest $request, Accessoire $accessoire): RedirectResponse
    {
        $this->accessoryService->update($accessoire, $request->validated());

        return redirect()->route('admin.accessoires.index')
            ->with('success', 'Accessoire mis a jour.');
    }

    public function destroy(Accessoire $accessoire): RedirectResponse
    {
        $this->accessoryService->delete($accessoire);

        return redirect()->route('admin.accessoires.index')
            ->with('success', 'Accessoire supprime.');
    }
}
