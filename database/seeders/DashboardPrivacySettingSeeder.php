<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DashboardPrivacySetting;

class DashboardPrivacySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DashboardPrivacySetting::create([
            'key_combination' => 'Ctrl+Shift+P',
            'is_enabled' => true
        ]);
    }
}
