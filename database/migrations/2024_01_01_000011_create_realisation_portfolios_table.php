<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->foreignId('categorie_modele_id')->nullable()->constrained('categorie_modeles')->nullOnDelete();
            $table->foreignId('modele_id')->nullable()->constrained('modeles')->nullOnDelete();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->string('image_principale', 255)->nullable();
            $table->json('images_supplementaires')->nullable();
            $table->date('date_realisation')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('ordre_affichage')->default(0);
            $table->timestamps();

            $table->index('categorie_modele_id');
            $table->index('modele_id');
            $table->index('commande_id');
            $table->index(['is_visible', 'ordre_affichage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_portfolios');
    }
};
