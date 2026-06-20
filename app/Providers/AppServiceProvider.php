<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\NotifikasiUser;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $notifCount = 0;
            if (auth()->check()) {
                $notifCount = NotifikasiUser::where('id_user', auth()->user()->id_user)
                    ->where('is_read', false) // atur sesuai kolom unread kamu
                    ->count();
            }
            $view->with('notifCount', $notifCount);
        });

        // Global Anti-DDoS Rate Limiter (150 requests per minute)
        \Illuminate\Support\Facades\RateLimiter::for('global', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(150)->by($request->ip());
        });

        // Anti-Bruteforce for Login (5 requests per minute)
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->input('email').$request->ip());
        });

        // OTP Rate Limiter (5 requests per minute)
        \Illuminate\Support\Facades\RateLimiter::for('otp', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });

        // Register Rate Limiter (3 requests per minute)
        \Illuminate\Support\Facades\RateLimiter::for('register', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by($request->ip());
        });

        // Password Reset Rate Limiter (3 requests per minute)
        \Illuminate\Support\Facades\RateLimiter::for('password-reset', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by($request->ip());
        });
    }
}
