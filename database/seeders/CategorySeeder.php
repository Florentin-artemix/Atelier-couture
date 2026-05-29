<?php

namespace Database\Seeders;

use App\Models\CategorieModele;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Chemise',
                'slug' => 'chemise',
                'description' => 'Chemises sur mesure pour homme et femme',
                'is_active' => true,
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Pantalon',
                'slug' => 'pantalon',
                'description' => 'Pantalons sur mesure classiques et modernes',
                'is_active' => true,
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Veste',
                'slug' => 'veste',
                'description' => 'Vestes et blazers sur mesure',
                'is_active' => true,
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Robe',
                'slug' => 'robe',
                'description' => 'Robes sur mesure pour toutes occasions',
                'is_active' => true,
                'ordre_affichage' => 4,
            ],
            [
                'nom' => 'Pagne / Jupe',
                'slug' => 'pagne-jupe',
                'description' => 'Pagnes et jupes traditionnels et modernes',
                'is_active' => true,
                'ordre_affichage' => 5,
            ],
            [
                'nom' => 'Kit complet',
                'slug' => 'kit-complet',
                'description' => 'Ensembles complets sur mesure',
                'is_active' => true,
                'ordre_affichage' => 6,
            ],
        ];

        foreach ($categories as $category) {
            CategorieModele::create($category);
        }
    }
}
