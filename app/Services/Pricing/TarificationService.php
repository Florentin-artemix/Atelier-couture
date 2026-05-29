<?php

namespace App\Services\Pricing;

use App\Exceptions\BusinessException;
use App\Models\Accessoire;
use App\Models\Commande;
use App\Models\Modele;
use App\Repositories\Contracts\OrderRepositoryInterface;

class TarificationService
{
    private float $tauxReductionFourni;
    private float $coefficientMin;
    private float $coefficientMax;

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {
        $this->tauxReductionFourni = (float) config('pricing.taux_reduction_fourni_client', 0.80);
        $this->coefficientMin = (float) config('pricing.coefficient_min', 0.50);
        $this->coefficientMax = (float) config('pricing.coefficient_max', 5.00);
    }

    public function recalculer(Commande $commande): Commande
    {
        $commande->load(['accessoires', 'modele']);

        $details = $this->calculerDetails($commande);

        $this->orderRepository->update($commande, [
            'prix_propose' => $details['prix_propose'],
            'reduction_client_fournit' => $details['reduction'],
        ]);

        return $commande->fresh();
    }

    public function calculerDetails(Commande $commande): array
    {
        $commande->load(['accessoires', 'modele']);
        $modele = $commande->modele;

        $prixBaseCalcule = $this->calculerPrixBase($modele);
        $totalAccessoires = 0.0;
        $reduction = 0.0;
        $lignesAccessoires = [];

        foreach ($commande->accessoires as $accessoire) {
            $pivot = $accessoire->pivot;
            $montantLigne = (float) $pivot->prix_unitaire_snapshot * $pivot->quantite;

            if ($pivot->fourni_par_client) {
                $reductionLigne = $montantLigne * $this->tauxReductionFourni;
                $reduction += $reductionLigne;
                $lignesAccessoires[] = [
                    'accessoire_id' => $accessoire->id,
                    'nom' => $accessoire->nom,
                    'quantite' => $pivot->quantite,
                    'prix_unitaire' => (float) $pivot->prix_unitaire_snapshot,
                    'montant' => $montantLigne,
                    'fourni_par_client' => true,
                    'reduction_appliquee' => round($reductionLigne, 2),
                ];
            } else {
                $totalAccessoires += $montantLigne;
                $lignesAccessoires[] = [
                    'accessoire_id' => $accessoire->id,
                    'nom' => $accessoire->nom,
                    'quantite' => $pivot->quantite,
                    'prix_unitaire' => (float) $pivot->prix_unitaire_snapshot,
                    'montant' => $montantLigne,
                    'fourni_par_client' => false,
                    'reduction_appliquee' => 0,
                ];
            }
        }

        $prixPropose = max(0, $prixBaseCalcule + $totalAccessoires - $reduction);

        return [
            'prix_base_modele' => (float) $modele->prix_base,
            'coefficient' => (float) $modele->coefficient_complexite,
            'prix_base_calcule' => round($prixBaseCalcule, 2),
            'total_accessoires' => round($totalAccessoires, 2),
            'reduction' => round($reduction, 2),
            'prix_propose' => round($prixPropose, 2),
            'lignes_accessoires' => $lignesAccessoires,
        ];
    }

    public function estimer(Modele $modele, array $accessoiresData = []): array
    {
        $prixBaseCalcule = $this->calculerPrixBase($modele);
        $totalAccessoires = 0.0;
        $reduction = 0.0;

        foreach ($accessoiresData as $item) {
            $accessoire = Accessoire::find($item['accessoire_id']);
            if (!$accessoire) {
                continue;
            }

            $prixUnitaire = (float) $accessoire->prix_unitaire;
            $quantite = (int) ($item['quantite'] ?? 1);
            $fourniParClient = (bool) ($item['fourni_par_client'] ?? false);

            $montant = $prixUnitaire * $quantite;

            if ($fourniParClient) {
                $reduction += $montant * $this->tauxReductionFourni;
            } else {
                $totalAccessoires += $montant;
            }
        }

        $prixPropose = max(0, $prixBaseCalcule + $totalAccessoires - $reduction);

        return [
            'prix_base_calcule' => round($prixBaseCalcule, 2),
            'total_accessoires' => round($totalAccessoires, 2),
            'reduction' => round($reduction, 2),
            'prix_propose' => round($prixPropose, 2),
        ];
    }

    public function validerCoefficient(float $coefficient): void
    {
        if ($coefficient < $this->coefficientMin || $coefficient > $this->coefficientMax) {
            throw new BusinessException(
                "Le coefficient de complexite doit etre entre {$this->coefficientMin} et {$this->coefficientMax}."
            );
        }
    }

    private function calculerPrixBase(Modele $modele): float
    {
        return (float) $modele->prix_base * (float) $modele->coefficient_complexite;
    }
}
