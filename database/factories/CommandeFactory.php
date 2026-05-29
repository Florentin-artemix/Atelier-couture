<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Modele;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        return [
            'reference' => 'CMD-' . date('Y') . '-' . str_pad(fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'client_id' => Client::factory(),
            'modele_id' => Modele::factory(),
            'type' => OrderType::Physique,
            'statut' => OrderStatus::Nouvelle,
            'date_commande' => fake()->date(),
            'date_livraison_prevue' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'lien_suivi' => Str::random(64),
        ];
    }
}
