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

        // View Composer para injetar alertas ativos e municípios com contagem real no layout PWA
        \Illuminate\Support\Facades\View::composer(['layouts.pwa', 'pwa.*'], function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('alertas')) {
                    $alertasAtivos = \App\Models\Alerta::ativos()->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('alertasAtivos', $alertasAtivos);
                } else {
                    $view->with('alertasAtivos', collect());
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('municipios')) {
                    $municipiosDropdown = \App\Models\Municipio::withCount(['atrativos' => function ($q) {
                        $q->where('status', 'ativo');
                    }])->orderBy('nome')->get()->map(function ($m) {
                        $coords = [
                            'João Pessoa' => ['lat' => -7.1153, 'lng' => -34.8641, 'color' => 'bg-primary', 'estado' => 'Paraíba'],
                            'Bonito' => ['lat' => -21.1275, 'lng' => -56.4831, 'color' => 'bg-success', 'estado' => 'Mato Grosso do Sul'],
                            'Recife' => ['lat' => -8.0476, 'lng' => -34.8770, 'color' => 'bg-warning', 'estado' => 'Pernambuco'],
                            'Natal' => ['lat' => -5.7945, 'lng' => -35.2110, 'color' => 'bg-info', 'estado' => 'Rio Grande do Norte'],
                            'São Paulo' => ['lat' => -23.5505, 'lng' => -46.6333, 'color' => 'bg-danger', 'estado' => 'São Paulo'],
                        ];
                        $c = $coords[$m->nome] ?? ['lat' => -7.1153, 'lng' => -34.8641, 'color' => 'bg-primary', 'estado' => $m->uf];
                        $m->lat = $c['lat'];
                        $m->lng = $c['lng'];
                        $m->color_badge = $c['color'];
                        $m->estado_nome = $c['estado'];
                        return $m;
                    });
                    $view->with('municipiosDropdown', $municipiosDropdown);
                } else {
                    $view->with('municipiosDropdown', collect());
                }
            } catch (\Throwable $e) {
                $view->with('alertasAtivos', collect());
                $view->with('municipiosDropdown', collect());
            }
        });
    }
}
