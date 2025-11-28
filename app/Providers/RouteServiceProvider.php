<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the role middleware
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

        $this->mapRoutes();
    }

    protected function mapRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
    }
}
