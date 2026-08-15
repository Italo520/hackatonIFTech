<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atrativo;
use App\Http\Requests\IndexAtrativoRequest;
use Illuminate\Support\Facades\DB;

class AtrativoController extends Controller
{
    /**
     * Display a listing of the resource with location and proximity support.
     */
    public function index(IndexAtrativoRequest $request)
    {
        $query = Atrativo::with(['categoria', 'municipio'])
            ->where('status', 'ativo');

        // US-002: Busca por palavra-chave (TSVECTOR no Postgres, fallback no SQLite)
        if ($request->filled('q')) {
            $q = $request->query('q');
            if (DB::getDriverName() === 'pgsql') {
                $query->whereRaw("search_vector @@ to_tsquery('portuguese', ?)", [str_replace(' ', ' & ', $q)]);
            } else {
                $query->where(function($qBuilder) use ($q) {
                    $qBuilder->where('nome', 'like', "%{$q}%")
                             ->orWhere('descricao', 'like', "%{$q}%");
                });
            }
        }

        // US-002: Filtro de Categoria
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->query('categoria_id'));
        }

        // Filtro por Município (ID ou nome da cidade)
        if ($request->filled('municipio_id')) {
            $query->where('municipio_id', $request->query('municipio_id'));
        } elseif ($request->filled('cidade')) {
            $cidade = $request->query('cidade');
            $query->whereHas('municipio', function($m) use ($cidade) {
                $m->where('nome', 'like', "%{$cidade}%");
            });
        }

        // US-005: Acessibilidade
        if ($request->filled('acessivel_para')) {
            $acessivel = $request->query('acessivel_para');
            
            if (DB::getDriverName() === 'pgsql') {
                $query->whereJsonContains('acessibilidade', $acessivel);
            } else {
                $query->where(function($b) use ($acessivel) {
                    $b->whereJsonContains('acessibilidade', $acessivel)
                      ->orWhere('acessibilidade', 'like', "%\"{$acessivel}\"%")
                      ->orWhere('acessibilidade', 'like', "%{$acessivel}%");
                });
            }
        }

        // RF-A02: Filtro por Duração máxima
        if ($request->filled('duracao_max')) {
            $query->where('tempo_medio_visita', '<=', $request->query('duracao_max'));
        }

        $userLat = $request->filled('lat') ? (float) $request->query('lat') : null;
        $userLng = $request->filled('lng') ? (float) $request->query('lng') : ($request->filled('lon') ? (float) $request->query('lon') : null);
        $raioKm = $request->filled('raio_km') ? (float) $request->query('raio_km') : ($request->filled('radius') ? (float) $request->query('radius') : null);
        $sortBy = $request->query('sort_by');

        $atrativosPaginados = $query->get();

        // Se houver coordenadas GPS do usuário, calcula distância para cada item
        if (!is_null($userLat) && !is_null($userLng)) {
            $atrativosPaginados = $atrativosPaginados->map(function ($item) use ($userLat, $userLng) {
                $dist = $item->calcularDistanciaKm($userLat, $userLng);
                $item->distancia_km = $dist;
                $item->distancia_formatada = $item->formatarDistancia($dist);
                return $item;
            });

            // Filtra por raio se solicitado
            if (!is_null($raioKm) && $raioKm > 0) {
                $atrativosPaginados = $atrativosPaginados->filter(function ($item) use ($raioKm) {
                    return !is_null($item->distancia_km) && $item->distancia_km <= $raioKm;
                });
            }

            // Ordena por proximidade por padrão ou quando explicitamente solicitado
            if ($sortBy === 'distancia' || $sortBy === 'mais_proximos' || is_null($sortBy)) {
                $atrativosPaginados = $atrativosPaginados->sortBy(function ($item) {
                    return is_null($item->distancia_km) ? 999999 : $item->distancia_km;
                })->values();
            }
        }

        // Paginação manual para preservar distâncias calculadas
        $perPage = (int) $request->query('per_page', $request->query('limit', 15));
        $perPage = max(1, min($perPage, 500));
        $page = (int) $request->query('page', 1);
        $total = $atrativosPaginados->count();
        $items = $atrativosPaginados->forPage($page, $perPage)->values();

        return response()->json([
            'current_page' => $page,
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'last_page' => (int) ceil($total / $perPage),
            'user_location' => (!is_null($userLat) && !is_null($userLng)) ? [
                'lat' => $userLat,
                'lng' => $userLng,
            ] : null,
        ]);
    }

    /**
     * Display the specified resource with distance calculation if coords provided.
     */
    public function show(string $id)
    {
        $atrativo = Atrativo::with(['categoria', 'municipio'])
            ->where('status', 'ativo')
            ->findOrFail($id);
            
        $userLat = request()->filled('lat') ? (float) request()->query('lat') : null;
        $userLng = request()->filled('lng') ? (float) request()->query('lng') : (request()->filled('lon') ? (float) request()->query('lon') : null);

        if (!is_null($userLat) && !is_null($userLng)) {
            $dist = $atrativo->calcularDistanciaKm($userLat, $userLng);
            $atrativo->distancia_km = $dist;
            $atrativo->distancia_formatada = $atrativo->formatarDistancia($dist);
        }

        return response()->json($atrativo);
    }
}

