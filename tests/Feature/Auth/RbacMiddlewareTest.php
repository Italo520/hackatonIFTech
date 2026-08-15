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
}
