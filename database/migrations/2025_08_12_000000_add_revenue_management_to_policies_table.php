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
            // Gelir Yönetimi Alanları
            $table->decimal('policy_premium', 10, 2)->nullable()->after('status'); // Poliçe primi
            $table->decimal('commission_rate', 5, 2)->nullable()->after('policy_premium'); // Komisyon oranı (%)
            $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_rate'); // Komisyon tutarı
            $table->decimal('net_revenue', 10, 2)->nullable()->after('commission_amount'); // Net gelir
            $table->string('payment_status')->default('bekliyor')->after('net_revenue'); // Ödeme durumu: bekliyor, ödendi, gecikmiş
            $table->date('payment_due_date')->nullable()->after('payment_status'); // Ödeme vade tarihi
            $table->date('payment_date')->nullable()->after('payment_due_date'); // Ödeme yapılan tarih
            $table->text('payment_notes')->nullable()->after('payment_date'); // Ödeme notları
            $table->string('payment_method')->nullable()->after('payment_notes'); // Ödeme yöntemi
            $table->string('invoice_number')->nullable()->after('payment_method'); // Fatura numarası
            $table->decimal('tax_rate', 5, 2)->default(18.00)->after('invoice_number'); // Vergi oranı (%)
            $table->decimal('tax_amount', 10, 2)->nullable()->after('tax_rate'); // Vergi tutarı
            $table->decimal('total_amount', 10, 2)->nullable()->after('tax_amount'); // Toplam tutar (vergi dahil)
            
            // Poliçe Maliyet Analizi
            $table->decimal('policy_cost', 10, 2)->nullable()->after('total_amount'); // Poliçe maliyeti
            $table->decimal('brokerage_fee', 10, 2)->nullable()->after('policy_cost'); // Broker komisyonu
            $table->decimal('operational_cost', 10, 2)->nullable()->after('brokerage_fee'); // Operasyonel maliyet
            $table->decimal('profit_margin', 10, 2)->nullable()->after('operational_cost'); // Kar marjı
            
            // Müşteri Cari Takibi
            $table->decimal('customer_balance', 10, 2)->default(0)->after('profit_margin'); // Müşteri cari bakiyesi
            $table->string('customer_payment_terms')->nullable()->after('customer_balance'); // Ödeme koşulları
            $table->integer('customer_credit_limit')->nullable()->after('customer_payment_terms'); // Kredi limiti (gün)
            
            // Ödeme Geçmişi Referansı
            $table->string('last_payment_reference')->nullable()->after('customer_credit_limit'); // Son ödeme referansı
            $table->timestamp('last_payment_reminder')->nullable()->after('last_payment_reference'); // Son hatırlatma
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn([
                'policy_premium', 'commission_rate', 'commission_amount', 'net_revenue',
                'payment_status', 'payment_due_date', 'payment_date', 'payment_notes',
                'payment_method', 'invoice_number', 'tax_rate', 'tax_amount', 'total_amount',
                'policy_cost', 'brokerage_fee', 'operational_cost', 'profit_margin',
                'customer_balance', 'customer_payment_terms', 'customer_credit_limit',
                'last_payment_reference', 'last_payment_reminder'
            ]);
        });
    }
};
