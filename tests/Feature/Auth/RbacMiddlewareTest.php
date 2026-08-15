<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_tourist_cannot_access_admin_dashboard(): void
    {
        $tourist = User::factory()->create(['role' => 'turista']);

        $response = $this->actingAs($tourist)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_gestor_conteudo_can_access_admin_dashboard(): void
    {
        $gestor = User::factory()->create(['role' => 'gestor_conteudo']);

        $response = $this->actingAs($gestor)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_super_admin_can_access_all_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $responsePrestadores = $this->actingAs($admin)->get('/admin/prestadores');
        $responsePrestadores->assertStatus(200);
    }

    public function test_empreendedor_can_access_partner_panel(): void
    {
        $partner = User::factory()->create(['role' => 'empreendedor']);

        $response = $this->actingAs($partner)->get('/parceiro/painel');
        $response->assertStatus(200);
    }

    public function test_tourist_cannot_access_partner_panel(): void
    {
        $tourist = User::factory()->create(['role' => 'turista']);

        $response = $this->actingAs($tourist)->get('/parceiro/painel');
        $response->assertStatus(403);
    }

    public function test_prefeito_permissions(): void
    {
        $prefeito = User::factory()->create(['role' => 'prefeito']);

        // Acessos permitidos
        $this->actingAs($prefeito)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($prefeito)->get('/admin/alertas')->assertStatus(200);
        $this->actingAs($prefeito)->get('/admin/relatorios/exportar')->assertStatus(200);

        // Acessos negados
        $this->actingAs($prefeito)->get('/admin/atrativos')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/eventos')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/roteiros')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/prestadores')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/auditoria')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/documentacao')->assertStatus(403);
        $this->actingAs($prefeito)->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_secretario_permissions(): void
    {
        $secretario = User::factory()->create(['role' => 'secretario']);

        // Acessos permitidos
        $this->actingAs($secretario)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/atrativos')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/eventos')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/roteiros')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/prestadores')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/alertas')->assertStatus(200);
        $this->actingAs($secretario)->get('/admin/relatorios/exportar')->assertStatus(200);

        // Acessos negados
        $this->actingAs($secretario)->get('/admin/auditoria')->assertStatus(403);
        $this->actingAs($secretario)->get('/admin/documentacao')->assertStatus(403);
        $this->actingAs($secretario)->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_gestor_conteudo_permissions(): void
    {
        $gestor = User::factory()->create(['role' => 'gestor_conteudo']);

        // Acessos permitidos
        $this->actingAs($gestor)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($gestor)->get('/admin/atrativos')->assertStatus(200);
        $this->actingAs($gestor)->get('/admin/eventos')->assertStatus(200);
        $this->actingAs($gestor)->get('/admin/roteiros')->assertStatus(200);

        // Acessos negados
        $this->actingAs($gestor)->get('/admin/prestadores')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/alertas')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/auditoria')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/documentacao')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_gestor_cadastros_permissions(): void
    {
        $gestor = User::factory()->create(['role' => 'gestor_cadastros']);

        // Acessos permitidos
        $this->actingAs($gestor)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($gestor)->get('/admin/prestadores')->assertStatus(200);

        // Acessos negados
        $this->actingAs($gestor)->get('/admin/atrativos')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/eventos')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/roteiros')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/alertas')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/auditoria')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/documentacao')->assertStatus(403);
        $this->actingAs($gestor)->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_super_admin_can_access_usuarios_management(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/usuarios');
        $response->assertStatus(200);
        $response->assertSee('Gestão de Usuários');
    }
}
