<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LgpdConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_save_consents_anonymously(): void
    {
        $response = $this->postJson('/api/v1/lgpd/consentimentos', [
            'gps' => true,
            'alertas' => false,
            'metricas' => true,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('consentimentos.alertas', false);
    }

    public function test_can_save_consents_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/lgpd/consentimentos', [
            'gps' => false,
            'alertas' => true,
            'metricas' => false,
        ]);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertIsArray($user->consentimentos);
        $this->assertFalse($user->consentimentos['gps']);
        $this->assertTrue($user->consentimentos['alertas']);
    }
}
