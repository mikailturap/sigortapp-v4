<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DashboardPrivacySetting;

class DashboardPrivacySettingSeeder extends Seeder
{
    /**
     * Veritabanı seed'lerini çalıştır.
     */
    public function run(): void
    {
        DashboardPrivacySetting::create([
            'key_combination' => 'Ctrl+Shift+P',
            'is_enabled' => true
        ]);
    }
}
