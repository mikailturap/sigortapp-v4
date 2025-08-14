<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_identity_number')->unique(); // TC/Vergi No
            $table->string('customer_title'); // Müşteri ünvanı
            
            // Cari Hesap Bilgileri
            $table->decimal('current_balance', 12, 2)->default(0); // Mevcut bakiye
            $table->decimal('credit_limit', 12, 2)->default(0); // Kredi limiti
            $table->integer('payment_terms_days')->default(30); // Ödeme vadesi (gün)
            $table->string('payment_method_preference')->nullable(); // Tercih edilen ödeme yöntemi
            
            // Risk Analizi
            $table->string('risk_level')->default('düşük'); // düşük, orta, yüksek
            $table->integer('days_overdue')->default(0); // Gecikmiş gün sayısı
            $table->timestamp('last_payment_date')->nullable(); // Son ödeme tarihi
            $table->timestamp('last_activity_date')->nullable(); // Son aktivite tarihi
            
            // İletişim ve Adres
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            
            // Muhasebe Bilgileri
            $table->string('accounting_code')->nullable(); // Müşteri hesap kodu
            $table->string('tax_office')->nullable(); // Vergi dairesi
            $table->string('tax_number')->nullable(); // Vergi numarası
            
            // Notlar ve Durum
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // İndeksler
            $table->index(['customer_identity_number']);
            $table->index(['current_balance', 'risk_level']);
            $table->index('days_overdue');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_accounts');
    }
};
