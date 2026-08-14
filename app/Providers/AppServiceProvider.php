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
        if (
            app()->environment('production') ||
            str_starts_with(config('app.url', ''), 'https://') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1' || $_SERVER['HTTPS'] === 'https'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // View Composer para injetar alertas ativos e vigentes no layout PWA e telas do turista
        \Illuminate\Support\Facades\View::composer(['layouts.pwa', 'pwa.*'], function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('alertas')) {
                    $alertasAtivos = \App\Models\Alerta::ativos()->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('alertasAtivos', $alertasAtivos);
                } else {
                    $view->with('alertasAtivos', collect());
                }
            } catch (\Throwable $e) {
                $view->with('alertasAtivos', collect());
            }
        });
    }
}
