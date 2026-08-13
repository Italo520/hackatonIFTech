<?php

namespace App\Services;

use App\Models\AssistantLog;

class IAService
{
    /**
     * RAG based chat response (mocked for MVP/Tests without OpenAI key)
     */
    public function chat(string $pergunta, string $idioma = 'pt-BR'): array
    {
        // PII Scrubbing would happen here before saving/sending
        // e.g. Regex replacements for CPF, Email, Phone...
        $scrubbedPergunta = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', '[EMAIL]', $pergunta);

        // Simulated RAG Response
        $resposta = "De acordo com nossa base oficial, o atrativo mais próximo atende aos seus critérios. [Conteúdo Gerado por IA]";
        $fontes = [
            ['id' => 1, 'nome' => 'Atrativo Turístico 1', 'tipo' => 'atrativo']
        ];

        // Audit Log for AI metrics
        AssistantLog::create([
            'pergunta' => $scrubbedPergunta,
            'resposta' => $resposta,
            'fontes' => $fontes,
            'idioma' => $idioma,
        ]);

        return [
            'resposta' => $resposta,
            'fontes' => $fontes,
            'is_ia' => true
        ];
    }

    /**
     * AI based roteiro generator
     */
    public function gerarRoteiro(array $preferences): array
    {
        // Mock generation
        return [
            'titulo' => 'Roteiro IA: ' . ($preferences['tema'] ?? 'Geral'),
            'duracao' => $preferences['duracao_max'] ?? 120,
            'orcamento' => $preferences['orcamento_max'] ?? 0,
            'itens' => [
                ['atrativo_id' => 1, 'ordem' => 1, 'tempo_estimado' => 60],
                ['atrativo_id' => 2, 'ordem' => 2, 'tempo_estimado' => 60]
            ],
            'is_ia' => true
        ];
    }
}
