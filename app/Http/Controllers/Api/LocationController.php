<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Mapeamento de Estados Brasileiros para siglas UF
     */
    protected static array $brazilStates = [
        'Acre' => 'AC', 'Alagoas' => 'AL', 'Amapá' => 'AP', 'Amazonas' => 'AM',
        'Bahia' => 'BA', 'Ceará' => 'CE', 'Distrito Federal' => 'DF', 'Espírito Santo' => 'ES',
        'Goiás' => 'GO', 'Maranhão' => 'MA', 'Mato Grosso' => 'MT', 'Mato Grosso do Sul' => 'MS',
        'Minas Gerais' => 'MG', 'Pará' => 'PA', 'Paraíba' => 'PB', 'Paraná' => 'PR',
        'Pernambuco' => 'PE', 'Piauí' => 'PI', 'Rio de Janeiro' => 'RJ', 'Rio Grande do Norte' => 'RN',
        'Rio Grande do Sul' => 'RS', 'Rondônia' => 'RO', 'Roraima' => 'RR', 'Santa Catarina' => 'SC',
        'São Paulo' => 'SP', 'Sergipe' => 'SE', 'Tocantins' => 'TO'
    ];

    /**
     * Geocodificação reversa via OpenStreetMap Nominatim
     */
    public function reverse(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required_without:lon|nullable|numeric|between:-180,180',
            'lon' => 'required_without:lng|nullable|numeric|between:-180,180',
        ]);

        $lat = (float) $request->input('lat');
        $lng = (float) ($request->input('lng') ?? $request->input('lon'));

        // Chave de cache arredondando coordenadas para ~100m para otimização
        $cacheKey = sprintf('osm_geo_%.3f_%.3f', $lat, $lng);

        $locationData = Cache::remember($cacheKey, 86400, function () use ($lat, $lng) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'TurismoPWA/1.0 (turismo-app@local.dev)',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                ])->timeout(8)->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $lat,
                    'lon' => $lng,
                    'zoom' => 10,
                    'addressdetails' => 1,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->parseNominatimResponse($data, $lat, $lng);
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao consultar Nominatim OSM: ' . $e->getMessage());
            }

            return null;
        });

        if (!$locationData) {
            return response()->json([
                'success' => false,
                'city' => 'Localização Atual',
                'state' => '',
                'display' => 'Localização Atual',
                'lat' => $lat,
                'lng' => $lng,
            ], 200);
        }

        return response()->json(array_merge(['success' => true], $locationData));
    }

    /**
     * Busca de cidades/endereços via OpenStreetMap Nominatim
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $q = trim($request->input('q'));
        $cacheKey = 'osm_search_' . md5(mb_strtolower($q));

        $results = Cache::remember($cacheKey, 3600, function () use ($q) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'TurismoPWA/1.0 (turismo-app@local.dev)',
                    'Accept-Language' => 'pt-BR,pt;q=0.9',
                ])->timeout(8)->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'jsonv2',
                    'q' => $q,
                    'countrycodes' => 'br',
                    'limit' => 6,
                    'addressdetails' => 1,
                ]);

                if ($response->successful()) {
                    $items = $response->json();
                    return array_map(function ($item) {
                        $parsed = $this->parseNominatimResponse($item, (float) $item['lat'], (float) $item['lon']);
                        return array_merge($parsed, [
                            'display_name' => $item['display_name'] ?? $parsed['display'],
                        ]);
                    }, $items);
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao buscar no Nominatim OSM: ' . $e->getMessage());
            }

            return [];
        });

        return response()->json($results);
    }

    /**
     * Normaliza e extrai dados relevantes do OpenStreetMap
     */
    protected function parseNominatimResponse(array $data, float $lat, float $lng): array
    {
        $address = $data['address'] ?? [];

        // Identifica o município com fallbacks inteligentes
        $city = $address['city'] 
            ?? $address['town'] 
            ?? $address['municipality'] 
            ?? $address['village'] 
            ?? $address['city_district'] 
            ?? $address['county'] 
            ?? $address['suburb'] 
            ?? 'Desconhecido';

        // Identifica o estado / UF
        $state = $address['state'] ?? '';
        $uf = '';

        if (!empty($address['ISO3166-2-lvl4'])) {
            $uf = str_replace('BR-', '', $address['ISO3166-2-lvl4']);
        } elseif (isset(self::$brazilStates[$state])) {
            $uf = self::$brazilStates[$state];
        } else {
            $uf = $state;
        }

        $display = $city;
        if (!empty($uf) && $city !== 'Desconhecido') {
            $display = $city . ' ' . $uf;
        }

        return [
            'city' => $city,
            'state' => $state,
            'uf' => $uf,
            'display' => $display,
            'neighborhood' => $address['suburb'] ?? $address['neighbourhood'] ?? null,
            'country' => $address['country'] ?? 'Brasil',
            'lat' => $lat,
            'lng' => $lng,
            'raw_address' => $address,
        ];
    }
}
