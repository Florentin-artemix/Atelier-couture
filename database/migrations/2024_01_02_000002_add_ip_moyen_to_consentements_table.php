<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consentements', function (Blueprint $table) {
            if (!Schema::hasColumn('consentements', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('date_consentement');
            }
            if (!Schema::hasColumn('consentements', 'moyen')) {
                $table->string('moyen', 50)->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consentements', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'moyen']);
        });
    }
};
