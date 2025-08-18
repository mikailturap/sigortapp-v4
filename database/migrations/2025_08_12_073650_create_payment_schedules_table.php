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
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('policy_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description'); // Açıklama
            $table->decimal('amount', 15, 2); // Tutar
            $table->date('due_date'); // Vade tarihi
            $table->string('status')->default('bekliyor'); // bekliyor, ödendi, gecikti
            $table->string('payment_type')->default('taksit'); // taksit, peşin, ek ödeme
            $table->integer('installment_number')->nullable(); // Taksit numarası
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();
            
            // İndeksler
            $table->index('customer_id');
            $table->index('policy_id');
            $table->index('due_date');
            $table->index('status');
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedules');
    }
};
