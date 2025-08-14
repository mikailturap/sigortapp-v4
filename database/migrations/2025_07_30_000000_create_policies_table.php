<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            // Müşteri Bilgileri
            $table->string('customer_title');
            $table->string('customer_identity_number');
            $table->string('customer_phone');
            $table->date('customer_birth_date');
            $table->text('customer_address');

            // Sigorta Ettiren Bilgileri (nullable, çünkü müşteri ile aynı olabilir)
            $table->string('insured_name')->nullable();
            $table->string('insured_phone')->nullable();

            // Poliçe Detayları
            $table->string('policy_type');
            $table->string('policy_number')->unique();
            $table->string('plate_or_other')->nullable();
            $table->date('issue_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('document_info')->nullable();

            // TARSİM Bilgileri (nullable)
            $table->string('tarsim_business_number')->nullable();
            $table->string('tarsim_animal_number')->nullable();

            // Ek Alanlar
            $table->string('status')->default('aktif'); // aktif, pasif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
