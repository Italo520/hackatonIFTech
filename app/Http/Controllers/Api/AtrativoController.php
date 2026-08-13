<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atrativo;
use App\Http\Requests\IndexAtrativoRequest;
use Illuminate\Support\Facades\DB;

class AtrativoController extends Controller
{
    /**
     * Display a listing of the resource.
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

        // US-005: Acessibilidade (JSONB contains no Postgres, LIKE fallback no SQLite)
        if ($request->filled('acessivel_para')) {
            $acessivel = $request->query('acessivel_para'); // ex: 'cadeirante', 'cego'
            
            if (DB::getDriverName() === 'pgsql') {
                $query->whereJsonContains('acessibilidade', $acessivel);
            } else {
                 $query->where('acessibilidade', 'like', "%\"{$acessivel}\"%");
            }
        }

        // RF-A02: Filtro por Duração máxima
        if ($request->filled('duracao_max')) {
            $query->where('tempo_medio_visita', '<=', $request->query('duracao_max'));
        }

        // TODO: Map Radius filter (GiST geo queries) when coords are provided via query params

        $atrativos = $query->paginate(15);
        return response()->json($atrativos);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $atrativo = Atrativo::with(['categoria', 'municipio'])
            ->where('status', 'ativo')
            ->findOrFail($id);
            
        return response()->json($atrativo);
    }
}
