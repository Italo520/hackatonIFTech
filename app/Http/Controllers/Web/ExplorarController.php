<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Categoria;

class ExplorarController extends Controller
{
    /**
     * Exibe a página de exploração de atrativos e eventos.
     *
     * As categorias são carregadas do banco para popular os chips de filtro.
     * A busca e paginação são feitas no client-side via API REST (/api/v1/atrativos).
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // Carrega apenas categorias do tipo "atrativo" para os chips de filtro.
        // Ordenadas por nome para exibição consistente.
        $categorias = Categoria::where('tipo', 'atrativo')
            ->orderBy('nome')
            ->get(['id', 'nome', 'slug', 'icone']);

        $query = \App\Models\Atrativo::with('categoria', 'municipio');
        $is_search = false;

        // Filtro por Município (cidade selecionada pelo LocationService via cookie ou query)
        $municipioNome = $request->query('municipio', $request->cookie('turismo_user_city'));
        if ($municipioNome) {
            $query->whereHas('municipio', function ($m) use ($municipioNome) {
                $m->where('nome', 'like', "%{$municipioNome}%");
            });
        }

        // Filtro por texto (Busca Inteligente)
        if ($request->filled('q')) {
            $is_search = true;
            $q = $request->q;
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('nome', 'like', "%{$q}%")
                         ->orWhere('descricao', 'like', "%{$q}%");
            });
        }

        // Filtro por Categorias
        if ($request->filled('cat')) {
            $is_search = true;
            $cats = (array) $request->cat;
            $query->whereHas('categoria', function ($qBuilder) use ($cats) {
                $qBuilder->whereIn('slug', $cats);
            });
        }

        // Filtro por Acessibilidade
        if ($request->filled('acess')) {
            $is_search = true;
            $acess = (array) $request->acess;
            foreach ($acess as $a) {
                $query->whereJsonContains('acessibilidade', $a);
            }
        }

        // Captura de Orçamento
        $orcamento = null;
        if ($request->filled('orcamento')) {
            $is_search = true;
            $orcamento = (float) $request->orcamento;
        }

        // Se houver busca ou listagem de lugares
        if ($is_search) {
            $principais_lugares = $query->orderBy('nome', 'asc')->paginate(9);
            $atividades_gratuitas = collect();
            $eventos = collect();
        } else {
            // Comportamento da tela de explorar: todos os atrativos paginados em 9 por página
            $principais_lugares = (clone $query)->orderBy('id', 'asc')->paginate(9);
            
            $gratuitasQuery = \App\Models\Atrativo::with('categoria');
            if ($municipioNome) {
                $gratuitasQuery->whereHas('municipio', function ($m) use ($municipioNome) {
                    $m->where('nome', 'like', "%{$municipioNome}%");
                });
            }
            $atividades_gratuitas = $gratuitasQuery->inRandomOrder()->limit(4)->get();
            
            $eventosQuery = \App\Models\Evento::where('status', 'ativo');
            if ($municipioNome) {
                $eventosQuery->where(function ($q) use ($municipioNome) {
                    $q->where('local', 'like', "%{$municipioNome}%")
                      ->orWhere('descricao', 'like', "%{$municipioNome}%")
                      ->orWhere('nome', 'like', "%{$municipioNome}%");
                });
            }
            $eventos = $eventosQuery->orderBy('inicio', 'asc')->limit(4)->get();
            if ($eventos->isEmpty() && $municipioNome) {
                $eventos = \App\Models\Evento::where('status', 'ativo')->orderBy('inicio', 'asc')->limit(4)->get();
            }
        }

        return view('pwa.explorar', compact('categorias', 'principais_lugares', 'atividades_gratuitas', 'eventos', 'is_search', 'municipioNome'));
    }
}
