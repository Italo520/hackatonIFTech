<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\AssistantLog;

class IAApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_chat_with_assistant_and_it_scrubs_pii(): void
    {
        $response = $this->post('/api/v1/ia/chat', [
            'pergunta' => 'Quais os melhores atrativos? Meu email é user@example.com',
            'idioma' => 'pt-BR'
        ]);

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/event-stream; charset=utf-8');

        $this->assertDatabaseHas('assistant_logs', [
            'pergunta' => 'Quais os melhores atrativos? Meu email é [EMAIL]',
        ]);
    }

    public function test_can_generate_roteiro(): void
    {
        $atrativo = \App\Models\Atrativo::factory()->create();

        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'steps' => [['type' => 'model_output', 'content' => [['text' => json_encode([
                    'titulo' => 'Roteiro IA: Aventura',
                    'cidade' => 'IA',
                    'duracao' => 180,
                    'orcamento' => 100,
                    'itens' => [['atrativo_id' => $atrativo->id, 'ordem' => 1, 'tempo_estimado' => 60]]
                ])]]]]
            ], 200)
        ]);

        $response = $this->postJson('/api/v1/ia/roteiro', [
            'tema' => 'Aventura',
            'duracao_max' => 180,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('is_ia', true)
                 ->assertJsonPath('titulo', 'Roteiro IA: Aventura');
    }
}
