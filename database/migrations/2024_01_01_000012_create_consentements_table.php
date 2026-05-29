<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consentements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('type', 50);
            $table->boolean('accepte')->default(false);
            $table->dateTime('date_consentement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consentements');
    }
};
