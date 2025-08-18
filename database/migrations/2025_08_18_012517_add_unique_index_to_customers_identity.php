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
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('customer_identity_number', 'customers_identity_unique');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_identity_unique');
        });
    }
};
