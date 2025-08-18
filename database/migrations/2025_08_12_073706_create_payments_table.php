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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('policy_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_schedule_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_number')->unique(); // Ödeme numarası
            $table->decimal('amount', 15, 2); // Ödeme tutarı
            $table->string('payment_method'); // Ödeme yöntemi
            $table->string('payment_status')->default('tamamlandı'); // tamamlandı, iptal, iade
            $table->date('payment_date'); // Ödeme tarihi
            $table->string('reference_number')->nullable(); // Referans numarası
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();
            
            // İndeksler
            $table->index('customer_id');
            $table->index('policy_id');
            $table->index('payment_schedule_id');
            $table->index('payment_date');
            $table->index('payment_status');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
