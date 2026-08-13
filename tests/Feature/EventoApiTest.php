<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Evento;

class EventoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_eventos_with_filters(): void
    {
        Evento::create([
            'nome' => 'Show de Rock',
            'descricao' => 'Muito barulho',
            'inicio' => now()->addDays(2),
            'gratuito' => false,
            'status' => 'ativo'
        ]);

        Evento::create([
            'nome' => 'Feira Livre',
            'descricao' => 'Verduras',
            'inicio' => now()->addDays(5),
            'gratuito' => true,
            'status' => 'ativo'
        ]);

        Evento::create([
            'nome' => 'Evento Cancelado',
            'descricao' => 'Cancelado',
            'inicio' => now()->addDays(1),
            'gratuito' => true,
            'status' => 'cancelado'
        ]);

        // List all (ativos & cancelados if no filter, or whatever default API logic has)
        $responseAll = $this->getJson('/api/v1/eventos');
        $responseAll->assertStatus(200)->assertJsonCount(3, 'data');

        // Filter by gratuito
        $responseGratuito = $this->getJson('/api/v1/eventos?gratuito=true');
        $responseGratuito->assertStatus(200)->assertJsonCount(2, 'data');

        // Filter by status
        $responseCancelado = $this->getJson('/api/v1/eventos?status=cancelado');
        $responseCancelado->assertStatus(200)
                          ->assertJsonCount(1, 'data')
                          ->assertJsonPath('data.0.nome', 'Evento Cancelado');
    }
}
