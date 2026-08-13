<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avaliacao;

class SyncController extends Controller
{
    public function syncAvaliacoes(Request $request)
    {
        $payload = $request->validate([
            'avaliacoes' => 'required|array',
            'avaliacoes.*.entidade_id' => 'required|integer',
            'avaliacoes.*.entidade_type' => 'required|string',
            'avaliacoes.*.nota' => 'required|integer|min:1|max:5',
            'avaliacoes.*.comentario' => 'nullable|string',
        ]);

        $synced = [];
        foreach ($payload['avaliacoes'] as $av) {
            $synced[] = Avaliacao::create([
                'user_id' => $request->user()->id ?? null,
                'entidade_id' => $av['entidade_id'],
                'entidade_type' => $av['entidade_type'],
                'nota' => $av['nota'],
                'comentario' => $av['comentario'] ?? null,
                'origem_offline' => true,
                'status_moderacao' => 'pendente'
            ]);
        }

        return response()->json(['message' => 'Sincronização concluída', 'count' => count($synced)], 200);
    }
}
