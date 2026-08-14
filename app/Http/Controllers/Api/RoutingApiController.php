<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RoutingApiController extends Controller
{
    /**
     * Calcula a rota real entre coordenadas utilizando OSRM (Open Source Routing Machine)
     */
    public function directions(Request $request)
    {
        $request->validate([
            'coordinates' => 'required|string',
            'mode' => 'nullable|string|in:driving,walking,cycling',
        ]);

        $coordinates = trim($request->input('coordinates'));
        $mode = $request->input('mode', 'driving');

        // Validar se há pelo menos dois pontos (ex: "lng1,lat1;lng2,lat2")
        $points = explode(';', $coordinates);
        if (count($points) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Informe pelo menos duas coordenadas no formato lng1,lat1;lng2,lat2'
            ], 422);
        }

        $cacheKey = 'osrm_route_' . md5($mode . '_' . $coordinates);

        $routeData = Cache::remember($cacheKey, 86400, function () use ($coordinates, $mode, $points) {
            try {
                $osrmProfile = ($mode === 'walking') ? 'foot' : (($mode === 'cycling') ? 'bicycle' : 'driving');
                $url = "https://router.project-osrm.org/route/v1/{$osrmProfile}/{$coordinates}";

                $response = Http::withHeaders([
                    'User-Agent' => 'TurismoPWA/1.0 (turismo-app@local.dev)',
                ])->timeout(8)->get($url, [
                    'overview' => 'full',
                    'geometries' => 'geojson',
                    'steps' => 'false',
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (($json['code'] ?? '') === 'Ok' && !empty($json['routes'])) {
                        $primary = $json['routes'][0];
                        return [
                            'success' => true,
                            'is_fallback' => false,
                            'distance_km' => round($primary['distance'] / 1000, 2),
                            'duration_minutes' => (int) round($primary['duration'] / 60),
                            'mode' => $mode,
                            'geojson' => $primary['geometry'],
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao consultar API OSRM: ' . $e->getMessage());
            }

            // Fallback gracioso calculando linha direta e distância Haversine
            return $this->generateFallbackRoute($points, $mode);
        });

        return response()->json($routeData);
    }

    /**
     * Gera rota de fallback com distância estimada em linha reta caso o OSRM esteja indisponível
     */
    protected function generateFallbackRoute(array $points, string $mode): array
    {
        $lineCoords = [];
        $totalDistKm = 0.0;

        $parsedPoints = [];
        foreach ($points as $p) {
            $parts = explode(',', trim($p));
            if (count($parts) === 2) {
                $lng = (float)$parts[0];
                $lat = (float)$parts[1];
                $parsedPoints[] = ['lat' => $lat, 'lng' => $lng];
                $lineCoords[] = [$lng, $lat];
            }
        }

        for ($i = 0; $i < count($parsedPoints) - 1; $i++) {
            $p1 = $parsedPoints[$i];
            $p2 = $parsedPoints[$i + 1];
            $totalDistKm += $this->haversineKm($p1['lat'], $p1['lng'], $p2['lat'], $p2['lng']);
        }

        // Estimativa de tempo baseada na velocidade média (carro: 40km/h, a pé: 4.5km/h)
        $speedKmh = ($mode === 'walking') ? 4.5 : 40.0;
        $durationMinutes = (int) ceil(($totalDistKm / $speedKmh) * 60);

        return [
            'success' => true,
            'is_fallback' => true,
            'distance_km' => round($totalDistKm, 2),
            'duration_minutes' => max(1, $durationMinutes),
            'mode' => $mode,
            'geojson' => [
                'type' => 'LineString',
                'coordinates' => $lineCoords,
            ],
        ];
    }

    /**
     * Fórmula Haversine para cálculo de distância entre duas coordenadas
     */
    protected function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
