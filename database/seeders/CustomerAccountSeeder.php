<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Policy;
use App\Models\CustomerAccount;
use Illuminate\Support\Facades\DB;

class CustomerAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Mevcut poliçelerden benzersiz müşterileri al
        $customers = Policy::select('customer_identity_number', 'customer_title', 'customer_phone', 'customer_address')
            ->whereNotNull('customer_identity_number')
            ->where('customer_identity_number', '!=', '')
            ->distinct()
            ->get();

        foreach ($customers as $customer) {
            // Müşteri cari hesabı oluştur veya güncelle
            CustomerAccount::updateOrCreate(
                ['customer_identity_number' => $customer->customer_identity_number],
                [
                    'customer_title' => $customer->customer_title,
                    'phone' => $customer->customer_phone,
                    'address' => $customer->customer_address,
                    'current_balance' => 0,
                    'credit_limit' => 10000, // Varsayılan kredi limiti
                    'payment_terms_days' => 30,
                    'risk_level' => 'düşük',
                    'days_overdue' => 0,
                    'is_active' => true
                ]
            );
        }

        // Müşteri bakiyelerini güncelle
        $this->updateCustomerBalances();

        $this->command->info('Müşteri cari hesapları oluşturuldu: ' . $customers->count());
    }

    private function updateCustomerBalances()
    {
        // Her müşteri için toplam bakiye hesapla
        $customerBalances = Policy::select('customer_identity_number')
            ->selectRaw('SUM(CASE WHEN payment_status != "ödendi" THEN total_amount ELSE 0 END) as total_balance')
            ->whereNotNull('customer_identity_number')
            ->groupBy('customer_identity_number')
            ->get();

        foreach ($customerBalances as $balance) {
            CustomerAccount::where('customer_identity_number', $balance->customer_identity_number)
                ->update(['current_balance' => $balance->total_balance ?? 0]);
        }

        // Risk seviyelerini güncelle
        $this->updateRiskLevels();
    }

    private function updateRiskLevels()
    {
        // Gecikmiş ödemeleri hesapla ve risk seviyelerini güncelle
        $overdueCustomers = Policy::select('customer_identity_number')
            ->selectRaw('MAX(DATEDIFF(CURDATE(), payment_due_date)) as max_overdue_days')
            ->where('payment_due_date', '<', now())
            ->where('payment_status', '!=', 'ödendi')
            ->groupBy('customer_identity_number')
            ->get();

        foreach ($overdueCustomers as $overdue) {
            $riskLevel = $this->calculateRiskLevel($overdue->max_overdue_days);
            
            CustomerAccount::where('customer_identity_number', $overdue->customer_identity_number)
                ->update([
                    'days_overdue' => $overdue->max_overdue_days,
                    'risk_level' => $riskLevel
                ]);
        }
    }

    private function calculateRiskLevel($overdueDays)
    {
        if ($overdueDays > 90) return 'kritik';
        if ($overdueDays > 60) return 'yüksek';
        if ($overdueDays > 30) return 'orta';
        if ($overdueDays > 0) return 'düşük';
        return 'düşük';
    }
}
