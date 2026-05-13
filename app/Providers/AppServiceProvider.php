<?php

namespace App\Providers;

use App\Models\Gasto;
use App\Models\Inversion;
use App\Models\Venta;
use App\Policies\GastoPolicy;
use App\Policies\InversionPolicy;
use App\Policies\VentaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        Gate::policy(Venta::class, VentaPolicy::class);
        Gate::policy(Gasto::class, GastoPolicy::class);
        Gate::policy(Inversion::class, InversionPolicy::class);

        if (app()->environment('production') && (bool) config('app.force_https', false)) {
            URL::forceScheme('https');
        }
    }
}
