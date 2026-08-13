<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UtilidadePublica;

class UtilidadePublicaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_active_utilidades(): void
    {
        UtilidadePublica::create([
            'nome' => 'Polícia',
            'telefone' => '190',
            'ordem' => 1,
            'ativo' => true
        ]);

        UtilidadePublica::create([
            'nome' => 'Inativo',
            'telefone' => '000',
            'ativo' => false
        ]);

        $response = $this->getJson('/api/v1/utilidades-publicas');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.nome', 'Polícia');
    }
}
