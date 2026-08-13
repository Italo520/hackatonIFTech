<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IAService;

class IAController extends Controller
{
    protected $iaService;

    public function __construct(IAService $iaService)
    {
        $this->iaService = $iaService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'pergunta' => 'required|string|max:500',
            'idioma' => 'nullable|string|in:pt-BR,en,es'
        ]);

        $response = $this->iaService->chat($request->pergunta, $request->idioma ?? 'pt-BR');

        return response()->json($response);
    }

    public function gerarRoteiro(Request $request)
    {
        $request->validate([
            'tema' => 'nullable|string|max:100',
            'duracao_max' => 'nullable|integer|min:30',
            'orcamento_max' => 'nullable|numeric|min:0'
        ]);

        $roteiro = $this->iaService->gerarRoteiro($request->all());

        return response()->json($roteiro);
    }
}
