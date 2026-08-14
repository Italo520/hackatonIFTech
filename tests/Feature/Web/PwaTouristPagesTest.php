<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\Alerta;
use App\Models\Roteiro;
use App\Models\QrCode as QrCodeModel;

class PwaTouristPagesTest extends TestCase
{
    use RefreshDatabase;

    protected Municipio $municipio;
    protected Categoria $categoria;
    protected Atrativo $atrativo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->municipio = Municipio::create([
            'nome' => 'João Pessoa',
            'uf' => 'PB'
        ]);

        $this->categoria = Categoria::create([
            'nome' => 'Praias & Rios',
            'slug' => 'praias-e-rios',
            'tipo' => 'atrativo',
            'icone' => 'bi-water'
        ]);

        $this->atrativo = Atrativo::create([
            'municipio_id' => $this->municipio->id,
            'categoria_id' => $this->categoria->id,
            'nome' => 'Praia de Tambaú',
            'descricao' => 'Uma das praias mais famosas da capital paraibana.',
            'endereco' => 'Av. Almirante Tamandaré, s/n',
            'lat' => -7.1147,
            'lng' => -34.8239,
            'tempo_medio_visita' => 120,
            'status' => 'ativo',
            'horarios' => ['seg' => '08:00 - 18:00', 'ter' => '08:00 - 18:00'],
            'acessibilidade' => ['cadeirante', 'libras']
        ]);
    }

    /**
     * 1. Página Inicial / Home do Turista (GET /)
     */
    public function test_pwa_home_page_loads_successfully_with_components(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bom dia, Turista!');
        $response->assertSee('Roteiros Recomendados');
        $response->assertSee('Explorar por Categoria');
        $response->assertSee('Turismo Seguro');
    }

    /**
     * 2. Home exibindo Alertas de Defesa Civil com o componente Blade
     */
    public function test_pwa_home_page_displays_civil_defense_alerts_when_available(): void
    {
        Alerta::create([
            'municipio_id' => $this->municipio->id,
            'titulo' => 'Alerta de Maré Alta na Orla',
            'corpo' => 'Evite aproximação dos arrecifes durante a preamar.',
            'urgencia' => 'aviso',
            'responsavel' => 'Defesa Civil JP',
            'contato_emergencia' => '199',
            'valido_ate' => now()->addHours(12),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Alerta de Maré Alta na Orla');
        $response->assertSee('Defesa Civil JP');
        $response->assertSee('199');
    }

    /**
     * 3. Catálogo e Explorar (GET /explorar)
     */
    public function test_pwa_explorar_page_loads_with_categories_and_attractions(): void
    {
        $response = $this->get('/explorar');

        $response->assertStatus(200);
        $response->assertSee('Busca Inteligente');
        $response->assertSee('Principais Lugares');
        $response->assertSee('Praia de Tambaú');
    }

    /**
     * 4. Detalhes do Atrativo (GET /atrativo/{id})
     */
    public function test_pwa_atrativo_detail_page_loads_with_valid_model(): void
    {
        $response = $this->get('/atrativo/' . $this->atrativo->id);

        $response->assertStatus(200);
        $response->assertSee('Praia de Tambaú');
        $response->assertSee('Uma das praias mais famosas da capital paraibana.');
        $response->assertSee('Guia por Voz & Audiodescrição', false);
        $response->assertSee('Como deseja chegar?');
        $response->assertSee('Adicionar ao Roteiro');
    }

    /**
     * 5. Detalhe do Atrativo inexistente deve retornar 404
     */
    public function test_pwa_atrativo_detail_returns_404_for_invalid_id(): void
    {
        $response = $this->get('/atrativo/999999');

        $response->assertStatus(404);
    }

    /**
     * 6. Agenda de Eventos (GET /eventos)
     */
    public function test_pwa_eventos_page_loads_successfully(): void
    {
        Evento::create([
            'municipio_id' => $this->municipio->id,
            'nome' => 'Festival de Frutos do Mar',
            'descricao' => 'Gastronomia típica na praia.',
            'inicio' => now()->addDays(2),
            'fim' => now()->addDays(4),
            'gratuito' => true,
            'status' => 'ativo'
        ]);

        $response = $this->get('/eventos');

        $response->assertStatus(200);
        $response->assertSee('Agenda de Eventos');
        $response->assertSee('Filtrar Eventos');
    }

    /**
     * 7. Mapa Turístico Interativo (GET /mapa)
     */
    public function test_pwa_mapa_page_loads_with_leaflet_support(): void
    {
        $response = $this->get('/mapa');

        $response->assertStatus(200);
        $response->assertSee('pwa-map');
        $response->assertSee('Sua Localização Real');
    }

    /**
     * 8. Catálogo de Roteiros (GET /roteiros)
     */
    public function test_pwa_roteiros_catalog_page_loads_successfully(): void
    {
        $response = $this->get('/roteiros');

        $response->assertStatus(200);
        $response->assertSee('Roteiros Prontos');
        $response->assertSee('Criar com IA');
    }

    /**
     * 9. Detalhe do Roteiro (GET /roteiro/{id}) - Suporte a rotas estáticas e de banco
     */
    public function test_pwa_roteiro_detail_page_loads_for_demo_and_db_routes(): void
    {
        // Roteiro demo estático 101
        $response = $this->get('/roteiro/101');
        $response->assertStatus(200);
        $response->assertSee('Piscinas do Seixas');
        $response->assertSee('Farol do Cabo Branco');

        // Roteiro cadastrado dinamicamente no banco de dados
        $roteiroDb = Roteiro::create([
            'municipio_id' => $this->municipio->id,
            'titulo' => 'Roteiro Histórico Exclusivo',
            'descricao' => 'Caminhada pelas igrejas históricas.',
            'tema' => 'História',
            'perfil' => 'Cultura',
            'duracao_estimada' => 4,
            'orcamento_estimado' => 50.00,
            'dificuldade' => 'facil',
            'status' => 'ativo'
        ]);

        $responseDb = $this->get('/roteiro/' . $roteiroDb->id);
        $responseDb->assertStatus(200);
        $responseDb->assertSee('Roteiro Histórico Exclusivo');
    }

    /**
     * 10. Assistente IA de Viagem (GET /ia) para Visitantes e Usuários Autenticados
     */
    public function test_pwa_ia_page_renders_for_guest_and_auth_users(): void
    {
        // Como Visitante (Guest)
        $guestResponse = $this->get('/ia');
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Assistente de Viagem IA');
        $guestResponse->assertSee('Fazer Login');

        // Como Turista Autenticado
        $user = User::factory()->create([
            'role' => 'turista',
            'name' => 'Viajante Turista'
        ]);

        $authResponse = $this->actingAs($user)->get('/ia');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Assistente de Viagem IA');
        $authResponse->assertSee('ia-chat-form');
    }

    /**
     * 11. Telefones Úteis e Acessibilidade (GET /utilidade)
     */
    public function test_pwa_utilidade_page_lists_emergency_numbers_and_accessibility(): void
    {
        $response = $this->get('/utilidade');

        $response->assertStatus(200);
        $response->assertSee('Utilidade & Acessibilidade', false);
        $response->assertSee('190');
        $response->assertSee('192');
        $response->assertSee('193');
        $response->assertSee('199');
        $response->assertSee('Privacidade & LGPD', false);
    }

    /**
     * 12. Privacidade e LGPD (GET /privacidade)
     */
    public function test_pwa_privacidade_page_renders_lgpd_content(): void
    {
        $response = $this->get('/privacidade');

        $response->assertStatus(200);
        $response->assertSee('Privacidade & LGPD', false);
        $response->assertSee('Privacidade por Design (LGPD)');
        $response->assertSee('Exportar Meus Dados (JSON)');
    }

    /**
     * 13. Resolução de Totem / Placa Física QR Code (GET /qr/{hash})
     */
    public function test_qr_code_resolution_redirects_correctly(): void
    {
        $qr = QrCodeModel::create([
            'atrativo_id' => $this->atrativo->id,
            'hash_code' => 'totem_tambau_01',
            'scans' => 0
        ]);

        $response = $this->get('/qr/totem_tambau_01');

        $response->assertRedirect(route('pwa.atrativo', ['id' => $this->atrativo->id]));
        $this->assertEquals(1, $qr->fresh()->scans);
    }
}
