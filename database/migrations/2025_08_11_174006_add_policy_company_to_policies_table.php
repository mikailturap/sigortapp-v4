<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migrasyonları çalıştır.
     */
    public function up(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->string('policy_company')->nullable()->after('policy_type');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('policy_company');
        });
    }
};
