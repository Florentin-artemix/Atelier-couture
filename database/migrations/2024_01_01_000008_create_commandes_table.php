<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('client_id')->constrained('clients')->restrictOnDelete();
            $table->foreignId('modele_id')->constrained('modeles')->restrictOnDelete();
            $table->enum('type', ['physique', 'a_distance', 'precommande']);
            $table->enum('statut', ['precommande', 'nouvelle', 'en_attente_mesures', 'en_production', 'prete', 'livree', 'annulee'])->default('nouvelle');
            $table->decimal('prix_propose', 10, 2)->nullable();
            $table->decimal('prix_final', 10, 2)->nullable();
            $table->decimal('reduction_client_fournit', 10, 2)->default(0.00);
            $table->date('date_commande');
            $table->date('date_livraison_prevue');
            $table->date('date_livraison_reelle')->nullable();
            $table->text('notes_internes')->nullable();
            $table->text('notes_client')->nullable();
            $table->string('lien_suivi', 64)->unique();
            $table->timestamps();

            $table->index('client_id');
            $table->index('modele_id');
            $table->index('statut');
            $table->index('type');
            $table->index('date_commande');
            $table->index('date_livraison_prevue');
            $table->index(['statut', 'date_livraison_prevue']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
