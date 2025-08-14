<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Policy;
use App\Models\PaymentSchedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'customer_title');
        $sortOrder = $request->get('order', 'asc');
        
        // Sıralama alanlarını kontrol et
        $allowedSortFields = ['customer_title', 'customer_identity_number', 'phone', 'email', 'customer_type', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'customer_title';
        }
        
        // Sıralama yönünü kontrol et
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        
        // Basit test query
        $customers = Customer::query()
            ->withCount(['policies', 'paymentSchedules as pending_payments' => function($query) {
                $query->where('status', 'bekliyor');
            }])
            ->withSum(['paymentSchedules as total_scheduled' => function($query) {
                $query->where('status', 'bekliyor');
            }], 'amount')
            ->withSum(['payments as total_paid' => function($query) {
                $query->where('payment_status', 'tamamlandı');
            }], 'amount')
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15);

        // Debug bilgisi
        Log::info('Customer pagination info', [
            'total' => $customers->total(),
            'current_page' => $customers->currentPage(),
            'per_page' => $customers->perPage(),
            'has_pages' => $customers->hasPages(),
            'last_page' => $customers->lastPage()
        ]);

        // Test için basit bir dd() ekleyelim
        if (request()->has('debug')) {
            dd([
                'total' => $customers->total(),
                'current_page' => $customers->currentPage(),
                'per_page' => $customers->perPage(),
                'has_pages' => $customers->hasPages(),
                'last_page' => $customers->lastPage(),
                'customers_count' => $customers->count()
            ]);
        }

        // Basit pagination test
        if (request()->has('test')) {
            $simpleCustomers = Customer::paginate(5);
            return view('customers.index', compact('simpleCustomers'));
        }

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'policies' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'paymentSchedules' => function($query) {
                $query->orderBy('due_date');
            },
            'payments' => function($query) {
                $query->orderBy('payment_date', 'desc');
            }
        ]);

        // Özet istatistikler
        $stats = [
            'total_policies' => $customer->policies->count(),
            'active_policies' => $customer->policies->where('status', 'aktif')->count(),
            'pending_amount' => $customer->paymentSchedules->where('status', 'bekliyor')->sum('amount'),
            'overdue_amount' => $customer->paymentSchedules->where('status', 'gecikti')->sum('amount'),
            'total_paid' => $customer->payments->where('payment_status', 'tamamlandı')->sum('amount'),
            'current_balance' => $customer->current_balance
        ];

        return view('customers.show', compact('customer', 'stats'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_title' => 'required|string|max:255',
            'customer_identity_number' => 'required|string|max:20|unique:customers',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'customer_type' => 'required|in:bireysel,kurumsal',
            'notes' => 'nullable|string'
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Müşteri başarıyla oluşturuldu.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_title' => 'required|string|max:255',
            'customer_identity_number' => 'required|string|max:20|unique:customers,customer_identity_number,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'customer_type' => 'required|in:bireysel,kurumsal',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Müşteri bilgileri güncellendi.');
    }

    public function destroy(Customer $customer)
    {
        // Müşterinin aktif poliçesi varsa silmeye izin verme
        if ($customer->policies()->where('status', 'aktif')->exists()) {
            return back()->with('error', 'Aktif poliçesi olan müşteri silinemez.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri silindi.');
    }

    // Ödeme planı ekleme
    public function addPaymentSchedule(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'policy_id' => 'nullable|exists:policies,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after:today',
            'payment_type' => 'required|in:taksit,peşin,ek ödeme',
            'installment_number' => 'nullable|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['status'] = 'bekliyor';

        $paymentSchedule = PaymentSchedule::create($validated);

        // Eğer poliçeye bağlıysa, poliçe ödeme durumunu güncelle
        if ($validated['policy_id']) {
            $policy = Policy::find($validated['policy_id']);
            if ($policy) {
                // Poliçe ödeme durumunu kontrol et
                $totalScheduled = $policy->paymentSchedules()->sum('amount');
                $totalPaid = $policy->payments()->where('payment_status', 'tamamlandı')->sum('amount');
                
                if ($totalPaid >= $totalScheduled) {
                    $policy->update(['payment_status' => 'ödendi']);
                } else {
                    $policy->update(['payment_status' => 'bekliyor']);
                }
            }
        }

        return back()->with('success', 'Ödeme planı eklendi.');
    }

    // Ödeme kaydetme
    public function addPayment(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'policy_id' => 'nullable|exists:policies,id',
            'payment_schedule_id' => 'nullable|exists:payment_schedules,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:nakit,kredi kartı,banka havalesi,çek',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['payment_status'] = 'tamamlandı';

        $payment = Payment::create($validated);

        // Eğer ödeme planına bağlıysa, durumunu güncelle
        if ($validated['payment_schedule_id']) {
            $schedule = PaymentSchedule::find($validated['payment_schedule_id']);
            $schedule->update(['status' => 'ödendi']);
        }

        // Eğer poliçeye bağlıysa, poliçe ödeme durumunu güncelle
        if ($validated['policy_id']) {
            $policy = Policy::find($validated['policy_id']);
            if ($policy) {
                // Poliçe ödeme durumunu kontrol et
                $totalScheduled = $policy->paymentSchedules()->sum('amount');
                $totalPaid = $policy->payments()->where('payment_status', 'tamamlandı')->sum('amount');
                
                if ($totalPaid >= $totalScheduled) {
                    $policy->update(['payment_status' => 'ödendi']);
                } else {
                    $policy->update(['payment_status' => 'bekliyor']);
                }
            }
        }

        return back()->with('success', 'Ödeme başarıyla kaydedildi.');
    }

    // Müşteri arama
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) {
            return response()->json(['customers' => []]);
        }

        $customers = Customer::where(function($q) use ($query) {
            $q->where('customer_title', 'LIKE', "%{$query}%")
              ->orWhere('customer_identity_number', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('address', 'LIKE', "%{$query}%");
        })
        ->withCount(['policies', 'paymentSchedules as pending_payments' => function($q) {
            $q->where('status', 'bekliyor');
        }])
        ->withSum(['paymentSchedules as total_scheduled' => function($q) {
            $q->where('status', 'bekliyor');
        }], 'amount')
        ->withSum(['payments as total_paid' => function($q) {
            $q->where('payment_status', 'tamamlandı');
        }], 'amount')
        ->limit(10)
        ->get();

        return response()->json(['customers' => $customers]);
    }

    // TC/Vergi no ile müşteri bul veya oluştur
    public function findOrCreate(Request $request)
    {
        $request->validate([
            'customer_identity_number' => 'required|string|max:20',
            'customer_title' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'customer_type' => 'required|in:bireysel,kurumsal'
        ]);

        // Önce mevcut müşteriyi ara
        $customer = Customer::where('customer_identity_number', $request->customer_identity_number)->first();

        if ($customer) {
            // Müşteri varsa bilgilerini güncelle (eğer yeni bilgiler verilmişse)
            $updated = false;
            
            if ($request->customer_title && $customer->customer_title !== $request->customer_title) {
                $customer->customer_title = $request->customer_title;
                $updated = true;
            }
            
            if ($request->phone && $customer->phone !== $request->phone) {
                $customer->phone = $request->phone;
                $updated = true;
            }
            
            if ($request->email && $customer->email !== $request->email) {
                $customer->email = $request->email;
                $updated = true;
            }
            
            if ($request->address && $customer->address !== $request->address) {
                $customer->address = $request->address;
                $updated = true;
            }
            
            if ($updated) {
                $customer->save();
            }

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'message' => 'Mevcut müşteri bulundu' . ($updated ? ' ve güncellendi' : ''),
                'action' => 'found'
            ]);
        }

        // Müşteri yoksa yeni oluştur
        $customer = Customer::create([
            'customer_title' => $request->customer_title,
            'customer_identity_number' => $request->customer_identity_number,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'customer_type' => $request->customer_type,
            'risk_level' => 'düşük', // Varsayılan risk seviyesi
            'status' => 'aktif',
            'credit_limit' => 0
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Yeni müşteri oluşturuldu',
            'action' => 'created'
        ]);
    }

    // TC/Vergi no ile müşteri kontrolü
    public function checkByIdentity(Request $request)
    {
        $request->validate([
            'customer_identity_number' => 'required|string|max:20'
        ]);

        $customer = Customer::where('customer_identity_number', $request->customer_identity_number)->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'exists' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'exists' => false,
            'message' => 'Müşteri bulunamadı'
        ]);
    }

    // Ödeme planını ödendi olarak işaretle
    public function markPaymentScheduleAsPaid(Request $request, Customer $customer, PaymentSchedule $schedule)
    {
        // Ödeme planının bu müşteriye ait olduğunu kontrol et
        if ($schedule->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ödeme planı bu müşteriye ait değil.'
            ], 403);
        }

        // Ödeme planını ödendi olarak işaretle
        $schedule->update(['status' => 'ödendi']);

        // Eğer poliçeye bağlıysa, poliçe ödeme durumunu güncelle
        if ($schedule->policy_id) {
            $policy = Policy::find($schedule->policy_id);
            if ($policy) {
                $totalScheduled = $policy->paymentSchedules()->sum('amount');
                $totalPaid = $policy->payments()->where('payment_status', 'tamamlandı')->sum('amount');
                
                if ($totalPaid >= $totalScheduled) {
                    $policy->update(['payment_status' => 'ödendi']);
                } else {
                    $policy->update(['payment_status' => 'bekliyor']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ödeme planı ödendi olarak işaretlendi.'
        ]);
    }

    // Ödeme planını sil
    public function deletePaymentSchedule(Request $request, Customer $customer, PaymentSchedule $schedule)
    {
        // Ödeme planının bu müşteriye ait olduğunu kontrol et
        if ($schedule->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ödeme planı bu müşteriye ait değil.'
            ], 403);
        }

        // Ödeme planını sil
        $schedule->delete();

        // Eğer poliçeye bağlıysa, poliçe ödeme durumunu güncelle
        if ($schedule->policy_id) {
            $policy = Policy::find($schedule->policy_id);
            if ($policy) {
                $totalScheduled = $policy->paymentSchedules()->sum('amount');
                $totalPaid = $policy->payments()->where('payment_status', 'tamamlandı')->sum('amount');
                
                if ($totalPaid >= $totalScheduled) {
                    $policy->update(['payment_status' => 'ödendi']);
                } else {
                    $policy->update(['payment_status' => 'bekliyor']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ödeme planı silindi.'
        ]);
    }
}
