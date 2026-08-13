<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_sync_avaliacoes(): void
    {
        $payload = [
            'avaliacoes' => [
                [
                    'entidade_id' => 1,
                    'entidade_type' => 'App\Models\Atrativo',
                    'nota' => 5,
                    'comentario' => 'Ótimo lugar offline'
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/sync/avaliacoes', $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('count', 1);

        $this->assertDatabaseHas('avaliacaos', [
            'entidade_id' => 1,
            'nota' => 5,
            'origem_offline' => true
        ]);
    }
}
