<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Prestador;
use App\Models\Ocorrencia;
use App\Models\Alerta;

class Sprint3Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_prestador_cadastro(): void
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->post('/parceiro/cadastro', [
            'tipo' => 'hospedagem',
            'nome_negocio' => 'Pousada Teste',
            'documento' => 'url_para_doc.pdf'
        ]);

        $response->assertRedirect('/parceiro/painel');
        
        $this->assertDatabaseHas('prestadores', [
            'tipo' => 'hospedagem',
            'status' => 'pendente'
        ]);
    }

    public function test_can_create_ocorrencia(): void
    {
        $response = $this->postJson('/api/v1/ocorrencias', [
            'tipo' => 'buraco',
            'gravidade' => 'media',
            'descricao' => 'Buraco na rua principal'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'protocolo']);

        $this->assertDatabaseHas('ocorrencias', [
            'tipo' => 'buraco',
            'gravidade' => 'media'
        ]);
    }

    public function test_admin_can_create_alerta(): void
    {
        $response = $this->post('/admin/alertas', [
            'titulo' => 'Alerta de Chuva',
            'corpo' => 'Cuidado com enchentes',
            'urgencia' => 'aviso'
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('alertas', [
            'titulo' => 'Alerta de Chuva',
            'urgencia' => 'aviso'
        ]);
    }
}
