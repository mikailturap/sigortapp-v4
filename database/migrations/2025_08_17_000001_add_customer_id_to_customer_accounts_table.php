<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrasyonları çalıştır.
     */
    public function up(): void
    {
        Schema::table('customer_accounts', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        // Kimlik numarasından müşteri_id doldur
        DB::statement('
            UPDATE customer_accounts ca
            JOIN customers c ON ca.customer_identity_number = c.customer_identity_number
            SET ca.customer_id = c.id
            WHERE ca.customer_id IS NULL
        ');

        // Her müşteri için tek hesap
        Schema::table('customer_accounts', function (Blueprint $table) {
            $table->unique('customer_id');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::table('customer_accounts', function (Blueprint $table) {
            $table->dropUnique(['customer_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};


