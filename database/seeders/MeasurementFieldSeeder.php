<?php

namespace Database\Seeders;

use App\Models\CategorieModele;
use App\Models\MesureType;
use Illuminate\Database\Seeder;

class MeasurementFieldSeeder extends Seeder
{
    public function run(): void
    {
        // Base measurements (common to all categories)
        $baseMeasurements = [
            ['nom' => 'tour_poitrine', 'libelle' => 'Tour de poitrine', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 1],
            ['nom' => 'tour_taille', 'libelle' => 'Tour de taille', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 2],
            ['nom' => 'tour_hanches', 'libelle' => 'Tour de hanches', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 3],
            ['nom' => 'largeur_epaules', 'libelle' => 'Largeur des epaules', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 4],
            ['nom' => 'longueur_bras', 'libelle' => 'Longueur des bras', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 5],
            ['nom' => 'longueur_vetement', 'libelle' => 'Longueur du vetement', 'unite' => 'cm', 'is_base' => true, 'ordre_affichage' => 6],
        ];

        // Specific measurements
        $specificMeasurements = [
            ['nom' => 'tour_cou', 'libelle' => 'Tour de cou', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 7],
            ['nom' => 'longueur_entrejambe', 'libelle' => 'Longueur entrejambe', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 8],
            ['nom' => 'longueur_robe', 'libelle' => 'Longueur de la robe', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 9],
            ['nom' => 'tour_cuisse', 'libelle' => 'Tour de cuisse', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 10],
            ['nom' => 'tour_mollet', 'libelle' => 'Tour de mollet', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 11],
            ['nom' => 'longueur_jupe', 'libelle' => 'Longueur de la jupe', 'unite' => 'cm', 'is_base' => false, 'ordre_affichage' => 12],
        ];

        foreach ($baseMeasurements as $measurement) {
            MesureType::create($measurement);
        }

        foreach ($specificMeasurements as $measurement) {
            MesureType::create($measurement);
        }

        // Attach specific measurements to categories via pivot
        $chemise = CategorieModele::where('slug', 'chemise')->first();
        $pantalon = CategorieModele::where('slug', 'pantalon')->first();
        $veste = CategorieModele::where('slug', 'veste')->first();
        $robe = CategorieModele::where('slug', 'robe')->first();
        $pagneJupe = CategorieModele::where('slug', 'pagne-jupe')->first();
        $kitComplet = CategorieModele::where('slug', 'kit-complet')->first();

        $tourCou = MesureType::where('nom', 'tour_cou')->first();
        $longueurEntrejambe = MesureType::where('nom', 'longueur_entrejambe')->first();
        $longueurRobe = MesureType::where('nom', 'longueur_robe')->first();
        $tourCuisse = MesureType::where('nom', 'tour_cuisse')->first();
        $tourMollet = MesureType::where('nom', 'tour_mollet')->first();
        $longueurJupe = MesureType::where('nom', 'longueur_jupe')->first();

        // Chemise needs tour_cou
        if ($chemise && $tourCou) {
            $chemise->mesureTypes()->attach($tourCou->id, ['is_obligatoire' => true]);
        }

        // Pantalon needs entrejambe, tour_cuisse, tour_mollet
        if ($pantalon) {
            if ($longueurEntrejambe) {
                $pantalon->mesureTypes()->attach($longueurEntrejambe->id, ['is_obligatoire' => true]);
            }
            if ($tourCuisse) {
                $pantalon->mesureTypes()->attach($tourCuisse->id, ['is_obligatoire' => true]);
            }
            if ($tourMollet) {
                $pantalon->mesureTypes()->attach($tourMollet->id, ['is_obligatoire' => false]);
            }
        }

        // Veste needs tour_cou
        if ($veste && $tourCou) {
            $veste->mesureTypes()->attach($tourCou->id, ['is_obligatoire' => true]);
        }

        // Robe needs longueur_robe
        if ($robe && $longueurRobe) {
            $robe->mesureTypes()->attach($longueurRobe->id, ['is_obligatoire' => true]);
        }

        // Pagne / Jupe needs longueur_jupe
        if ($pagneJupe && $longueurJupe) {
            $pagneJupe->mesureTypes()->attach($longueurJupe->id, ['is_obligatoire' => true]);
        }

        // Kit complet needs tour_cou, entrejambe, tour_cuisse
        if ($kitComplet) {
            if ($tourCou) {
                $kitComplet->mesureTypes()->attach($tourCou->id, ['is_obligatoire' => true]);
            }
            if ($longueurEntrejambe) {
                $kitComplet->mesureTypes()->attach($longueurEntrejambe->id, ['is_obligatoire' => true]);
            }
            if ($tourCuisse) {
                $kitComplet->mesureTypes()->attach($tourCuisse->id, ['is_obligatoire' => false]);
            }
        }
    }
}
