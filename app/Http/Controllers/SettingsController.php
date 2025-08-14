<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\DashboardPrivacySetting;

class SettingsController extends Controller
{
    public function index()
    {
        $defaults = [
            'tracking_window_days' => '30',
            'notifications_expiration_threshold_days' => (string) config('notifications.expiration_threshold_days', 7),
            'sms_enabled' => '0',
            'sms_reminder_days' => '3',
            'sms_template' => 'Sayın {customerTitle}, {policyNumber} numaralı poliçenizin bitiş tarihi olan {endDate} yaklaşıyor. Yenileme için bize ulaşabilirsiniz.',
            'whatsapp_reminder_days' => '7',
            'company_email' => '',
            'daily_summary_time' => '08:30',
            'daily_summary_format' => 'xlsx', // xlsx|pdf
        ];

        $settings = Setting::pluck('value', 'key')->toArray();
        $data = array_merge($defaults, $settings);

        // Dashboard privacy ayarlarını al
        $dashboardPrivacy = DashboardPrivacySetting::getDefaultSettings();

        return view('settings.index', [
            'settings' => $data,
            'dashboardPrivacy' => $dashboardPrivacy
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'tracking_window_days' => 'sometimes|in:7,15,30,60',
            'notifications_expiration_threshold_days' => 'sometimes|integer|min:1|max:365',
            'sms_reminder_days' => 'sometimes|integer|min:1|max:60',
            'sms_template' => 'sometimes|string|max:1000',
            'whatsapp_reminder_days' => 'sometimes|integer|min:1|max:60',
            'company_email' => 'sometimes|nullable|email',
            'daily_summary_time' => 'sometimes|string',
            'daily_summary_format' => 'sometimes|in:xlsx,pdf',
            'export_prefix' => 'sometimes|string|max:50',
        ];

        $validated = $request->validate($rules);

        // sms_enabled checkbox (yalnızca formda varsa güncelle)
        if ($request->has('sms_enabled')) {
            Setting::updateOrCreate(['key' => 'sms_enabled'], ['value' => $request->boolean('sms_enabled') ? '1' : '0']);
        }

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        // Dashboard privacy ayarlarını güncelle
        if ($request->has('dashboard_privacy_enabled')) {
            $dashboardPrivacy = DashboardPrivacySetting::getDefaultSettings();
            $dashboardPrivacy->update([
                'key_combination' => $request->input('dashboard_privacy_key_combination', 'Ctrl+Shift+P'),
                'is_enabled' => $request->boolean('dashboard_privacy_enabled')
            ]);
        }

        return back()->with('success', 'Ayarlar güncellendi');
    }
}



