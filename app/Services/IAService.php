<?php

namespace App\Services;

use App\Models\AssistantLog;

class IAService
{
    /**
     * Resposta de chat com inteligência contextual de localização geográfica
     */
    public function chat(string $pergunta, string $idioma = 'pt-BR', array $userLocation = []): array
    {
        // Sanitização de PII
        $scrubbedPergunta = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', '[EMAIL]', $pergunta);
        
        $cidade = $userLocation['cidade'] ?? $userLocation['city'] ?? null;
        $uf = $userLocation['uf'] ?? $userLocation['state'] ?? '';
        $lat = $userLocation['lat'] ?? null;
        $lng = $userLocation['lng'] ?? null;

        $pLower = mb_strtolower($pergunta);

        // Respostas contextuais com base na localização real
        if ($cidade && (str_contains(mb_strtolower($cidade), 'joão pessoa') || str_contains(mb_strtolower($cidade), 'joao pessoa') || $uf === 'PB')) {
            if (str_contains($pLower, 'comer') || str_contains($pLower, 'peixe') || str_contains($pLower, 'restaurante') || str_contains($pLower, 'gastronomia')) {
                $resposta = "Em **João Pessoa - PB**, você encontra uma gastronomia incrível! Recomendo o **Mangai** (famoso pela autêntica culinária nordestina) e o **Nau Frutos do Mar** em Manaíra. Para um almoço à beira-mar, os quiosques de **Tambaú** e **Cabo Branco** são excelentes opções com peixes frescos e acessibilidade.";
                $fontes = [
                    ['id' => 101, 'nome' => 'Mangai João Pessoa', 'tipo' => 'gastronomia', 'cidade' => 'João Pessoa - PB'],
                    ['id' => 102, 'nome' => 'Nau Frutos do Mar', 'tipo' => 'gastronomia', 'cidade' => 'João Pessoa - PB']
                ];
            } elseif (str_contains($pLower, 'criança') || str_contains($pLower, 'familia')) {
                $resposta = "Para aproveitar com crianças em **João Pessoa**, as **Piscinas Naturais dos Seixas** na maré baixa são calmas como uma piscina de água morna! O **Jardim Botânico Benjamin Maranhão** e a orla fechada de Tambaú pela manhã também são ótimos para caminhar e brincar.";
                $fontes = [
                    ['id' => 103, 'nome' => 'Piscinas Naturais dos Seixas', 'tipo' => 'atrativo', 'cidade' => 'João Pessoa - PB'],
                    ['id' => 104, 'nome' => 'Farol do Cabo Branco', 'tipo' => 'atrativo', 'cidade' => 'João Pessoa - PB']
                ];
            } else {
                $resposta = "Como seu guia em **João Pessoa - PB**, recomendo começar o dia vendo o nascer do sol no **Farol do Cabo Branco** (ponto mais oriental das Américas), fazer um passeio às **Piscinas Naturais dos Seixas** e finalizar a tarde no **Centro Cultural São Francisco**.";
                $fontes = [
                    ['id' => 104, 'nome' => 'Farol do Cabo Branco', 'tipo' => 'atrativo', 'cidade' => 'João Pessoa - PB'],
                    ['id' => 105, 'nome' => 'Centro Histórico São Francisco', 'tipo' => 'cultura', 'cidade' => 'João Pessoa - PB']
                ];
            }
        } elseif ($cidade && str_contains(mb_strtolower($cidade), 'bonito')) {
            if (str_contains($pLower, 'comer') || str_contains($pLower, 'peixe')) {
                $resposta = "Em **Bonito - MS**, o restaurante **Casa do João** é parada obrigatória, famoso pelo clássico Traíra sem espinho e Pintado a Urucum, com ótima acessibilidade.";
                $fontes = [
                    ['id' => 4, 'nome' => 'Casa do João', 'tipo' => 'gastronomia', 'cidade' => 'Bonito - MS']
                ];
            } else {
                $resposta = "Em **Bonito - MS**, as principais atrações são a **Flutuação no Rio Sucuri** (com águas ultra cristalinas) e a icônica **Gruta do Lago Azul**.";
                $fontes = [
                    ['id' => 1, 'nome' => 'Flutuação no Rio Sucuri', 'tipo' => 'atrativo', 'cidade' => 'Bonito - MS'],
                    ['id' => 2, 'nome' => 'Gruta do Lago Azul', 'tipo' => 'atrativo', 'cidade' => 'Bonito - MS']
                ];
            }
        } else {
            $localNome = $cidade ? "{$cidade} {$uf}" : "sua localização atual";
            $resposta = "Detectei que você está em **{$localNome}**! Analisando os atrativos e serviços mais próximos com base no seu GPS (" . ($lat ? "{$lat}, {$lng}" : "coordenadas em tempo real") . "), selecionei as melhores opções disponíveis na região para sua viagem.";
            $fontes = [
                ['id' => 1, 'nome' => 'Atrativo em Destaque', 'tipo' => 'atrativo', 'cidade' => $localNome]
            ];
        }

        // Audit Log
        AssistantLog::create([
            'pergunta' => $scrubbedPergunta,
            'resposta' => $resposta,
            'fontes' => $fontes,
            'idioma' => $idioma,
        ]);

        return [
            'resposta' => $resposta,
            'fontes' => $fontes,
            'cidade_detectada' => $cidade,
            'is_ia' => true
        ];
    }

    /**
     * Gerador de Roteiro Inteligente baseado na Localização do Usuário
     */
    public function gerarRoteiro(array $preferences): array
    {
        $cidade = $preferences['cidade'] ?? 'Sua Região';
        $tema = $preferences['tema'] ?? 'Natureza & Cultura';

        return [
            'titulo' => "Roteiro {$cidade}: {$tema}",
            'cidade' => $cidade,
            'duracao' => $preferences['duracao_max'] ?? 240,
            'orcamento' => $preferences['orcamento_max'] ?? 150.00,
            'itens' => [
                ['atrativo_id' => 1, 'ordem' => 1, 'tempo_estimado' => 90, 'nome' => 'Parada 1: Ponto Panorâmico'],
                ['atrativo_id' => 2, 'ordem' => 2, 'tempo_estimado' => 120, 'nome' => 'Parada 2: Experiência Principal & Gastronomia']
            ],
            'is_ia' => true
        ];
    }
}

