<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Mesures supplementaires demandees par le tailleur (precommande complexe).
            // Tableau d'IDs de mesure_types, en plus du socle de base + mesures categorie.
            $table->json('mesures_demandees')->nullable()->after('notes_client');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('mesures_demandees');
        });
    }
};
