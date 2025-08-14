<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination konfigürasyonu
        \Illuminate\Pagination\Paginator::useBootstrap();
        
        // You can configure queue/scheduler in console kernel if present.
    }
}
