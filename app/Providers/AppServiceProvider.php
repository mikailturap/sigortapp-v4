<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Uygulama servislerini kaydet.
     */
    public function register(): void
    {
        //
    }

    /**
     * Uygulama servislerini başlat.
     */
    public function boot(): void
    {
        // Sayfalama konfigürasyonu
        \Illuminate\Pagination\Paginator::useBootstrap();

        // Uygulama yerelini zorla Türkçe yap (env yanlışsa bile)
        App::setLocale(config('app.locale', 'tr'));
    }
}
