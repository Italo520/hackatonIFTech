<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Atrativo;

class OsmImportCommandTest extends TestCase
{
    use RefreshDatabase;

    protected Municipio $municipio;
    protected Categoria $categoriaCultura;
    protected Categoria $categoriaPraia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->municipio = Municipio::create([
            'nome' => 'João Pessoa',
            'uf' => 'PB',
        ]);

        $this->categoriaCultura = Categoria::create([
            'nome' => 'História & Cultura',
            'slug' => 'cultura',
            'tipo' => 'atrativo'
        ]);

        $this->categoriaPraia = Categoria::create([
            'nome' => 'Praias, Rios e Piscinas',
            'slug' => 'rios',
            'tipo' => 'atrativo'
        ]);
    }

    public function test_fails_when_municipio_not_found(): void
    {
        $this->artisan('turismo:import-osm', ['municipio_id' => 99999])
            ->expectsOutputToContain('não encontrado')
            ->assertExitCode(1);
    }

    public function test_can_import_osm_points_into_database_with_mock(): void
    {
        // Mock das chamadas HTTP para Nominatim e Overpass API
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([
                [
                    'lat' => '-7.1153',
                    'lon' => '-34.8641',
                    'display_name' => 'João Pessoa, PB, Brasil'
                ]
            ], 200),
            'overpass-api.de/*' => Http::response([
                'elements' => [
                    [
                        'type' => 'node',
                        'id' => 1001,
                        'lat' => -7.1155,
                        'lon' => -34.8864,
                        'tags' => [
                            'name' => 'Centro Cultural São Francisco',
                            'tourism' => 'museum',
                            'historic' => 'monument',
                            'wheelchair' => 'yes',
                            'opening_hours' => 'Mo-Su 09:00-17:00',
                            'addr:street' => 'Praça São Francisco',
                            'addr:suburb' => 'Centro'
                        ]
                    ],
                    [
                        'type' => 'node',
                        'id' => 1002,
                        'lat' => -7.1597,
                        'lon' => -34.7877,
                        'tags' => [
                            'name' => 'Piscinas dos Seixas',
                            'natural' => 'beach',
                            'wheelchair' => 'limited'
                        ]
                    ],
                    [
                        // Elemento sem nome - deve ser ignorado
                        'type' => 'node',
                        'id' => 1003,
                        'lat' => -7.1234,
                        'lon' => -34.5678,
                        'tags' => [
                            'tourism' => 'attraction'
                        ]
                    ]
                ]
            ], 200),
        ]);

        $this->artisan('turismo:import-osm', [
            'municipio_id' => $this->municipio->id,
            '--status' => 'ativo',
            '--radius' => 10000
        ])
        ->expectsOutputToContain('Relatório de Importação OSM')
        ->expectsOutputToContain('Novos Atrativos Importados: 2')
        ->assertExitCode(0);

        // Verifica inserções no banco de dados
        $this->assertDatabaseHas('atrativos', [
            'municipio_id' => $this->municipio->id,
            'nome' => 'Centro Cultural São Francisco',
            'status' => 'ativo',
            'categoria_id' => $this->categoriaCultura->id
        ]);

        $this->assertDatabaseHas('atrativos', [
            'municipio_id' => $this->municipio->id,
            'nome' => 'Piscinas dos Seixas',
            'status' => 'ativo',
            'categoria_id' => $this->categoriaPraia->id
        ]);

        $atrativo = Atrativo::where('nome', 'Centro Cultural São Francisco')->first();
        $this->assertContains('cadeirante', $atrativo->acessibilidade);
        $this->assertEquals(['padrao' => 'Mo-Su 09:00-17:00'], $atrativo->horarios);
    }

    public function test_prevents_duplicate_imports(): void
    {
        // Cria um atrativo existente
        Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoriaCultura->id,
            'nome' => 'Centro Cultural São Francisco',
            'descricao' => 'Descrição do monumento histórico.',
            'lat' => -7.1155,
            'lng' => -34.8864,
            'status' => 'ativo'
        ]);

        Http::fake([
            'overpass-api.de/*' => Http::response([
                'elements' => [
                    [
                        'type' => 'node',
                        'id' => 1001,
                        'lat' => -7.1155,
                        'lon' => -34.8864,
                        'tags' => [
                            'name' => 'Centro Cultural São Francisco',
                            'tourism' => 'museum'
                        ]
                    ]
                ]
            ], 200),
        ]);

        $this->artisan('turismo:import-osm', [
            'municipio_id' => $this->municipio->id
        ])
        ->expectsOutputToContain('Duplicados Ignorados: 1')
        ->assertExitCode(0);

        // Deve continuar existindo apenas 1 registro
        $this->assertEquals(1, Atrativo::where('nome', 'Centro Cultural São Francisco')->count());
    }
}
