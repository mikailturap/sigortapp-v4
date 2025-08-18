<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\Policy;
use App\Models\PaymentSchedule;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\PolicyCostAnalysis;
use App\Models\PolicyType;
use App\Models\InsuranceCompany;
use App\Models\CustomerAccount;

class FullDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn('Mevcut sahte veriler temizleniyor...');

        Schema::disableForeignKeyConstraints();
        DB::table('payments')->truncate();
        DB::table('payment_schedules')->truncate();
        DB::table('payment_transactions')->truncate();
        DB::table('policy_cost_analysis')->truncate();
        if (Schema::hasTable('policy_files')) {
            DB::table('policy_files')->truncate();
        }
        DB::table('policies')->truncate();
        DB::table('customer_accounts')->truncate();
        DB::table('customers')->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Faker::create('tr_TR');

        // Poliçe türleri ve şirketleri
        $policyTypes = PolicyType::active()->ordered()->pluck('name')->all();
        if (empty($policyTypes)) {
            $policyTypes = [
                'Zorunlu Trafik Sigortası', 'Kasko', 'DASK', 'Konut Sigortası', 'Sağlık Sigortası', 'TARSİM', 'İşyeri Sigortası', 'Seyahat Sigortası'
            ];
        }

        $insuranceCompanies = InsuranceCompany::where('is_active', true)->pluck('name')->all();
        if (empty($insuranceCompanies)) {
            $insuranceCompanies = [
                'Axa Sigorta', 'Allianz Sigorta', 'Gulf Sigorta', 'Neova Sigorta', 'Sompo Sigorta', 'HDI Sigorta', 'Mapfre Sigorta', 'Unico Sigorta', 'Ray Sigorta', 'Türkiye Sigorta', 'Anadolu Sigorta', 'Güneş Sigorta', 'Koru Sigorta', 'Quick Sigorta', 'Groupama Sigorta'
            ];
        }

        $this->command->info('Müşteriler oluşturuluyor...');

        // 50 benzersiz müşteri oluştur
        $customers = collect();
        for ($i = 0; $i < 50; $i++) {
            $isCorporate = $faker->boolean(30);
            $title = $isCorporate ? $faker->company : $faker->name;
            $identity = $isCorporate
                ? $faker->unique()->numerify('##########') // 10 haneli vergi no
                : $faker->unique()->numerify('###########'); // 11 haneli TCKN

            $customer = Customer::create([
                'customer_title' => $title,
                'customer_identity_number' => $identity,
                'phone' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'birth_date' => $isCorporate ? null : $faker->date('Y-m-d', '2006-01-01'),
                'address' => $faker->address,
                'customer_type' => $isCorporate ? 'kurumsal' : 'bireysel',
                'notes' => $faker->optional()->sentence(),
            ]);

            $customers->push($customer);
        }

        $this->command->info('Poliçeler ve ilişkili kayıtlar oluşturuluyor...');

        $paymentMethods = ['nakit', 'kredi kartı', 'banka havalesi', 'çek'];
        $statuses = ['ödendi', 'bekliyor', 'gecikmiş'];

        foreach ($customers as $customer) {
            // Poliçe temel bilgiler
            $policyType = $faker->randomElement($policyTypes);

            $issueDate = Carbon::now()->subDays(rand(0, 365));
            $startDate = (clone $issueDate)->addDays(rand(0, 30));
            $endDate = (clone $startDate)->addYear();

            $paymentStatus = $faker->randomElement($statuses);

            $policyPremium = (float) $faker->numberBetween(1500, 50000);
            $commissionRate = (float) $faker->numberBetween(5, 25);
            $commissionAmount = round(($policyPremium * $commissionRate) / 100, 2);
            $netRevenue = round($policyPremium - $commissionAmount, 2);

            $taxRate = 18.00;
            $taxAmount = round(($netRevenue * $taxRate) / 100, 2);
            $totalAmount = round($netRevenue + $taxAmount, 2);

            $policyCost = round($policyPremium * ($faker->numberBetween(50, 80) / 100), 2);
            $brokerageFee = round($policyPremium * ($faker->numberBetween(5, 12) / 100), 2);
            $operationalCost = round($policyPremium * ($faker->numberBetween(2, 8) / 100), 2);
            $profitMargin = round($netRevenue - $policyCost, 2);

            $dueBase = (clone $startDate)->addMonths(rand(0, 11));
            $paymentDueDate = null;
            $paymentDate = null;
            if ($paymentStatus === 'ödendi') {
                $paymentDate = (clone $dueBase)->subDays(rand(0, 15));
                $paymentDueDate = (clone $dueBase);
            } elseif ($paymentStatus === 'bekliyor') {
                $paymentDueDate = (clone $dueBase)->addDays(rand(1, 60));
            } else { // gecikmiş
                $paymentDueDate = (clone $dueBase)->subDays(rand(1, 90));
            }

            // Tüm alanlarda veri olması için sigorta ettiren bilgilerini doldur
            $insuredDifferent = true;

            $policy = Policy::create([
                'customer_id' => $customer->id,
                'customer_title' => $customer->customer_title,
                'customer_identity_number' => $customer->customer_identity_number,
                'customer_phone' => $customer->phone ?? $faker->phoneNumber,
                'customer_birth_date' => $customer->birth_date ?? $faker->date('Y-m-d', '2006-01-01'),
                'customer_address' => $customer->address ?? $faker->address,

                'insured_name' => $insuredDifferent ? ($customer->customer_type === 'kurumsal' ? $faker->company : $faker->name) : $customer->customer_title,
                'insured_phone' => $insuredDifferent ? $faker->phoneNumber : ($customer->phone ?? $faker->phoneNumber),

                'policy_type' => $policyType,
                'policy_company' => $faker->randomElement($insuranceCompanies),
                'policy_number' => $faker->unique()->bothify('POL-########'),
                'plate_or_other' => $faker->bothify('##???##'),
                'issue_date' => $issueDate->format('Y-m-d'),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'document_info' => $faker->bothify('DOC-####'),

                'tarsim_business_number' => ($policyType === 'TARSİM') ? $faker->numerify('##########') : null,
                'tarsim_animal_number' => ($policyType === 'TARSİM') ? $faker->numerify('##########') : null,

                'status' => 'aktif',

                // Gelir Yönetimi
                'policy_premium' => $policyPremium,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'net_revenue' => $netRevenue,
                'payment_status' => $paymentStatus,
                'payment_due_date' => $paymentDueDate?->format('Y-m-d'),
                'payment_date' => $paymentDate?->format('Y-m-d'),
                'payment_notes' => $faker->sentence(),
                'payment_method' => $faker->randomElement($paymentMethods),
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,

                // Maliyet Analizi Özet
                'policy_cost' => $policyCost,
                'brokerage_fee' => $brokerageFee,
                'operational_cost' => $operationalCost,
                'profit_margin' => $profitMargin,

                // Cari
                'customer_balance' => $paymentStatus === 'ödendi' ? 0 : $totalAmount,
                'customer_payment_terms' => $faker->randomElement(['peşin', '30 gün', '60 gün', '90 gün']),
                'customer_credit_limit' => $faker->numberBetween(0, 100000),
                'last_payment_reference' => $faker->bothify('REF-########'),
                'last_payment_reminder' => $faker->dateTimeBetween('-60 days', 'now'),
            ]);

            // Taksit planı ve ödemeler
            $installmentCount = $faker->numberBetween(1, 4);
            $installmentAmount = round($totalAmount / $installmentCount, 2);
            $paidInstallments = 0;

            for ($k = 1; $k <= $installmentCount; $k++) {
                $due = (clone $startDate)->addMonths($k - 1);
                $scheduleStatus = 'bekliyor';

                if ($paymentStatus === 'ödendi') {
                    $scheduleStatus = 'ödendi';
                    $paidInstallments++;
                } elseif ($paymentStatus === 'gecikmiş' && $due < now()) {
                    $scheduleStatus = 'gecikti';
                }

                $schedule = PaymentSchedule::create([
                    'customer_id' => $customer->id,
                    'policy_id' => $policy->id,
                    'description' => 'Taksit ' . $k,
                    'amount' => $installmentAmount,
                    'due_date' => $due->format('Y-m-d'),
                    'status' => $scheduleStatus,
                    'payment_type' => 'taksit',
                    'installment_number' => $k,
                    'notes' => null,
                ]);

                if ($scheduleStatus === 'ödendi') {
                    Payment::create([
                        'customer_id' => $customer->id,
                        'policy_id' => $policy->id,
                        'payment_schedule_id' => $schedule->id,
                        'amount' => $installmentAmount,
                        'payment_method' => $faker->randomElement($paymentMethods),
                        'payment_status' => 'tamamlandı',
                        'payment_date' => $due->copy()->subDays(rand(0, 10))->format('Y-m-d'),
                        'reference_number' => 'REF-' . strtoupper(uniqid()),
                        'notes' => 'Taksit ödemesi',
                    ]);
                }
            }

            // Ödeme işlemi
            PaymentTransaction::create([
                'policy_id' => $policy->id,
                'user_id' => 1, // varsayılan yönetici
                'transaction_type' => $paymentStatus === 'ödendi' ? 'ödeme' : 'ödeme',
                'amount' => $totalAmount,
                'currency' => 'TRY',
                'payment_method' => $faker->randomElement($paymentMethods),
                'payment_status' => $paymentStatus === 'ödendi' ? 'başarılı' : ($paymentStatus === 'bekliyor' ? 'bekliyor' : 'başarısız'),
                'reference_number' => 'TRX-' . strtoupper(uniqid()),
                'invoice_number' => $policy->invoice_number,
                'invoice_date' => $issueDate,
                'accounting_code' => '120.01',
                'transaction_date' => $issueDate,
                'processed_at' => $paymentStatus === 'ödendi' ? $issueDate : null,
                'notes' => 'Otomatik oluşturulan işlem',
                'metadata' => [
                    'policy_type' => $policyType,
                    'customer_identity' => $customer->customer_identity_number,
                ],
            ]);

            // Maliyet analizi
            PolicyCostAnalysis::create([
                'policy_id' => $policy->id,
                'insurance_company_cost' => round($policyCost * 0.8, 2),
                'brokerage_cost' => $brokerageFee,
                'operational_cost' => $operationalCost,
                'marketing_cost' => round($policyCost * 0.05, 2),
                'administrative_cost' => round($policyCost * 0.03, 2),
                'gross_revenue' => $policyPremium,
                'net_revenue' => $netRevenue,
                'profit_margin' => $profitMargin,
                'profit_margin_percentage' => $policyPremium > 0 ? round(($profitMargin / $policyPremium) * 100, 2) : 0,
                'risk_reserve' => round($policyPremium * 0.1, 2),
                'commission_reserve' => round($brokerageFee * 0.2, 2),
                'tax_reserve' => round($netRevenue * 0.2, 2),
                'analysis_date' => Carbon::now()->subDays(rand(0, 365)),
                'analysis_period' => $faker->randomElement(['aylık', 'yıllık', 'poliçe bazlı']),
                'notes' => 'Otomatik maliyet analizi',
            ]);

            // Cari hesap güncelle
            CustomerAccount::updateOrCreate(
                ['customer_identity_number' => $customer->customer_identity_number],
                [
                    'customer_title' => $customer->customer_title,
                    'current_balance' => $paymentStatus === 'ödendi' ? 0 : $totalAmount,
                    'credit_limit' => 10000,
                    'payment_terms_days' => 30,
                    'risk_level' => $paymentStatus === 'gecikmiş' ? 'orta' : 'düşük',
                    'days_overdue' => $paymentStatus === 'gecikmiş' ? rand(1, 90) : 0,
                    'last_payment_date' => $paymentDate,
                    'last_activity_date' => now(),
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'address' => $customer->address,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Tam dolu 50 poliçe ve ilişkili veriler oluşturuldu.');
    }
}


