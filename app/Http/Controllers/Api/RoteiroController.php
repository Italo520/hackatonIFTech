<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roteiro;
use Illuminate\Http\Request;

class RoteiroController extends Controller
{
    public function index(Request $request)
    {
        $query = Roteiro::where('publico', true);

        if ($request->filled('tema')) {
            $query->where('tema', $request->query('tema'));
        }

        if ($request->filled('dificuldade')) {
            $query->where('dificuldade', $request->query('dificuldade'));
        }

        if ($request->filled('transporte')) {
            $query->where('transporte', $request->query('transporte'));
        }

        if ($request->filled('duracao_max')) {
            $query->where('duracao', '<=', $request->query('duracao_max'));
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->query('origem'));
        }

        $roteiros = $query->paginate(15);
        return response()->json($roteiros);
    }

    public function export(string $id)
    {
        $roteiro = Roteiro::with("itens.atrativo.midias")->findOrFail($id);
        return response()->json([
            "roteiro" => $roteiro,
            "tiles_bbox" => "bbox_tiles_payload",
            "timestamp" => now()->toIso8601String()
        ]);
    }

    public function show(string $id)
    {
        $roteiro = Roteiro::with('itens.atrativo')->findOrFail($id);
        return response()->json($roteiro);
    }
}
