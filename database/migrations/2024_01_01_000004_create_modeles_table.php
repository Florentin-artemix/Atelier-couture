<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_modele_id')->constrained('categorie_modeles')->cascadeOnDelete();
            $table->string('nom', 150);
            $table->string('slug', 170)->unique();
            $table->text('description')->nullable();
            $table->decimal('prix_base', 10, 2);
            $table->decimal('coefficient_complexite', 4, 2)->default(1.00);
            $table->integer('duree_estimee_jours')->nullable();
            $table->string('image_principale')->nullable();
            $table->json('images_supplementaires')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modeles');
    }
};
