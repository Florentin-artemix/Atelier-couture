<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReminderRequest;
use App\Models\Rappel;
use App\Services\Notification\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function index(): View
    {
        $rappels = $this->notificationService->getRappelsEnAttente();

        return view('admin.rappels.index', compact('rappels'));
    }

    public function store(StoreReminderRequest $request): RedirectResponse
    {
        $this->notificationService->creerRappelManuel($request->validated());

        return redirect()->route('admin.rappels.index')
            ->with('success', 'Rappel cree avec succes.');
    }

    public function markDone(Rappel $rappel): RedirectResponse
    {
        $this->notificationService->marquerFait($rappel);

        return redirect()->route('admin.rappels.index')
            ->with('success', 'Rappel marque comme traite.');
    }
}
