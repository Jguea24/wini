<?php

namespace App\Providers;

use App\Models\Gasto;
use App\Models\Inversion;
use App\Models\Venta;
use App\Policies\GastoPolicy;
use App\Policies\InversionPolicy;
use App\Policies\VentaPolicy;
use App\Repositories\CocoaMarketPriceRepository;
use App\Repositories\Contracts\CocoaMarketPriceRepositoryInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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
        $this->app->bind(CocoaMarketPriceRepositoryInterface::class, CocoaMarketPriceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = URL::route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false);

            return (new MailMessage)
                ->subject('Recuperar la contraseña')
                ->view('emails.auth.reset-password', [
                    'user' => $notifiable,
                    'resetUrl' => url($resetUrl),
                    'expiresIn' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
                ]);
        });

        Gate::policy(Venta::class, VentaPolicy::class);
        Gate::policy(Gasto::class, GastoPolicy::class);
        Gate::policy(Inversion::class, InversionPolicy::class);

        if (app()->environment('production') && (bool) config('app.force_https', false)) {
            URL::forceScheme('https');
        }
    }
}
