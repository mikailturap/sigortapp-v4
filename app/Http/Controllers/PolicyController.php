<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;
use App\Models\Policy;
use App\Models\PolicyFile;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Mail\PolicyReminderMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PoliciesTrackingExport;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PolicyController extends Controller
{
    /**
     * Kaynakların listesini görüntüle.
     */
    public function index(Request $request)
    {
        $policies = Policy::query();

        // Filtreleme mantığı
        if ($request->filled('customer_title')) {
            $policies->where('customer_title', 'like', '%' . $request->customer_title . '%');
        }

        if ($request->filled('policy_number')) {
            $policies->where('policy_number', 'like', '%' . $request->policy_number . '%');
        }

        if ($request->filled('plate_or_other')) {
            $policies->where('plate_or_other', 'like', '%' . $request->plate_or_other . '%');
        }

        // Müşteriye göre filtreleme
        if ($request->filled('customer_identity_number')) {
            $policies->where('customer_identity_number', $request->customer_identity_number);
        }
        if ($request->filled('customer_id')) {
            $policies->where('customer_id', $request->customer_id);
        }

        if ($request->filled('policy_type')) {
            $policies->where('policy_type', $request->policy_type);
        }

        if ($request->filled('policy_company')) {
            $policies->where('policy_company', 'like', '%' . $request->policy_company . '%');
        }

        if ($request->filled('status')) {
            $policies->where('status', $request->status);
        }

        if ($request->filled('start_date_from')) {
            $policies->where('start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $policies->where('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('end_date_from')) {
            $policies->where('end_date', '>=', $request->end_date_from);
        }

        if ($request->filled('end_date_to')) {
            $policies->where('end_date', '<=', $request->end_date_to);
        }

        // Sayfa başına kayıt seçimi
        $perPage = (int) $request->get('per_page', 25);
        $allowedPerPageOptions = [25, 50, 100, 200];
        if (!in_array($perPage, $allowedPerPageOptions)) {
            $perPage = 25;
        }

        // Sıralama - varsayılan en son güncellenenler üstte
        $sortBy = $request->get('sort', 'updated_at');
        $sortOrder = $request->get('order', 'desc');
        
        // Sıralama alanlarını kontrol et
        $allowedSortFields = [
            'id',
            'customer_title',
            'policy_number',
            'policy_type',
            'policy_company',
            'plate_or_other',
            'start_date',
            'end_date',
            'status',
            'customer_identity_number',
            'customer_phone',
            'issue_date',
            'updated_at',
            'created_at'
        ];
        
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'updated_at';
        }
        
        // Sıralama yönünü kontrol et
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';

        // Toplam poliçe sayısını hesapla (filtrelenmemiş)
        $totalPoliciesCount = Policy::count();

        // Laravel sayfalama kullan
        $policies = $policies->orderBy($sortBy, $sortOrder)->paginate($perPage)->withQueryString();

        // Bildirim mantığı
        $expirationThresholdDays = (int) Setting::get('notifications_expiration_threshold_days', config('notifications.expiration_threshold_days'));
        $today = now()->startOfDay();

        $policies->each(function ($policy) use ($expirationThresholdDays, $today) {
            $endDate = $policy->end_date ? $policy->end_date->copy()->startOfDay() : null;
            $daysUntilExpiration = $endDate ? (int) floor(($endDate->getTimestamp() - $today->getTimestamp()) / 86400) : null;

            // Poliçe aktifse, bitiş tarihi yaklaşmışsa ve bildirim kapatılmamışsa
            if ($policy->status == 'aktif' && $daysUntilExpiration !== null && $daysUntilExpiration <= $expirationThresholdDays && $daysUntilExpiration >= 0 && !$policy->notification_dismissed_at) {
                // Bildirim gösterilecekse notified_at'i güncelle
                if (!$policy->notified_at) {
                    $policy->update(['notified_at' => now()]);
                }
                $policy->setAttribute('show_notification', true);
                $policy->setAttribute('days_until_expiration', $daysUntilExpiration);
            } else {
                $policy->setAttribute('show_notification', false);
            }
        });

        return view('policies.index', compact('policies', 'perPage', 'allowedPerPageOptions', 'totalPoliciesCount'));
    }

    /**
     * Dashboard'u özet bilgi ile görüntüle.
     */
    public function dashboard()
    {
        $totalPolicies = Policy::count();
        $activePolicies = Policy::where('status', 'aktif')->count();
        $passivePolicies = Policy::where('status', 'pasif')->count();
        $expiringSoonPolicies = Policy::where('end_date', '>=', now())
                                    ->where('end_date', '<=', now()->addDays(config('notifications.expiration_threshold_days')))
                                    ->where('status', 'aktif')
                                    ->count();

        // Poliçe Türü Dağılımı
        $policyTypeDistribution = Policy::select('policy_type', DB::raw('count(*) as total'))
                                        ->groupBy('policy_type')
                                        ->pluck('total', 'policy_type')->toArray();

        // Son 12 Ayda Oluşturulan Poliçeler
        $policiesLast12Months = Policy::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
                                        ->where('created_at', '>=', now()->subMonths(12))
                                        ->groupBy('month')
                                        ->orderBy('month')
                                        ->pluck('total', 'month')->toArray();

        // Toplam Müşteri (Benzersiz müşteri ünvanı veya TC/Vergi No)
        $totalCustomers = Policy::distinct('customer_identity_number')->count('customer_identity_number');

        // Son 30 Günde Oluşturulan Poliçeler
        $newPoliciesLast30Days = Policy::where('created_at', '>=', now()->subDays(30))->count();

        // Sigorta Şirketleri Dağılımı
        $insuranceCompaniesDistribution = Policy::select('policy_company', DB::raw('count(*) as total'))
                                                ->whereNotNull('policy_company')
                                                ->where('policy_company', '!=', '')
                                                ->groupBy('policy_company')
                                                ->orderBy('total', 'desc')
                                                ->limit(10)
                                                ->pluck('total', 'policy_company')
                                                ->toArray();

        // Gelir Yönetimi Verileri
        $totalRevenue = Policy::where('payment_status', 'ödendi')->sum('net_revenue');
        $pendingRevenue = Policy::where('payment_status', 'bekliyor')->sum('net_revenue');

        return view('dashboard', compact(
            'totalPolicies', 
            'activePolicies', 
            'passivePolicies', 
            'expiringSoonPolicies', 
            'policyTypeDistribution', 
            'policiesLast12Months', 
            'totalCustomers', 
            'newPoliciesLast30Days', 
            'insuranceCompaniesDistribution',
            'totalRevenue',
            'pendingRevenue'
        ));
    }

    /**
     * Yeni kaynağı oluşturmak için formu görüntüle.
     */
    public function create()
    {
        return view('policies.create');
    }

    /**
     * Yeni kaynağı depolama.
     */
    public function store(StorePolicyRequest $request)
    {
        $validatedData = $request->validated();

        // Müşteri kontrolü ve otomatik oluşturma
        $customer = $this->findOrCreateCustomer($validatedData);
        $validatedData['customer_id'] = $customer->id;

        // Sigorta ettiren bilgileri boşsa, müşteri bilgilerini kullan
        if (empty($validatedData['insured_name'])) {
            $validatedData['insured_name'] = $customer->customer_title;
        }
        if (empty($validatedData['insured_phone'])) {
            $validatedData['insured_phone'] = $customer->phone;
        }

        // Varsayılan değerleri ayarla
        $validatedData['payment_status'] = $validatedData['payment_status'] ?? 'bekliyor';
        $validatedData['tax_rate'] = $validatedData['tax_rate'] ?? 18.00;

        $policy = Policy::create($validatedData);

        // Gelir hesaplamalarını yap
        if ($policy->policy_premium && $policy->commission_rate) {
            $policy->calculateCommission();
            $policy->calculateNetRevenue();
            $policy->calculateTax();
            $policy->calculateTotalAmount();
        }

        // Dosya yükleme (toplam 4 dosya limiti)
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $existingCount = $policy->files()->count();
            $newCount = count($files);
            if ($existingCount + $newCount > 4) {
                return back()->withErrors(['files' => 'En fazla 4 dosya yükleyebilirsiniz.'])->withInput();
            }
            foreach ($files as $uploadedFile) {
                $storedPath = $uploadedFile->store('policy-files/' . $policy->id, 'public');
                $policy->files()->create([
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'path' => $storedPath,
                    'disk' => 'public',
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'size' => $uploadedFile->getSize(),
                ]);
            }
        }

        return redirect()->route('policies.index')->with('success', 'Poliçe başarıyla oluşturuldu.');
    }

    /**
     * TC/Vergi no ile müşteri kontrolü
     */
    public function checkCustomerByIdentity(Request $request)
    {
        $request->validate([
            'customer_identity_number' => 'required|string|max:20'
        ]);

        $customer = Customer::where('customer_identity_number', $request->customer_identity_number)->first();
        
        if ($customer) {
            return response()->json([
                'success' => true,
                'exists' => true,
                'customer' => [
                    'customer_title' => $customer->customer_title,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'customer_birth_date' => optional($customer->birth_date)->format('Y-m-d'),
                    'customer_type' => $customer->customer_type,
                ],
                'message' => 'Müşteri bulundu'
            ]);
        }

        return response()->json([
            'success' => true,
            'exists' => false,
            'message' => 'Müşteri bulunamadı'
        ]);
    }

    /**
     * Müşteri bul veya oluştur
     */
    private function findOrCreateCustomer($data)
    {
        // TC/Vergi no ile müşteri ara
        $customer = Customer::where('customer_identity_number', $data['customer_identity_number'])->first();

        if ($customer) {
            // Müşteri varsa bilgilerini güncelle (eğer yeni bilgiler verilmişse)
            $updated = false;
            
            if (isset($data['customer_title']) && $customer->customer_title !== $data['customer_title']) {
                $customer->customer_title = $data['customer_title'];
                $updated = true;
            }
            
            if (isset($data['customer_phone']) && $customer->phone !== $data['customer_phone']) {
                $customer->phone = $data['customer_phone'];
                $updated = true;
            }
            
            if (isset($data['customer_address']) && $customer->address !== $data['customer_address']) {
                $customer->address = $data['customer_address'];
                $updated = true;
            }
            
            if (isset($data['customer_birth_date'])) {
                // Doğum tarihi farklıysa güncelle
                $incomingBirth = $data['customer_birth_date'];
                if (empty($customer->birth_date) || $customer->birth_date->format('Y-m-d') !== $incomingBirth) {
                    $customer->birth_date = $incomingBirth;
                    $updated = true;
                }
            }

            if (isset($data['customer_type']) && in_array($data['customer_type'], ['bireysel','kurumsal'], true) && $customer->customer_type !== $data['customer_type']) {
                $customer->customer_type = $data['customer_type'];
                $updated = true;
            }
            
            if ($updated) {
                $customer->save();
            }

            return $customer;
        }

        // Müşteri yoksa yeni oluştur
        return Customer::create([
            'customer_title' => $data['customer_title'],
            'customer_identity_number' => $data['customer_identity_number'],
            'phone' => $data['customer_phone'] ?? null,
            'email' => null,
            'birth_date' => $data['customer_birth_date'] ?? null,
            'address' => $data['customer_address'] ?? null,
            'customer_type' => $data['customer_type'] ?? 'bireysel',
            'status' => 'aktif'
        ]);
    }

    /**
     * Belirtilen kaynağı görüntüle.
     */
    public function show(Policy $policy)
    {
        $policy->load('files');
        return view('policies.show', compact('policy'));
    }

    /**
     * Belirtilen kaynağı düzenlemek için formu görüntüle.
     */
    public function edit(Policy $policy)
    {
        $policy->load('files');
        return view('policies.edit', compact('policy'));
    }

    /**
     * Belirtilen kaynağı güncelle.
     */
    public function update(UpdatePolicyRequest $request, Policy $policy)
    {
        Log::info("Update method called for policy {$policy->id}");
        $validatedData = $request->validated();

        // Güvence: müşteri alanları istenmeden gelirse yoksay
        unset($validatedData['customer_title'], $validatedData['customer_identity_number'], $validatedData['customer_phone'], $validatedData['customer_birth_date'], $validatedData['customer_address']);

        // Sigorta ettiren bilgileri boşsa, poliçenin müşteri bilgilerini kullan
        if (empty($validatedData['insured_name'])) {
            $validatedData['insured_name'] = $policy->customer_title;
        }
        if (empty($validatedData['insured_phone'])) {
            $validatedData['insured_phone'] = $policy->customer_phone;
        }

        // Eğer bitiş tarihi değiştiyse, bildirim zaman damgalarını sıfırla
        if ($policy->end_date->format('Y-m-d') !== $validatedData['end_date']) {
            $validatedData['notified_at'] = null;
            $validatedData['notification_dismissed_at'] = null;
        }

        $policy->update($validatedData);

        // Yeni dosya yükleme (var olanlara ek) ve toplam 4 sınırı
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $existingCount = $policy->files()->count();
            $newCount = count($files);
            if ($existingCount + $newCount > 4) {
                return back()->withErrors(['files' => 'Toplamda en fazla 4 dosya yükleyebilirsiniz.'])->withInput();
            }
            foreach ($files as $uploadedFile) {
                $storedPath = $uploadedFile->store('policy-files/' . $policy->id, 'public');
                $policy->files()->create([
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'path' => $storedPath,
                    'disk' => 'public',
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'size' => $uploadedFile->getSize(),
                ]);
            }
        }

        return redirect()->route('policies.index')->with('success', 'Poliçe başarıyla güncellendi.');
    }

    /**
     * Belirtilen kaynağı sil.
     */
    public function destroy(Policy $policy)
    {
        Log::info("Policy destroy called - Policy ID: {$policy->id}, Policy Number: {$policy->policy_number}");
        
        // İlişkili dosyaları da sil
        foreach ($policy->files as $file) {
            if ($file->disk && $file->path) {
                Storage::disk($file->disk)->delete($file->path);
            }
            $file->delete();
        }
        $policy->delete();
        
        Log::info("Policy deleted successfully");

        return redirect()->route('policies.index')->with('success', 'Poliçe başarıyla silindi.');
    }

    /**
     * Belirtilen kaynağın durumunu değiştir.
     */
    public function toggleStatus(Request $request, Policy $policy)
    {
        $policy->status = ($policy->status == 'aktif') ? 'pasif' : 'aktif';
        $policy->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Poliçe durumu başarıyla güncellendi.',
                'data' => [
                    'id' => $policy->id,
                    'status' => $policy->status,
                ],
            ]);
        }

        return back()->with('success', 'Poliçe durumu başarıyla güncellendi.');
    }

    /**
     * Kaynakların toplu işlemlerini gerçekleştir.
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete,download_pdf',
            'selected_policies' => 'required|array',
            'selected_policies.*' => 'exists:policies,id',
        ]);

        $action = $request->input('action');
        $policyIds = $request->input('selected_policies');

        switch ($action) {
            case 'activate':
                Policy::whereIn('id', $policyIds)->update(['status' => 'aktif']);
                $message = 'Seçilen poliçeler başarıyla aktif edildi.';
                break;
            case 'deactivate':
                Policy::whereIn('id', $policyIds)->update(['status' => 'pasif']);
                $message = 'Seçilen poliçeler başarıyla pasif edildi.';
                break;
            case 'delete':
                Policy::whereIn('id', $policyIds)->delete();
                $message = 'Seçilen poliçeler başarıyla silindi.';
                break;
            case 'download_pdf':
                $policiesToDownload = Policy::whereIn('id', $policyIds)->get();
                $zipFileName = 'poliçeler_' . now()->format('Ymd_His') . '.zip';
                $zip = new \ZipArchive();

                if ($zip->open(public_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    foreach ($policiesToDownload as $policyItem) {
                        $pdf = Pdf::loadView('policies.pdf', ['policy' => $policyItem]);
                        $pdfFileName = 'poliçe_' . $policyItem->policy_number . '.pdf';
                        $zip->addFromString($pdfFileName, $pdf->output());
                    }
                    $zip->close();
                    return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
                } else {
                    return back()->with('error', 'ZIP dosyası oluşturulamadı.');
                }
            default:
                $message = 'Geçersiz işlem.';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Belirtilen poliçenin PDF'ini indir.
     */
    public function downloadPdf(Policy $policy)
    {
        $pdf = Pdf::loadView('policies.pdf', compact('policy'));
        return $pdf->download('policy_' . $policy->policy_number . '.pdf');
    }

    /**
     * Belirtilen poliçenin bildirimini kapat.
     */
    public function dismissNotification(Policy $policy)
    {
        $policy->update(['notification_dismissed_at' => now()]);

        return back()->with('success', 'Bildirim başarıyla kapatıldı.');
    }

    // Dosya indir
    public function downloadFile(Policy $policy, PolicyFile $file)
    {
        if ($file->policy_id !== $policy->id) {
            abort(404);
        }
        $disk = Storage::disk($file->disk);
        $stream = $disk->readStream($file->path);
        $filename = $file->original_name ?: basename($file->path);
        $mime = $file->mime_type ?: 'application/octet-stream';

        return response()->streamDownload(function() use ($stream) {
            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, $filename, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    // Dosya görüntüle (inline)
    public function previewFile(Policy $policy, PolicyFile $file)
    {
        if ($file->policy_id !== $policy->id) {
            abort(404);
        }
        $mime = $file->mime_type ?: 'application/octet-stream';
        $content = Storage::disk($file->disk)->get($file->path);
        return response($content)->header('Content-Type', $mime);
    }

    // Dosya sil
    public function deleteFile(Request $request, Policy $policy, PolicyFile $file)
    {
        Log::info("Delete file called - Policy ID: {$policy->id}, File ID: {$file->id}, File name: {$file->original_name}");
        
        if ($file->policy_id !== $policy->id) {
            Log::error("File policy mismatch - File policy_id: {$file->policy_id}, Expected: {$policy->id}");
            abort(404);
        }
        
        if ($file->disk && $file->path) {
            Storage::disk($file->disk)->delete($file->path);
            Log::info("File deleted from storage: {$file->path}");
        }
        
        $file->delete();
        Log::info("File record deleted from database");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dosya silindi.'
            ]);
        }
        
        return redirect()->route('policies.edit', $policy)->with('success', 'Dosya silindi.');
    }

    /**
     * Belirtilen poliçenin hatırlatıcı e-postasını gönder.
     */
    public function sendReminderEmail(Policy $policy)
    {
        // Müşterinin e-posta adresi olmadığı için şimdilik sabit bir adres kullanıyorum.
        // Gerçek uygulamada policy->customer_email gibi bir alan olmalı.
        $customerEmail = 'test@example.com'; // Burayı gerçek e-posta adresi ile değiştirin

        if (!empty($customerEmail)) {
            Mail::to($customerEmail)->send(new PolicyReminderMail($policy));
            return back()->with('success', 'Hatırlatıcı e-posta başarıyla gönderildi.');
        } else {
            return back()->with('error', 'Müşterinin e-posta adresi bulunamadı.');
        }
    }

    /**
     * Poliçe takip sayfasını görüntüle.
     */
    public function policyTracking(Request $request)
    {
        $today = now()->startOfDay();
        $allowedWindows = [7, 15, 30, 60];
        $windowDefault = (int) Setting::get('tracking_window_days', 30);
        $window = (int) $request->input('window', $windowDefault);
        if (!in_array($window, $allowedWindows, true)) {
            $window = 30;
        }

        $expiringToday = Policy::whereDate('end_date', $today)
            ->orderBy('end_date', 'asc')
            ->get();

        $upcomingRenewals = Policy::where('end_date', '>', $today)
            ->where('end_date', '<=', $today->copy()->addDays($window))
            ->orderBy('end_date', 'asc')
            ->get();

        $expiredPolicies = Policy::where('end_date', '<', $today)
            ->orderBy('end_date', 'asc')
            ->get();

        // Takip sayfasının son sekmesinde tüm poliçeler gösterilecek
        $activePolicies = Policy::orderBy('end_date', 'asc')->get();

        $totalActivePolicies = Policy::count();

        $whatsappReminderDays = (int) Setting::get('whatsapp_reminder_days', 7);
        $smsTemplate = (string) Setting::get('sms_template', 'Sayın {customerTitle}, {policyNumber} numaralı poliçenizin bitiş tarihi olan {endDate} yaklaşıyor. Yenileme için bize ulaşabilirsiniz.');

        return view('policies.tracking', [
            'expiringToday' => $expiringToday,
            'upcomingRenewals' => $upcomingRenewals,
            'expiredPolicies' => $expiredPolicies,
            'activePolicies' => $activePolicies,
            'totalActivePolicies' => $totalActivePolicies,
            'window' => $window,
            'allowedWindows' => $allowedWindows,
            'whatsappReminderDays' => $whatsappReminderDays,
            'smsTemplate' => $smsTemplate,
        ]);
    }

    /**
     * Takip listelerini CSV'ye (süresi dolmuş, bugün, yaklaşan) dışa aktar.
     */
    public function trackingExport(Request $request)
    {
        $today = now()->startOfDay();
        $type = $request->input('type'); // expired | today | upcoming
        $window = (int) $request->input('window', 30);

        $query = Policy::query();
        switch ($type) {
            case 'today':
                $query->whereDate('end_date', $today)
                    ->orderBy('end_date', 'asc');
                $filename = 'bugun_sona_eren_policeler_' . $today->format('Ymd') . '.csv';
                break;
            case 'upcoming':
                if ($window <= 0) { $window = 30; }
                $query->where('end_date', '>', $today)
                    ->where('end_date', '<=', $today->copy()->addDays($window))
                    ->orderBy('end_date', 'asc');
                $filename = 'yaklasan_yenilemeler_' . $window . 'g_' . $today->format('Ymd') . '.csv';
                break;
            case 'expired':
            default:
                $query->where('end_date', '<', $today)
                    ->orderBy('end_date', 'desc');
                $filename = 'suresi_gecmis_policeler_' . $today->format('Ymd') . '.csv';
                break;
        }

        $policies = $query->get();

        $exportRows = $policies->map(function($p) use ($today) {
            $endDate = $p->end_date ? $p->end_date->copy()->startOfDay() : null;
            $days = $endDate ? (int) floor(($endDate->getTimestamp() - $today->getTimestamp()) / 86400) : null;
            return [
                'ID' => $p->id,
                'Müşteri Ünvan' => $p->customer_title,
                'TC/Vergi No' => $p->customer_identity_number,
                'Telefon' => $p->customer_phone,
                'Doğum Tarihi' => optional($p->customer_birth_date)->format('Y-m-d'),
                'Adres' => $p->customer_address,
                'Sigorta Ettiren Ünvan' => $p->insured_name,
                'Sigorta Ettiren Telefon' => $p->insured_phone,
                'Poliçe Türü' => $p->policy_type,
                'Poliçe Şirketi' => $p->policy_company,
                'Poliçe No' => $p->policy_number,
                'Plaka/Diğer' => $p->plate_or_other,
                'Tanzim Tarihi' => optional($p->issue_date)->format('Y-m-d'),
                'Başlangıç Tarihi' => optional($p->start_date)->format('Y-m-d'),
                'Bitiş Tarihi' => optional($p->end_date)->format('Y-m-d'),
                'Belge Seri/Diğer/UAVT' => $p->document_info,
                'TARSİM İşletme No' => $p->tarsim_business_number,
                'TARSİM Hayvan No' => $p->tarsim_animal_number,
                'Durum' => $p->status,
                'Kalan Gün' => $days,
                'Oluşturulma' => optional($p->created_at)->format('Y-m-d H:i:s'),
                'Güncellenme' => optional($p->updated_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $exportArray = [
            ['Poliçe Takip Dökümü'],
            ['Tarih: ' . now()->format('Y-m-d H:i:s')],
            [],
        ];
        if (!empty($exportRows)) {
            $exportArray[] = array_keys($exportRows[0]);
            foreach ($exportRows as $row) {
                $exportArray[] = array_values($row);
            }
        }

        $prefix = (string) Setting::get('export_prefix', '');
        $exportFileName = ($prefix ? $prefix . '_' : '') . str_replace('.csv', '.xlsx', $filename);
        return Excel::download(new PoliciesTrackingExport($exportArray), $exportFileName);
    }
}
