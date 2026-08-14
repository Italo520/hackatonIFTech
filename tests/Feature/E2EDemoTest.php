<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\Prestador;

class E2EDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_flow_1_turista_busca_linguagem_natural(): void
    {
        // Setup mock data
        $response = $this->post('/api/v1/ia/chat', [
            'pergunta' => 'roteiro gratuito em família',
            'idioma' => 'pt-BR'
        ]);

        $response->assertStatus(200)->assertHeader('Content-Type', 'text/event-stream; charset=utf-8');
    }

    public function test_flow_2_turista_gera_roteiro_ia_baixa_offline(): void
    {
        $responseGen = $this->postJson('/api/v1/ia/roteiro', [
            'tema' => 'Aventura',
            'duracao_max' => 120
        ]);
        $responseGen->assertStatus(200);

        // Assume ID is returned
        $roteiro = \App\Models\Roteiro::create(['titulo' => 'Teste', 'origem' => 'ia']);
        $responseExport = $this->getJson('/api/v1/roteiros/' . $roteiro->id . '/export');
        $responseExport->assertStatus(200)->assertJsonStructure(['roteiro', 'tiles_bbox']);
    }

    public function test_flow_3_turista_escaneia_qr(): void
    {
        $atrativo = \App\Models\Atrativo::factory()->create();
        $qr = \App\Models\QrCode::create(['hash_code' => 'DEMOQR', 'atrativo_id' => $atrativo->id]);
        $response = $this->getJson('/api/v1/qr/DEMOQR');
        $response->assertStatus(200);
        $this->assertEquals(1, $qr->fresh()->scans);
    }

    public function test_flow_4_empreendedor_cadastro_validacao(): void
    {
        $empreendedor = User::factory()->create(['role' => 'empreendedor']);
        $admin = User::factory()->create(['role' => 'gestor_cadastros']);

        $responseCad = $this->actingAs($empreendedor)->post('/parceiro/cadastro', [
            'tipo' => 'hospedagem',
            'nome_negocio' => 'Pousada',
            'documento' => 'doc.pdf'
        ]);
        $responseCad->assertRedirect();
        
        $p = Prestador::first();
        
        $responseApprove = $this->actingAs($admin)->put('/admin/prestadores/' . $p->id, [
            'status' => 'aprovado'
        ]);
        $responseApprove->assertRedirect();
        $this->assertTrue($p->fresh()->selo_validado);
    }

    public function test_flow_5_alerta_heatmap_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'prefeito']);
        $this->actingAs($admin);

        $this->post('/admin/alertas', [
            'titulo' => 'Aviso', 'corpo' => 'Teste', 'urgencia' => 'info'
        ])->assertRedirect();

        $this->getJson('/admin/heatmap-data')->assertStatus(200);
        $this->get('/admin')->assertStatus(200);
        
        // Export 
        $this->get('/admin/relatorios/exportar')->assertStatus(200);
    }
}
