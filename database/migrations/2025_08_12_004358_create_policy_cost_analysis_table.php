<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policy_cost_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            
            // Maliyet Dağılımı
            $table->decimal('insurance_company_cost', 10, 2)->nullable(); // Sigorta şirketi maliyeti
            $table->decimal('brokerage_cost', 10, 2)->nullable(); // Broker maliyeti
            $table->decimal('operational_cost', 10, 2)->nullable(); // Operasyonel maliyet
            $table->decimal('marketing_cost', 10, 2)->nullable(); // Pazarlama maliyeti
            $table->decimal('administrative_cost', 10, 2)->nullable(); // İdari maliyet
            
            // Gelir Analizi
            $table->decimal('gross_revenue', 10, 2)->nullable(); // Brüt gelir
            $table->decimal('net_revenue', 10, 2)->nullable(); // Net gelir
            $table->decimal('profit_margin', 10, 2)->nullable(); // Kar marjı
            $table->decimal('profit_margin_percentage', 5, 2)->nullable(); // Kar marjı yüzdesi
            
            // Risk ve Rezerv
            $table->decimal('risk_reserve', 10, 2)->nullable(); // Risk rezervi
            $table->decimal('commission_reserve', 10, 2)->nullable(); // Komisyon rezervi
            $table->decimal('tax_reserve', 10, 2)->nullable(); // Vergi rezervi
            
            // Analiz Tarihi
            $table->timestamp('analysis_date');
            $table->string('analysis_period'); // aylık, yıllık, poliçe bazlı
            
            // Notlar
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // İndeksler
            $table->index(['policy_id', 'analysis_date']);
            $table->index('analysis_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policy_cost_analysis');
    }
};
