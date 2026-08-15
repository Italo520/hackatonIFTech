<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\AssistantLog;

class IAApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Gemini interactions API response
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'steps' => [
                    [
                        'type' => 'model_output',
                        'content' => [
                            ['text' => json_encode([
                                'resposta' => 'Recomendo visitar a Praia de Tambaú e o Farol do Cabo Branco!',
                                'fontes' => [['id' => 1, 'nome' => 'Praia de Tambaú', 'tipo' => 'atrativo', 'cidade' => 'João Pessoa']],
                                'cidade_detectada' => 'João Pessoa',
                                'titulo' => 'Roteiro IA: Aventura',
                                'itens' => [
                                    ['atrativo_id' => 1, 'ordem' => 1, 'tempo_estimado' => 90, 'nome' => 'Praia de Tambaú']
                                ]
                            ])]
                        ]
                    ]
                ]
            ], 200)
        ]);
    }

    public function test_can_chat_with_assistant_and_it_scrubs_pii(): void
    {
        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
            ->post('/api/v1/ia/chat', [
                'pergunta' => 'Quais os melhores atrativos? Meu email é user@example.com',
                'idioma' => 'pt-BR'
            ]);

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/event-stream; charset=utf-8');

        $this->assertDatabaseHas('assistant_logs', [
            'pergunta' => 'Quais os melhores atrativos? Meu email é [EMAIL]',
        ]);
    }

    public function test_can_generate_roteiro_considering_user_location_as_start_point(): void
    {
        $atrativo = \App\Models\Atrativo::factory()->create();

        $userLat = -7.1153;
        $userLng = -34.8641;
        $userCity = 'João Pessoa';

        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'steps' => [['type' => 'model_output', 'content' => [['text' => json_encode([
                    'titulo' => 'Roteiro IA: Aventura',
                    'cidade' => $userCity,
                    'duracao' => 180,
                    'orcamento' => 100,
                    'itens' => [['atrativo_id' => $atrativo->id, 'ordem' => 1, 'tempo_estimado' => 60]]
                ])]]]]
            ], 200)
        ]);

        $response = $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class)
            ->postJson('/api/v1/ia/roteiro', [
                'tema' => 'Aventura',
                'duracao_max' => 180,
                'cidade' => $userCity,
                'lat' => $userLat,
                'lng' => $userLng,
            ]);

        $response->assertStatus(200)
                 ->assertJsonPath('is_ia', true)
                 ->assertJsonPath('origem_lat', $userLat)
                 ->assertJsonPath('origem_lng', $userLng)
                 ->assertJsonPath('ponto_partida.is_partida', true)
                 ->assertJsonPath('ponto_partida.lat', $userLat);

        $this->assertDatabaseHas('roteiros', [
            'origem' => 'ia',
        ]);
    }
}

