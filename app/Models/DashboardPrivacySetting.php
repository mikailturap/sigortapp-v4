<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardPrivacySetting extends Model
{
    protected $fillable = [
        'key_combination',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    // Varsayılan ayarları döndür
    public static function getDefaultSettings()
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'key_combination' => 'Ctrl+Shift+P',
                'is_enabled' => true
            ]
        );
    }
}
