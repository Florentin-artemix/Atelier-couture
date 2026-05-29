<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Modele;
use App\Services\Pricing\TarificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function __construct(
        private TarificationService $tarificationService,
    ) {}

    public function calculate(Request $request): JsonResponse
    {
        $modele = Modele::findOrFail($request->input('modele_id'));
        $accessoires = $request->input('accessoires', []);

        $estimation = $this->tarificationService->estimer($modele, $accessoires);

        return response()->json($estimation);
    }

    public function recalculate(Commande $commande): JsonResponse
    {
        $commande = $this->tarificationService->recalculer($commande);
        $details = $this->tarificationService->calculerDetails($commande);

        return response()->json($details);
    }
}
