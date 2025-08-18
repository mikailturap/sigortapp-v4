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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_title'); // Müşteri ünvanı
            $table->string('customer_identity_number'); // TC/Vergi no
            $table->string('phone')->nullable(); // Telefon
            $table->string('email')->nullable(); // E-posta
            $table->text('address')->nullable(); // Adres
            $table->decimal('credit_limit', 15, 2)->default(0); // Kredi limiti
            $table->string('risk_level')->default('düşük'); // Risk seviyesi
            $table->string('customer_type')->default('bireysel'); // Bireysel/Kurumsal
            $table->string('status')->default('aktif'); // Aktif/Pasif
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();
            
            // İndeksler
            $table->index('customer_identity_number');
            $table->index('customer_title');
            $table->index('risk_level');
            $table->index('status');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
