<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Gunakan Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();

        // Force HTTPS untuk ngrok dan production
        if (config('app.env') !== 'local') {
            \URL::forceScheme('https');
        }
        
        // Force HTTPS hanya jika request berasal dari ngrok
        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
            \URL::forceScheme('https');
        }
    }
}
