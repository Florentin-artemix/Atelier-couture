<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Accessoire;
use App\Models\CategorieModele;
use App\Models\Client;
use App\Models\Modele;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'nom' => 'Administrateur',
            'email' => 'admin@ateliercouture.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        // Create 8 accessories (prix en Franc Congolais - FC)
        $accessoires = [
            ['nom' => 'Boutons dorés', 'prix_unitaire' => 2500, 'unite' => 'piece', 'is_active' => true],
            ['nom' => 'Fermeture éclair', 'prix_unitaire' => 5000, 'unite' => 'piece', 'is_active' => true],
            ['nom' => 'Dentelle', 'prix_unitaire' => 8000, 'unite' => 'metre', 'is_active' => true],
            ['nom' => 'Fil de soie', 'prix_unitaire' => 3500, 'unite' => 'bobine', 'is_active' => true],
            ['nom' => 'Doublure satin', 'prix_unitaire' => 15000, 'unite' => 'metre', 'is_active' => true],
            ['nom' => 'Boutons nacre', 'prix_unitaire' => 3000, 'unite' => 'piece', 'is_active' => true],
            ['nom' => 'Broderie', 'prix_unitaire' => 20000, 'unite' => 'piece', 'is_active' => true],
            ['nom' => 'Perles', 'prix_unitaire' => 5000, 'unite' => 'lot', 'is_active' => true],
        ];

        foreach ($accessoires as $accessoire) {
            Accessoire::create($accessoire);
        }

        // Create 8 modeles assigned to categories
        $categories = CategorieModele::all();

        $modeles = [
            ['nom' => 'Chemise classique homme', 'slug' => 'chemise-classique-homme', 'prix_base' => 25000, 'categorie' => 'chemise', 'duree_estimee_jours' => 5],
            ['nom' => 'Chemise femme cintrée', 'slug' => 'chemise-femme-cintree', 'prix_base' => 30000, 'categorie' => 'chemise', 'duree_estimee_jours' => 5],
            ['nom' => 'Pantalon classique', 'slug' => 'pantalon-classique', 'prix_base' => 35000, 'categorie' => 'pantalon', 'duree_estimee_jours' => 7],
            ['nom' => 'Veste de costume', 'slug' => 'veste-de-costume', 'prix_base' => 75000, 'categorie' => 'veste', 'duree_estimee_jours' => 10],
            ['nom' => 'Robe de soirée', 'slug' => 'robe-de-soiree', 'prix_base' => 95000, 'categorie' => 'robe', 'duree_estimee_jours' => 14],
            ['nom' => 'Jupe évasée', 'slug' => 'jupe-evasee', 'prix_base' => 20000, 'categorie' => 'pagne-jupe', 'duree_estimee_jours' => 5],
            ['nom' => 'Kit costume 3 pièces', 'slug' => 'kit-costume-3-pieces', 'prix_base' => 150000, 'categorie' => 'kit-complet', 'duree_estimee_jours' => 21],
            ['nom' => 'Robe traditionnelle', 'slug' => 'robe-traditionnelle', 'prix_base' => 60000, 'categorie' => 'robe', 'duree_estimee_jours' => 10],
        ];

        foreach ($modeles as $modeleData) {
            $categorie = $categories->where('slug', $modeleData['categorie'])->first();

            if ($categorie) {
                Modele::create([
                    'nom' => $modeleData['nom'],
                    'slug' => $modeleData['slug'],
                    'prix_base' => $modeleData['prix_base'],
                    'categorie_modele_id' => $categorie->id,
                    'coefficient_complexite' => 1.00,
                    'duree_estimee_jours' => $modeleData['duree_estimee_jours'],
                    'is_active' => true,
                ]);
            }
        }

        // Create 5 demo clients
        for ($i = 0; $i < 5; $i++) {
            $user = User::create([
                'nom' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => UserRole::Client,
                'email_verified_at' => now(),
            ]);

            Client::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'telephone' => fake()->phoneNumber(),
                'email' => $user->email,
                'lien_suivi' => Str::random(64),
                'is_active' => true,
            ]);
        }
    }
}
