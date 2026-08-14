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

        $query = \App\Models\Atrativo::with('categoria');
        $is_search = false;

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

        // Se houver busca, trazemos mais resultados e omitimos os blocos extras
        if ($is_search) {
            $principais_lugares = $query->inRandomOrder()->limit(12)->get();
            
            // Aplica o filtro de orçamento em memória (Collection) 
            // Visto que os preços estão num array JSON e o MySQL dificulta filtrar intervalo numérico dentro de array
            if ($orcamento !== null) {
                $principais_lugares = $principais_lugares->filter(function ($atrativo) use ($orcamento) {
                    if (empty($atrativo->precos)) {
                        return true; // Considera gratuito se não houver preço
                    }
                    
                    $menorPreco = null;
                    foreach ($atrativo->precos as $preco) {
                        // Verifica se é array ["valor" => X] ou um número solto
                        $v = is_array($preco) ? ($preco['valor'] ?? 0) : (is_numeric($preco) ? $preco : 0);
                        if ($menorPreco === null || $v < $menorPreco) {
                            $menorPreco = (float) $v;
                        }
                    }
                    
                    return $menorPreco === null || $menorPreco <= $orcamento;
                })->values();
            }

            $atividades_gratuitas = collect();
            $eventos = collect();
        } else {
            // Comportamento original da home de explorar
            $principais_lugares = (clone $query)->inRandomOrder()->limit(4)->get();
            
            $atividades_gratuitas = \App\Models\Atrativo::with('categoria')->inRandomOrder()->limit(4)->get();
            
            $eventos = \App\Models\Evento::where('status', 'ativo')
                ->orderBy('inicio', 'asc')
                ->limit(4)
                ->get();
        }

        return view('pwa.explorar', compact('categorias', 'principais_lugares', 'atividades_gratuitas', 'eventos', 'is_search'));
    }
}
