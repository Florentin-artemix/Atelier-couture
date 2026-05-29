<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesure_types', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('libelle', 100);
            $table->string('unite', 20)->default('cm');
            $table->boolean('is_base')->default(false);
            $table->decimal('valeur_min', 8, 2)->nullable();
            $table->decimal('valeur_max', 8, 2)->nullable();
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesure_types');
    }
};
