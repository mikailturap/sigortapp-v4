<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Policy;
use App\Models\PaymentTransaction;
use App\Models\User;
use Carbon\Carbon;

class PaymentTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Örnek ödeme işlemleri oluştur
        $policies = Policy::whereNotNull('policy_premium')->take(50)->get();
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('Kullanıcı bulunamadı!');
            return;
        }

        $paymentMethods = ['nakit', 'kredi kartı', 'banka havalesi', 'çek'];
        $transactionTypes = ['ödeme', 'iade', 'düzeltme'];
        $paymentStatuses = ['başarılı', 'bekliyor', 'başarısız', 'iptal'];

        foreach ($policies as $policy) {
            // Her poliçe için 1-3 ödeme işlemi oluştur
            $transactionCount = rand(1, 3);
            
            for ($i = 0; $i < $transactionCount; $i++) {
                $amount = $policy->policy_premium ? $policy->policy_premium * (rand(80, 120) / 100) : rand(100, 1000);
                $status = $paymentStatuses[array_rand($paymentStatuses)];
                $method = $paymentMethods[array_rand($paymentMethods)];
                $type = $transactionTypes[array_rand($transactionTypes)];
                
                // Tarih: son 6 ay içinde rastgele
                $transactionDate = Carbon::now()->subDays(rand(0, 180));
                
                PaymentTransaction::create([
                    'policy_id' => $policy->id,
                    'user_id' => $users->random()->id,
                    'transaction_type' => $type,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'payment_method' => $method,
                    'payment_status' => $status,
                    'reference_number' => 'REF-' . strtoupper(uniqid()),
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($policy->id, 6, '0', STR_PAD_LEFT),
                    'invoice_date' => $transactionDate,
                    'accounting_code' => '120.01', // Varsayılan muhasebe kodu
                    'transaction_date' => $transactionDate,
                    'processed_at' => $status === 'başarılı' ? $transactionDate : null,
                    'notes' => $this->generateTransactionNote($type, $method, $status),
                    'metadata' => [
                        'policy_type' => $policy->policy_type,
                        'customer_identity' => $policy->customer_identity_number,
                        'created_at' => now()->toISOString()
                    ]
                ]);
            }
        }

        $this->command->info('Ödeme işlemleri oluşturuldu: ' . $policies->count() * 2);
    }

    private function generateTransactionNote($type, $method, $status)
    {
        $notes = [
            'ödeme' => [
                'başarılı' => 'Poliçe primi başarıyla ödendi',
                'bekliyor' => 'Ödeme işlemi bekliyor',
                'başarısız' => 'Ödeme işlemi başarısız oldu',
                'iptal' => 'Ödeme işlemi iptal edildi'
            ],
            'iade' => [
                'başarılı' => 'İade işlemi başarıyla tamamlandı',
                'bekliyor' => 'İade işlemi bekliyor',
                'başarısız' => 'İade işlemi başarısız oldu',
                'iptal' => 'İade işlemi iptal edildi'
            ],
            'düzeltme' => [
                'başarılı' => 'Düzeltme işlemi başarıyla tamamlandı',
                'bekliyor' => 'Düzeltme işlemi bekliyor',
                'başarısız' => 'Düzeltme işlemi başarısız oldu',
                'iptal' => 'Düzeltme işlemi iptal edildi'
            ]
        ];

        return $notes[$type][$status] ?? 'İşlem notu';
    }
}
