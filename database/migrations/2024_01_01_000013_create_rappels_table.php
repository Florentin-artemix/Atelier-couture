<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->enum('type', ['pre_livraison', 'retard', 'precommande', 'manuel']);
            $table->string('titre', 200);
            $table->text('description')->nullable();
            $table->date('date_echeance');
            $table->boolean('is_done')->default(false);
            $table->dateTime('date_fait')->nullable();
            $table->timestamps();

            $table->index('commande_id');
            $table->index('client_id');
            $table->index('is_done');
            $table->index('date_echeance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rappels');
    }
};
