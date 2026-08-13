<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Atrativo;
use App\Models\Municipio;
use App\Models\Categoria;

class AtrativoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->municipio = Municipio::create([
            'nome' => 'Cidade Teste',
            'uf' => 'TS'
        ]);

        $this->categoria = Categoria::create([
            'nome' => 'Praia',
            'slug' => 'praia',
            'tipo' => 'atrativo'
        ]);
    }

    public function test_can_list_active_atrativos(): void
    {
        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Praia do Teste',
            'descricao' => 'Uma bela praia.',
            'status' => 'ativo'
        ]);

        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Praia Inativa',
            'descricao' => 'Esta não deve aparecer.',
            'status' => 'inativo'
        ]);

        $response = $this->getJson('/api/v1/atrativos');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.nome', 'Praia do Teste');
    }

    public function test_can_search_by_keyword(): void
    {
        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Montanha Alta',
            'descricao' => 'Muito alta.',
            'status' => 'ativo'
        ]);

        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Lagoa Azul',
            'descricao' => 'Muito bela.',
            'status' => 'ativo'
        ]);

        $response = $this->getJson('/api/v1/atrativos?q=Montanha');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.nome', 'Montanha Alta');
    }

    public function test_can_filter_by_accessibility(): void
    {
        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Museu Acessivel',
            'descricao' => 'Com rampas.',
            'status' => 'ativo',
            'acessibilidade' => ['cadeirante', 'cego']
        ]);

        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Museu Antigo',
            'descricao' => 'Sem rampas.',
            'status' => 'ativo',
            'acessibilidade' => ['surdo']
        ]);

        $response = $this->getJson('/api/v1/atrativos?acessivel_para=cadeirante');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.nome', 'Museu Acessivel');
    }
}
