<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventKitchenAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user adalah kitchen, redirect ke kitchen dashboard
        if (auth()->check() && auth()->user()->isKitchen()) {
            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
