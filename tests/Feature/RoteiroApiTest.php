<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Roteiro;

class RoteiroApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_and_filter_roteiros(): void
    {
        Roteiro::create([
            'titulo' => 'Roteiro Aventura',
            'tema' => 'Aventura',
            'duracao' => 120,
            'dificuldade' => 'Alta',
            'origem' => 'oficial',
            'publico' => true
        ]);

        Roteiro::create([
            'titulo' => 'Roteiro Leve',
            'tema' => 'Familia',
            'duracao' => 60,
            'dificuldade' => 'Baixa',
            'origem' => 'oficial',
            'publico' => true
        ]);

        Roteiro::create([
            'titulo' => 'Roteiro Privado',
            'tema' => 'Secreto',
            'publico' => false
        ]);

        $responseAll = $this->getJson('/api/v1/roteiros');
        $responseAll->assertStatus(200)->assertJsonCount(2, 'data');

        $responseFilter = $this->getJson('/api/v1/roteiros?duracao_max=90&dificuldade=Baixa');
        $responseFilter->assertStatus(200)
                       ->assertJsonCount(1, 'data')
                       ->assertJsonPath('data.0.titulo', 'Roteiro Leve');
    }
}
