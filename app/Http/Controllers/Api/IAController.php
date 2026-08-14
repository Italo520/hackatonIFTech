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
            'idioma' => 'nullable|string|in:pt-BR,en,es',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:10',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
        ]);

        $userLocation = [
            'cidade' => $request->input('cidade'),
            'uf' => $request->input('uf'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
        ];

        $historico = $request->input('historico', []);
        $response = $this->iaService->chat($request->pergunta, $request->idioma ?? 'pt-BR', $userLocation, $historico);

        return response()->json($response);
    }

    public function gerarRoteiro(Request $request)
    {
        $request->validate([
            'tema' => 'nullable|string|max:100',
            'duracao_max' => 'nullable|integer|min:30',
            'orcamento_max' => 'nullable|numeric|min:0',
            'cidade' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
        ]);

        $roteiro = $this->iaService->gerarRoteiro($request->all());

        return response()->json($roteiro);
    }

}
