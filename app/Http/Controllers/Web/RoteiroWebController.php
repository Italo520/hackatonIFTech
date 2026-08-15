<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Roteiro;
use Illuminate\Http\Request;

class RoteiroWebController extends Controller
{
    /**
     * Exibe o catálogo de roteiros turísticos prontos e oficiais.
     */
    public function index(Request $request)
    {
        $query = Roteiro::with(['itens.atrativo.midias']);

        if ($request->filled('duracao')) {
            $duracao = $request->query('duracao');
            if ($duracao === 'curto') {
                $query->where('duracao', '<=', 240);
            } elseif ($duracao === 'dia') {
                $query->whereBetween('duracao', [241, 480]);
            } elseif ($duracao === 'fimdesemana') {
                $query->where('duracao', '>', 480);
            }
        }

        $roteiros = $query->orderBy('id', 'desc')->paginate(9);

        return view('pwa.roteiros', compact('roteiros'));
    }

    /**
     * Exibe os detalhes de um roteiro com mapa interativo e paradas.
     */
    public function show($id)
    {
        $roteiro = null;
        if (is_numeric($id)) {
            $roteiro = Roteiro::with('itens.atrativo.midias')->find($id);
        }

        return view('pwa.roteiro', [
            'id' => $id,
            'dbRoteiro' => $roteiro,
        ]);
    }
}
