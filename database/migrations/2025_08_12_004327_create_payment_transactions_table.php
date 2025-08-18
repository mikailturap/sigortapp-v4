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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // İşlemi yapan kullanıcı
            
            // Ödeme Detayları
            $table->string('transaction_type'); // ödeme, iade, düzeltme
            $table->decimal('amount', 10, 2); // İşlem tutarı
            $table->string('currency', 3)->default('TRY');
            $table->string('payment_method'); // nakit, kredi kartı, banka havalesi, çek
            $table->string('payment_status'); // başarılı, başarısız, bekliyor, iptal
            $table->string('reference_number')->nullable(); // Banka/ödeme referans numarası
            
            // Fatura ve Muhasebe
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('accounting_code')->nullable(); // Muhasebe hesap kodu
            
            // Tarih Bilgileri
            $table->timestamp('transaction_date');
            $table->timestamp('processed_at')->nullable();
            
            // Notlar ve Detaylar
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Ek bilgiler (JSON)
            
            $table->timestamps();
            
            // İndeksler
            $table->index(['policy_id', 'transaction_date']);
            $table->index(['payment_status', 'transaction_date']);
            $table->index('reference_number');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
