<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Customer;
use App\Models\PaymentSchedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index()
    {
        // Müşteri cari hesap özeti - Artık risk seviyesi kullanılmıyor
        $totalCustomerBalance = 0;
        $overdueCustomers = 0;
        $highRiskCustomers = 0;
        $totalCustomers = Customer::count();

        // Ödeme planları özeti - Basit hesaplamalar
        $totalScheduledAmount = PaymentSchedule::where('status', 'bekliyor')->sum('amount');
        $overdueAmount = 0;
        $dueThisWeek = PaymentSchedule::where('status', 'bekliyor')->sum('amount');
        $dueThisMonth = PaymentSchedule::where('status', 'bekliyor')->sum('amount');

        // Ödeme işlemleri özeti
        $totalPayments = Payment::where('payment_status', 'tamamlandı')->count();
        $thisMonthPayments = Payment::where('payment_status', 'tamamlandı')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $thisYearPayments = Payment::where('payment_status', 'tamamlandı')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Aylık ödeme grafiği için veri
        $monthlyPayments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('payment_status', 'tamamlandı')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Risk seviyesine göre müşteri dağılımı - Artık kullanılmıyor ama Chart.js için gerekli
        $customerRiskDistribution = collect([
            ['risk_level' => 'Düşük', 'count' => 0],
            ['risk_level' => 'Orta', 'count' => 0],
            ['risk_level' => 'Yüksek', 'count' => 0],
            ['risk_level' => 'Kritik', 'count' => 0]
        ]);

        // Gecikmiş ödemeler - Basit sorgu
        $overdueSchedules = PaymentSchedule::where('status', 'bekliyor')
            ->with(['customer', 'policy'])
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        // Bu hafta vadesi gelen ödemeler - Basit sorgu
        $dueThisWeek = PaymentSchedule::where('status', 'bekliyor')
            ->with(['customer', 'policy'])
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        // En yüksek bakiyeli müşteriler - Basit sorgu
        $topCustomers = Customer::with(['paymentSchedules' => function($query) {
                $query->where('status', 'bekliyor');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Toplam gelir hesaplama
        $totalRevenue = Payment::where('payment_status', 'tamamlandı')->sum('amount');
        
        // Bekleyen gelir hesaplama
        $pendingRevenue = PaymentSchedule::where('status', 'bekliyor')->sum('amount');
        
        // Gecikmiş gelir hesaplama (basit hesaplama)
        $overdueRevenue = 0;
        
        // Bu ay gelir hesaplama
        $thisMonthRevenue = Payment::where('payment_status', 'tamamlandı')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        
        // Başarılı işlem sayısı
        $successfulTransactions = Payment::where('payment_status', 'tamamlandı')->count();
        
        // Gecikmiş poliçeler
        $overduePolicies = Policy::where('payment_status', 'bekliyor')
            ->with(['customer'])
            ->orderBy('payment_due_date', 'asc')
            ->get();
            
        // Bu ay sona eren poliçeler
        $expiringThisMonth = Policy::whereMonth('end_date', Carbon::now()->month)
            ->whereYear('end_date', Carbon::now()->year)
            ->with(['customer'])
            ->orderBy('end_date', 'asc')
            ->get();
            
        // Ödeme durumu sayıları
        $paymentStatusCounts = collect([
            ['payment_status' => 'ödendi', 'count' => Payment::where('payment_status', 'tamamlandı')->count()],
            ['payment_status' => 'bekliyor', 'count' => PaymentSchedule::where('status', 'bekliyor')->count()],
            ['payment_status' => 'gecikmiş', 'count' => 0]
        ]);
        
        // Aylık gelir verisi (Chart.js için)
        $monthlyRevenue = $monthlyPayments->map(function($item) {
            return [
                'month' => $item->month,
                'revenue' => $item->total
            ];
        });
        
        // Ödeme yöntemi analizi (Chart.js için)
        $paymentMethodAnalysis = collect([
            ['method' => 'Nakit', 'count' => Payment::where('payment_method', 'nakit')->count()],
            ['method' => 'Banka', 'count' => Payment::where('payment_method', 'banka')->count()],
            ['method' => 'Kredi Kartı', 'count' => Payment::where('payment_method', 'kredi_karti')->count()],
            ['method' => 'Çek', 'count' => Payment::where('payment_method', 'cek')->count()]
        ]);

        return view('revenue.index', compact(
            'totalCustomerBalance', 'overdueCustomers', 'highRiskCustomers', 'totalCustomers',
            'totalScheduledAmount', 'overdueAmount', 'dueThisWeek', 'dueThisMonth',
            'totalPayments', 'thisMonthPayments', 'thisYearPayments',
            'monthlyPayments', 'customerRiskDistribution',
            'overdueSchedules', 'dueThisWeek', 'topCustomers', 'totalRevenue',
            'pendingRevenue', 'overdueRevenue', 'thisMonthRevenue', 'successfulTransactions',
            'overduePolicies', 'expiringThisMonth', 'paymentStatusCounts', 'monthlyRevenue',
            'paymentMethodAnalysis'
        ));
    }

    public function policies()
    {
        $policies = Policy::with(['customerAccount'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('revenue.policies', compact('policies'));
    }

    public function show(Policy $policy)
    {
        $policy->load(['customerAccount', 'paymentTransactions', 'costAnalysis']);
        
        // Ödeme geçmişi
        $paymentHistory = $policy->paymentTransactions()
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Maliyet analizi
        $costAnalysis = $policy->costAnalysis()
            ->orderBy('analysis_date', 'desc')
            ->first();

        return view('revenue.show', compact('policy', 'paymentHistory', 'costAnalysis'));
    }

    public function edit(Policy $policy)
    {
        $policy->load(['customerAccount']);
        return view('revenue.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'policy_premium' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'payment_status' => 'required|in:bekliyor,ödendi,gecikmiş',
            'payment_due_date' => 'nullable|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'payment_notes' => 'nullable|string',
            'policy_cost' => 'nullable|numeric|min:0',
            'brokerage_fee' => 'nullable|numeric|min:0',
            'operational_cost' => 'nullable|numeric|min:0',
            'customer_balance' => 'nullable|numeric',
            'customer_payment_terms' => 'nullable|string',
            'customer_credit_limit' => 'nullable|integer|min:0',
        ]);

        $policy->update($validated);

        // Otomatik hesaplamalar
        if ($policy->policy_premium && $policy->commission_rate) {
            $policy->calculateCommission();
            $policy->calculateNetRevenue();
            $policy->calculateTax();
            $policy->calculateTotalAmount();
        }

        if ($policy->net_revenue && $policy->policy_cost) {
            $policy->calculateProfitMargin();
        }

        $policy->updateCustomerBalance();

        // Müşteri cari hesap güncelleme
        $this->updateCustomerAccount($policy);

        return redirect()->route('revenue.show', $policy)
            ->with('success', 'Poliçe gelir bilgileri güncellendi.');
    }

    public function reports()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        // Günlük gelir raporu
        $dailyRevenue = Policy::selectRaw('DATE(payment_date) as date, SUM(net_revenue) as revenue, COUNT(*) as policy_count')
            ->where('payment_status', 'ödendi')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Müşteri bazında gelir
        $customerRevenue = Policy::selectRaw('customer_title, SUM(net_revenue) as total_revenue, COUNT(*) as policy_count')
            ->where('payment_status', 'ödendi')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('customer_title')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Poliçe türü bazında gelir
        $policyTypeRevenue = Policy::selectRaw('policy_type, SUM(net_revenue) as total_revenue, COUNT(*) as policy_count')
            ->where('payment_status', 'ödendi')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('policy_type')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Risk analizi - Artık risk seviyesi kullanılmıyor
        $riskAnalysis = collect([]);

        // Ödeme yöntemi analizi - Artık ödeme işlemleri kullanılmıyor
        $paymentMethodAnalysis = collect([]);

        // View'da kullanılan değişken isimleri
        $revenueReport = $dailyRevenue;
        $companyRevenue = $customerRevenue;
        $typeRevenue = $policyTypeRevenue;

        return view('revenue.reports', compact(
            'startDate', 'endDate', 'dailyRevenue', 'customerRevenue', 'policyTypeRevenue',
            'riskAnalysis', 'paymentMethodAnalysis', 'revenueReport', 'companyRevenue', 'typeRevenue'
        ));
    }

    public function export(Request $request)
    {
        // Excel export işlemi burada yapılacak
        return response()->json(['message' => 'Export özelliği yakında eklenecek']);
    }

    // Yeni metodlar
    public function customerAccounts()
    {
        $customers = Customer::with(['policies'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('revenue.customer-accounts', compact('customers'));
    }

    public function paymentTransactions()
    {
        $transactions = Payment::with(['policy', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('revenue.payment-transactions', compact('transactions'));
    }

    public function costAnalysis()
    {
        // Basit maliyet analizi - gerçek veri yapısına göre güncellenecek
        $costAnalysis = collect([]);

        return view('revenue.cost-analysis', compact('costAnalysis'));
    }

    private function updateCustomerAccount(Policy $policy)
    {
        // Bu metod artık kullanılmıyor - yeni müşteri sistemi ile değiştirildi
        return;
    }
}
