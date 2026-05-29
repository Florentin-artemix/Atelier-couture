<?php

namespace Database\Factories;

use App\Models\CategorieModele;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategorieModele>
 */
class CategorieModeleFactory extends Factory
{
    protected $model = CategorieModele::class;

    public function definition(): array
    {
        $nom = fake()->word();

        return [
            'nom' => $nom,
            'slug' => Str::slug($nom),
            'is_active' => true,
            'ordre_affichage' => fake()->numberBetween(0, 100),
        ];
    }
}
