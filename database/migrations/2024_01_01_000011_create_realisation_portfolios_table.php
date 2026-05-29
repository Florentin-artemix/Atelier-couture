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
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->foreignId('modele_id')->nullable()->constrained('modeles')->nullOnDelete();
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->json('images');
            $table->boolean('is_featured')->default(false);
            $table->date('date_realisation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_portfolios');
    }
};
