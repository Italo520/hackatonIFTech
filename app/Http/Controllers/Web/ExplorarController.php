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
    public function index()
    {
        // Carrega apenas categorias do tipo "atrativo" para os chips de filtro.
        // Ordenadas por nome para exibição consistente.
        $categorias = Categoria::where('tipo', 'atrativo')
            ->orderBy('nome')
            ->get(['id', 'nome', 'slug', 'icone']);

        // Fetch principais lugares from database (limit to 4)
        $principais_lugares = \App\Models\Atrativo::with('categoria')->inRandomOrder()->limit(4)->get();
        
        // Fetch atividades gratuitas from database (limit to 4)
        $atividades_gratuitas = \App\Models\Atrativo::with('categoria')->inRandomOrder()->limit(4)->get();

        // Fetch eventos from database (limit to 4)
        $eventos = \App\Models\Evento::where('status', 'ativo')
            ->orderBy('inicio', 'asc')
            ->limit(4)
            ->get();

        return view('pwa.explorar', compact('categorias', 'principais_lugares', 'atividades_gratuitas', 'eventos'));
    }
}
