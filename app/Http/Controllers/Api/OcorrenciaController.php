<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ocorrencia;
use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'gravidade' => 'required|in:baixa,media,alta,critica',
            'descricao' => 'required|string|max:1000',
            'local_texto' => 'nullable|string',
            'entidade_id' => 'nullable|integer',
            'entidade_type' => 'nullable|string',
        ]);

        $ocorrencia = Ocorrencia::create([
            'tipo' => $data['tipo'],
            'gravidade' => $data['gravidade'],
            'descricao' => $data['descricao'],
            'local_texto' => $data['local_texto'] ?? null,
            'entidade_id' => $data['entidade_id'] ?? null,
            'entidade_type' => $data['entidade_type'] ?? null,
            'status_atendimento' => 'aberto',
            'origem' => 'app_turista'
        ]);

        return response()->json([
            'message' => 'Ocorrência registrada com sucesso.',
            'protocolo' => 'OC-' . str_pad($ocorrencia->id, 6, '0', STR_PAD_LEFT)
        ], 201);
    }
}
