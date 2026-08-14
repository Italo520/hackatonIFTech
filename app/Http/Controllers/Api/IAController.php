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

        if ($request->header('Accept') !== 'text/event-stream' && ($request->expectsJson() || $request->wantsJson())) {
            return response()->json([
                'is_ia' => true,
                'resposta' => $response['resposta'],
                'fontes' => $response['fontes'],
                'cidade_detectada' => $response['cidade_detectada'] ?? null,
            ]);
        }

        return response()->stream(function () use ($response) {
            // Envia os metadados iniciais (fontes e cidade)
            $meta = json_encode([
                'fontes' => $response['fontes'],
                'cidade_detectada' => $response['cidade_detectada']
            ]);
            echo "event: meta\ndata: {$meta}\n\n";
            ob_flush();
            flush();

            // Simula o streaming das palavras (Efeito Real-Time SSE)
            $words = preg_split('/( +)/', $response['resposta'], -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($words as $word) {
                if ($word === '') continue;
                $chunk = json_encode(['chunk' => $word]);
                echo "data: {$chunk}\n\n";
                ob_flush();
                flush();
                usleep(30000); // 30ms per token
            }

            echo "event: done\ndata: {}\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
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
