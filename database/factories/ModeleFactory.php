<?php

namespace Database\Factories;

use App\Models\CategorieModele;
use App\Models\Modele;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modele>
 */
class ModeleFactory extends Factory
{
    protected $model = Modele::class;

    public function definition(): array
    {
        $nom = fake()->words(3, true);

        return [
            'categorie_modele_id' => CategorieModele::factory(),
            'nom' => $nom,
            'slug' => Str::slug($nom),
            'prix_base' => fake()->numberBetween(5000, 100000),
            'coefficient_complexite' => 1.00,
            'is_active' => true,
        ];
    }
}
