<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesure_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('mesure_type_id')->constrained('mesure_types')->cascadeOnDelete();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->decimal('valeur', 8, 2);
            $table->date('date_prise');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['client_id', 'mesure_type_id', 'commande_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesure_clients');
    }
};
