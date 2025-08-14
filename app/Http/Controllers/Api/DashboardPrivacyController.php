<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardPrivacySetting;

class DashboardPrivacyController extends Controller
{
    public function getSettings()
    {
        $settings = DashboardPrivacySetting::getDefaultSettings();
        
        return response()->json([
            'key_combination' => $settings->key_combination,
            'is_enabled' => $settings->is_enabled
        ]);
    }
}
