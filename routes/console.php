<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Setting;
use App\Models\Policy;
use App\Jobs\SendPolicySmsReminder;
use App\Exports\PoliciesTrackingExport;
use App\Mail\TrackingDailySummaryMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Günlük SMS hatırlatıcı (09:00)
Schedule::call(function () {
    $today = now()->startOfDay();
    $smsEnabled = Setting::get('sms_enabled', '0') === '1';
    if (!$smsEnabled) return;
    $daysBefore = (int) Setting::get('sms_reminder_days', 3);
    $targetDate = $today->copy()->addDays($daysBefore);

    $candidates = Policy::query()
        ->whereDate('end_date', $targetDate)
        ->whereNull('sms_reminder_sent_at')
        ->where('status', 'aktif')
        ->get();

    foreach ($candidates as $policy) {
        dispatch(new SendPolicySmsReminder($policy))->onQueue('default');
    }
})->dailyAt('09:00');

// Günlük e-posta özeti
Schedule::call(function () {
    $companyEmail = Setting::get('company_email');
    if (!$companyEmail) return;

    $today = now()->startOfDay();
    $window = (int) Setting::get('tracking_window_days', 30);

    $expired = Policy::where('end_date', '<', $today)->get();
    $todayList = Policy::whereDate('end_date', $today)->get();
    $upcoming = Policy::where('end_date', '>', $today)->where('end_date', '<=', $today->copy()->addDays($window))->get();
    $activeCount = Policy::where('status', 'aktif')->count();

    // Tek sheet veri
    $columns = ['Kategori','ID','Müşteri','Telefon','Poliçe No','Tür','Başlangıç','Bitiş','Durum'];
    $rows = [];
    foreach ([['Süresi Geçmiş',$expired],['Bugün',$todayList],['Yaklaşan',$upcoming]] as [$label,$list]) {
        foreach ($list as $p) {
            $rows[] = [
                $label,
                $p->id,
                $p->customer_title,
                $p->customer_phone,
                $p->policy_number,
                $p->policy_type,
                optional($p->start_date)->format('Y-m-d'),
                optional($p->end_date)->format('Y-m-d'),
                $p->status,
            ];
        }
    }
    $array = [
        ['Günlük Poliçe Takip Özeti - '.now()->format('Y-m-d')],
        [],
        $columns,
        ...$rows,
    ];

    $export = new PoliciesTrackingExport($array);
    $fileName = 'gunluk_poliseler_' . now()->format('Ymd') . '.xlsx';
    $tempPath = storage_path('app/'.$fileName);
    Excel::store($export, $fileName);

    // Tek mail (ekli xlsx ile)
    Mail::to($companyEmail)->send((new TrackingDailySummaryMail([
        'today' => $todayList->count(),
        'upcoming' => $upcoming->count(),
        'expired' => $expired->count(),
        'active' => $activeCount,
    ]))->attach($tempPath));
})->dailyAt(Setting::get('daily_summary_time', '08:30'));
