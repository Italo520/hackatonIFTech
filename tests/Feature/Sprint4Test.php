<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AnalyticEvent;

class Sprint4Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_analytic_event(): void
    {
        $response = $this->postJson('/api/v1/analytics', [
            'tipo' => 'page_view',
            'metadados' => ['url' => '/home']
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('analytic_events', ['tipo' => 'page_view']);
    }

    public function test_admin_dashboard_heatmap_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'secretario']);
        $this->actingAs($admin);

        $response = $this->getJson('/admin/heatmap-data');
        $response->assertStatus(200);
    }

    public function test_admin_can_export_report(): void
    {
        $admin = User::factory()->create(['role' => 'secretario']);
        $this->actingAs($admin);

        AnalyticEvent::create(['tipo' => 'test_event']);

        $response = $this->get('/admin/relatorios/exportar');
        $response->assertStatus(200)
                 ->assertHeader('Content-type', 'text/csv');
                 
        $this->assertStringContainsString('DISCLAIMER', $response->streamedContent());
    }
}
