<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migrasyonları çalıştır.
     */
    public function up(): void
    {
        Schema::create('dashboard_privacy_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_combination')->default('Ctrl+Shift+P'); // Varsayılan tuş kombinasyonu
            $table->boolean('is_enabled')->default(true); // Özellik aktif mi?
            $table->text('description')->nullable(); // Açıklama
            $table->timestamps();
        });
    }

    /**
     * Migrasyonları geri al.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_privacy_settings');
    }
};
