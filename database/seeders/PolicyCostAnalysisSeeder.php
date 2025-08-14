<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Policy;
use App\Models\PolicyCostAnalysis;
use Carbon\Carbon;

class PolicyCostAnalysisSeeder extends Seeder
{
    public function run(): void
    {
        // Örnek maliyet analizi verileri oluştur
        $policies = Policy::whereNotNull('policy_premium')->take(100)->get();
        
        foreach ($policies as $policy) {
            // Maliyet hesaplamaları
            $grossRevenue = $policy->policy_premium ?? rand(500, 5000);
            $insuranceCompanyCost = $grossRevenue * (rand(60, 80) / 100); // %60-80
            $brokerageCost = $grossRevenue * (rand(5, 15) / 100); // %5-15
            $operationalCost = $grossRevenue * (rand(2, 8) / 100); // %2-8
            $marketingCost = $grossRevenue * (rand(1, 5) / 100); // %1-5
            $administrativeCost = $grossRevenue * (rand(1, 3) / 100); // %1-3
            
            $totalCost = $insuranceCompanyCost + $brokerageCost + $operationalCost + $marketingCost + $administrativeCost;
            $netRevenue = $grossRevenue - $totalCost;
            $profitMargin = $netRevenue - $totalCost;
            $profitMarginPercentage = $grossRevenue > 0 ? ($profitMargin / $grossRevenue) * 100 : 0;
            
            // Rezervler
            $riskReserve = $grossRevenue * (rand(5, 15) / 100);
            $commissionReserve = $brokerageCost * (rand(10, 30) / 100);
            $taxReserve = $netRevenue * (rand(15, 25) / 100);
            
            // Analiz periyodu
            $analysisPeriods = ['aylık', 'yıllık', 'poliçe bazlı'];
            $analysisPeriod = $analysisPeriods[array_rand($analysisPeriods)];
            
            // Analiz tarihi: son 12 ay içinde
            $analysisDate = Carbon::now()->subDays(rand(0, 365));
            
            PolicyCostAnalysis::create([
                'policy_id' => $policy->id,
                'insurance_company_cost' => round($insuranceCompanyCost, 2),
                'brokerage_cost' => round($brokerageCost, 2),
                'operational_cost' => round($operationalCost, 2),
                'marketing_cost' => round($marketingCost, 2),
                'administrative_cost' => round($administrativeCost, 2),
                'gross_revenue' => round($grossRevenue, 2),
                'net_revenue' => round($netRevenue, 2),
                'profit_margin' => round($profitMargin, 2),
                'profit_margin_percentage' => round($profitMarginPercentage, 2),
                'risk_reserve' => round($riskReserve, 2),
                'commission_reserve' => round($commissionReserve, 2),
                'tax_reserve' => round($taxReserve, 2),
                'analysis_date' => $analysisDate,
                'analysis_period' => $analysisPeriod,
                'notes' => $this->generateAnalysisNote($policy, $profitMarginPercentage)
            ]);
        }

        $this->command->info('Maliyet analizi verileri oluşturuldu: ' . $policies->count());
    }

    private function generateAnalysisNote($policy, $profitMarginPercentage)
    {
        $notes = [];
        
        if ($profitMarginPercentage > 20) {
            $notes[] = 'Yüksek karlılık - %' . round($profitMarginPercentage, 1);
        } elseif ($profitMarginPercentage > 10) {
            $notes[] = 'İyi karlılık - %' . round($profitMarginPercentage, 1);
        } elseif ($profitMarginPercentage > 0) {
            $notes[] = 'Düşük karlılık - %' . round($profitMarginPercentage, 1);
        } else {
            $notes[] = 'Zarar - %' . round(abs($profitMarginPercentage), 1);
        }
        
        $notes[] = 'Poliçe türü: ' . $policy->policy_type;
        $notes[] = 'Müşteri: ' . $policy->customer_title;
        
        return implode(' | ', $notes);
    }
}
