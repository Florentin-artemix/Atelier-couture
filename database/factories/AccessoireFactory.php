<?php

namespace Database\Factories;

use App\Models\Accessoire;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accessoire>
 */
class AccessoireFactory extends Factory
{
    protected $model = Accessoire::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->word(),
            'prix_unitaire' => fake()->numberBetween(100, 5000),
            'unite' => 'piece',
            'is_active' => true,
        ];
    }
}
