<?php

namespace Tests\Feature\Web;

use App\Models\Alerta;
use App\Models\Atrativo;
use App\Models\Categoria;
use App\Models\Evento;
use App\Models\Municipio;
use App\Models\Roteiro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OwenIt\Auditing\Models\Audit;
use Tests\TestCase;

class AdminCrudAndAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Municipio $municipio;
    protected Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'admin@governo.pb.gov.br',
        ]);

        $this->municipio = Municipio::create([
            'nome' => 'João Pessoa',
            'uf' => 'PB',
            'lat' => -7.1153,
            'lng' => -34.8641,
        ]);

        $this->categoria = Categoria::create([
            'nome' => 'Praias',
            'slug' => 'praias',
        ]);
    }

    public function test_admin_can_create_update_toggle_and_delete_atrativo_with_audit(): void
    {
        // 1. Criar Atrativo
        $response = $this->actingAs($this->admin)->post(route('admin.atrativos.store'), [
            'nome' => 'Farol do Cabo Branco',
            'categoria_id' => $this->categoria->id,
            'municipio_id' => $this->municipio->id,
            'descricao' => 'Ponto mais oriental das Américas.',
            'endereco' => 'Praia de Cabo Branco',
            'lat' => -7.1485,
            'lng' => -34.7967,
            'tempo_medio_visita' => 60,
            'status' => 'ativo',
        ]);

        $response->assertRedirect(route('admin.atrativos.index'));
        $this->assertDatabaseHas('atrativos', [
            'nome' => 'Farol do Cabo Branco',
            'status' => 'ativo',
        ]);

        $atrativo = Atrativo::where('nome', 'Farol do Cabo Branco')->first();
        $this->assertNotNull($atrativo);

        // 2. Atualizar Atrativo
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.atrativos.update', $atrativo->id), [
            'nome' => 'Farol do Cabo Branco - Atualizado',
            'categoria_id' => $this->categoria->id,
            'municipio_id' => $this->municipio->id,
            'descricao' => 'Descrição detalhada e atualizada.',
            'endereco' => 'Praia de Cabo Branco, SN',
            'lat' => -7.1485,
            'lng' => -34.7967,
            'status' => 'ativo',
        ]);

        $updateResponse->assertRedirect(route('admin.atrativos.index'));
        $this->assertDatabaseHas('atrativos', [
            'id' => $atrativo->id,
            'nome' => 'Farol do Cabo Branco - Atualizado',
        ]);

        // 3. Toggle Status
        $toggleResponse = $this->actingAs($this->admin)->patch(route('admin.atrativos.toggle-status', $atrativo->id));
        $toggleResponse->assertRedirect(route('admin.atrativos.index'));
        $atrativo->refresh();
        $this->assertEquals('inativo', $atrativo->status);

        // 4. Verificar Auditoria
        $auditCount = Audit::where('auditable_type', Atrativo::class)
            ->where('auditable_id', $atrativo->id)
            ->count();
        $this->assertGreaterThan(0, $auditCount);

        // 5. Excluir Atrativo
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.atrativos.destroy', $atrativo->id));
        $deleteResponse->assertRedirect(route('admin.atrativos.index'));
        $this->assertDatabaseMissing('atrativos', ['id' => $atrativo->id]);
    }

    public function test_admin_can_create_and_manage_eventos(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.eventos.store'), [
            'nome' => 'Festival Estação Nordeste',
            'inicio' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'fim' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'local' => 'Busto de Tamandaré',
            'organizador' => 'Prefeitura de João Pessoa',
            'descricao' => 'Shows e gastronomia regional.',
            'gratuito' => '1',
            'status' => 'ativo',
        ]);

        $response->assertRedirect(route('admin.eventos.index'));
        $this->assertDatabaseHas('eventos', [
            'nome' => 'Festival Estação Nordeste',
            'gratuito' => 1,
        ]);

        $evento = Evento::where('nome', 'Festival Estação Nordeste')->first();

        // Excluir
        $delResponse = $this->actingAs($this->admin)->delete(route('admin.eventos.destroy', $evento->id));
        $delResponse->assertRedirect(route('admin.eventos.index'));
        $this->assertDatabaseMissing('eventos', ['id' => $evento->id]);
    }

    public function test_admin_can_create_and_manage_roteiros_with_itens(): void
    {
        $at1 = Atrativo::create([
            'nome' => 'Ponto A',
            'categoria_id' => $this->categoria->id,
            'municipio_id' => $this->municipio->id,
            'descricao' => 'Ponto A desc',
            'lat' => -7.1,
            'lng' => -34.8,
            'status' => 'ativo',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.roteiros.store'), [
            'titulo' => 'Roteiro Sol e Mar',
            'tema' => 'Praias & Natureza',
            'duracao' => 4,
            'dificuldade' => 'facil',
            'orcamento' => 80.00,
            'perfil' => 'Família',
            'transporte' => 'Carro',
            'atrativos' => [$at1->id],
        ]);

        $response->assertRedirect(route('admin.roteiros.index'));
        $this->assertDatabaseHas('roteiros', [
            'titulo' => 'Roteiro Sol e Mar',
        ]);

        $roteiro = Roteiro::where('titulo', 'Roteiro Sol e Mar')->first();
        $this->assertDatabaseHas('roteiro_itens', [
            'roteiro_id' => $roteiro->id,
            'atrativo_id' => $at1->id,
        ]);

        // Excluir
        $delResponse = $this->actingAs($this->admin)->delete(route('admin.roteiros.destroy', $roteiro->id));
        $delResponse->assertRedirect(route('admin.roteiros.index'));
        $this->assertDatabaseMissing('roteiros', ['id' => $roteiro->id]);
    }

    public function test_civil_defense_alert_is_displayed_on_pwa_home_and_map(): void
    {
        $alerta = Alerta::create([
            'titulo' => 'Aviso de Ressaca Marítima',
            'corpo' => 'Ondas de até 2.5 metros na orla de Cabo Branco e Tambaú.',
            'urgencia' => 'urgente',
            'segmentacao' => ['orla', 'praias'],
        ]);

        // Visualizar na Home do PWA
        $responseHome = $this->get('/');
        $responseHome->assertOk();
        $responseHome->assertSee('Aviso de Ressaca Marítima');
        $responseHome->assertSee('Defesa Civil');

        // Visualizar no Mapa do PWA
        $responseMapa = $this->get('/mapa');
        $responseMapa->assertOk();
        $responseMapa->assertSee('Aviso de Ressaca Marítima');
    }
}
