<?php

namespace App\Services;

use App\Models\AssistantLog;
use App\Models\Atrativo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IAService
{
    private $apiKey;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Helper para chamar a API do Gemini com suporte a histórico (Memória)
     */
    private function callGemini(string $prompt, array $historico = []): string
    {
        if (empty($this->apiKey)) {
            Log::warning("Chave GEMINI_API_KEY não configurada.");
            return '{"erro": "Chave da API do Gemini não configurada no .env."}';
        }

        $contents = [];
        
        // Mapear o histórico (Conversational Memory)
        foreach ($historico as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['text']]]
            ];
        }

        // Adicionar o prompt atual
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $prompt]]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => $contents,
            // Força a resposta em JSON
            'generationConfig' => [
                'response_mime_type' => 'application/json',
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        }

        Log::error("Erro na API do Gemini: " . $response->body());
        return '{}';
    }

    /**
     * Resposta de chat com inteligência contextual e RAG real
     */
    public function chat(string $pergunta, string $idioma = 'pt-BR', array $userLocation = [], array $historico = []): array
    {
        $scrubbedPergunta = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', '[EMAIL]', $pergunta);
        
        $cidade = $userLocation['cidade'] ?? $userLocation['city'] ?? 'Não especificada';
        $uf = $userLocation['uf'] ?? $userLocation['state'] ?? '';

        // RAG REAL: Buscando locais reais do nosso banco de dados
        // Para o hackathon, vamos pegar até 10 atrativos ativos
        $atrativosDb = Atrativo::where('status', 'ativo')
                               ->orWhere('status', '!=', 'inativo') // Fallback caso status seja nulo
                               ->take(10)
                               ->get(['id', 'nome', 'descricao', 'categoria_id', 'lat', 'lng']);
                               
        $ragContext = "";
        if ($atrativosDb->count() > 0) {
            $ragContext = "LOCAIS DISPONÍVEIS NO SISTEMA (Use SOMENTE estes locais para dar recomendações reais):\n";
            foreach ($atrativosDb as $atrativo) {
                $ragContext .= "- ID {$atrativo->id}: {$atrativo->nome} (Lat: {$atrativo->lat}, Lng: {$atrativo->lng}). Descrição: {$atrativo->descricao}\n";
            }
        }

        $prompt = "Você é um assistente virtual turístico local muito simpático e inteligente. 
A localização atual do usuário é: {$cidade} {$uf}. O idioma preferido é: {$idioma}.
O usuário vai continuar a conversa abaixo. Mantenha o contexto do que já foi falado.

{$ragContext}

INSTRUÇÃO RÍGIDA:
Você deve retornar EXATAMENTE UM JSON com os seguintes campos, e mais nada:
{
  \"resposta\": \"(Um texto amigável, em markdown, respondendo à pergunta do usuário e recomendando locais, mantendo o contexto se necessário)\",
  \"fontes\": [
     {\"id\": 999, \"nome\": \"Nome exato do local\", \"tipo\": \"atrativo\", \"cidade\": \"{$cidade}\"} // OBRIGATÓRIO: O 'id' e o 'nome' DEVEM ser EXATAMENTE os mesmos listados em 'LOCAIS DISPONÍVEIS NO SISTEMA'.
  ],
  \"cidade_detectada\": \"{$cidade}\"
}

Pergunta atual: '{$scrubbedPergunta}'";

        $jsonResponse = $this->callGemini($prompt, $historico);
        $dados = json_decode($jsonResponse, true);

        if (!$dados || !isset($dados['resposta'])) {
            $dados = [
                'resposta' => "Desculpe, tive um problema de conexão com meus servidores de IA. Tente novamente!",
                'fontes' => [],
                'cidade_detectada' => $cidade
            ];
        }

        // Audit Log
        AssistantLog::create([
            'pergunta' => $scrubbedPergunta,
            'resposta' => $dados['resposta'],
            'fontes' => $dados['fontes'] ?? [],
            'idioma' => $idioma,
        ]);

        return [
            'resposta' => $dados['resposta'],
            'fontes' => $dados['fontes'] ?? [],
            'cidade_detectada' => $dados['cidade_detectada'] ?? $cidade,
            'is_ia' => true
        ];
    }

    /**
     * Gerador de Roteiro Inteligente com RAG e Mapa Mock
     */
    public function gerarRoteiro(array $preferences): array
    {
        $cidade = $preferences['cidade'] ?? 'Sua Região';
        $tema = $preferences['tema'] ?? 'Turismo Geral';
        $duracao = $preferences['duracao_max'] ?? 240;
        $orcamento = $preferences['orcamento_max'] ?? 150.00;

        // RAG REAL para roteiros
        $atrativosDb = Atrativo::take(10)->get(['id', 'nome', 'descricao']);
        $ragContext = "";
        foreach ($atrativosDb as $atrativo) {
            $ragContext .= "- ID {$atrativo->id}: {$atrativo->nome}\n";
        }

        $prompt = "Crie um roteiro turístico para a cidade de {$cidade} focado no tema: {$tema}.
Duração máxima disponível: {$duracao} minutos. Orçamento máximo: R$ {$orcamento}.

{$ragContext}

INSTRUÇÃO RÍGIDA:
Você deve retornar EXATAMENTE UM JSON com os seguintes campos, e mais nada:
{
  \"titulo\": \"(Um título atrativo para o roteiro)\",
  \"cidade\": \"{$cidade}\",
  \"duracao\": {$duracao},
  \"orcamento\": {$orcamento},
  \"itens\": [
      {\"atrativo_id\": 999, \"ordem\": 1, \"tempo_estimado\": 60, \"nome\": \"(Nome da parada)\"} // OBRIGATÓRIO: O 'atrativo_id' DEVE ser o ID real correspondente ao local no banco de dados.
  ]
}";

        $jsonResponse = $this->callGemini($prompt);
        $dados = json_decode($jsonResponse, true);

        if (!$dados || !isset($dados['itens'])) {
            // Fallback
            $dados = [
                'titulo' => "Roteiro {$cidade}: {$tema}",
                'cidade' => $cidade,
                'duracao' => $duracao,
                'orcamento' => $orcamento,
                'itens' => [
                    ['atrativo_id' => 1, 'ordem' => 1, 'tempo_estimado' => 90, 'nome' => 'Erro na IA: Usando Roteiro Padrão']
                ]
            ];
        }

        $dados['is_ia'] = true;
        return $dados;
    }
}
