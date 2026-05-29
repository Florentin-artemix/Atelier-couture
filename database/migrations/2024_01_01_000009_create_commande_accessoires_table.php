<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande_accessoires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->cascadeOnDelete();
            $table->foreignId('accessoire_id')->constrained('accessoires')->restrictOnDelete();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire_snapshot', 10, 2);
            $table->boolean('fourni_par_client')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_accessoires');
    }
};
