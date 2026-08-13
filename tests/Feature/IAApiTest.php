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
        $response = $this->postJson('/api/v1/ia/chat', [
            'pergunta' => 'Quais os melhores atrativos? Meu email é user@example.com',
            'idioma' => 'pt-BR'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('is_ia', true)
                 ->assertJsonStructure(['resposta', 'fontes']);

        $this->assertDatabaseHas('assistant_logs', [
            'pergunta' => 'Quais os melhores atrativos? Meu email é [EMAIL]',
        ]);
    }

    public function test_can_generate_roteiro(): void
    {
        $response = $this->postJson('/api/v1/ia/roteiro', [
            'tema' => 'Aventura',
            'duracao_max' => 180,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('is_ia', true)
                 ->assertJsonPath('titulo', 'Roteiro IA: Aventura');
    }
}
