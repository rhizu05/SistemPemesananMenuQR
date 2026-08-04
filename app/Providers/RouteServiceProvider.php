<?php

namespace App\Providers;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);

        $this->mapRoutes();
    }

    protected function mapRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
    }
}
