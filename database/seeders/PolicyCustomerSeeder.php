<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Policy;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class PolicyCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mevcut poliçeler müşterilerle eşleştiriliyor...');

        // Mevcut poliçeleri al
        $policies = Policy::whereNull('customer_id')->get();
        $matchedCount = 0;
        $unmatchedCount = 0;

        foreach ($policies as $policy) {
            // TC/Vergi no ile müşteri ara
            $customer = Customer::where('customer_identity_number', $policy->customer_identity_number)->first();

            if ($customer) {
                // Müşteri bulundu, poliçeyi güncelle
                $policy->update(['customer_id' => $customer->id]);
                $matchedCount++;
                
                $this->command->info("✓ Poliçe {$policy->policy_number} -> Müşteri {$customer->customer_title}");
            } else {
                // Müşteri bulunamadı, yeni müşteri oluştur
                $newCustomer = Customer::create([
                    'customer_title' => $policy->customer_title,
                    'customer_identity_number' => $policy->customer_identity_number,
                    'phone' => $policy->customer_phone,
                    'email' => null,
                    'address' => $policy->customer_address,
                    'customer_type' => 'bireysel', // Varsayılan
                    'risk_level' => 'düşük', // Varsayılan
                    'status' => 'aktif',
                    'credit_limit' => 0
                ]);

                $policy->update(['customer_id' => $newCustomer->id]);
                $matchedCount++;
                
                $this->command->info("✓ Yeni müşteri oluşturuldu: {$newCustomer->customer_title} -> Poliçe {$policy->policy_number}");
            }
        }

        $this->command->info("\nEşleştirme tamamlandı!");
        $this->command->info("Eşleştirilen poliçe sayısı: {$matchedCount}");
        $this->command->info("Eşleştirilemeyen poliçe sayısı: {$unmatchedCount}");
    }
}
