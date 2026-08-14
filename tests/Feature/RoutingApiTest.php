<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RoutingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_fails_when_less_than_two_points_provided(): void
    {
        $response = $this->getJson('/api/v1/routes/directions?coordinates=-34.8239,-7.1147');

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }

    public function test_can_calculate_route_via_osrm_mock(): void
    {
        Http::fake([
            'router.project-osrm.org/*' => Http::response([
                'code' => 'Ok',
                'routes' => [
                    [
                        'distance' => 12500, // 12.5 km
                        'duration' => 1200,  // 20 min
                        'geometry' => [
                            'type' => 'LineString',
                            'coordinates' => [
                                [-34.8239, -7.1147],
                                [-34.8000, -7.1300],
                                [-34.7877, -7.1597]
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = $this->getJson('/api/v1/routes/directions?coordinates=-34.8239,-7.1147;-34.7877,-7.1597&mode=driving');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('is_fallback', false)
                 ->assertJsonPath('distance_km', 12.5)
                 ->assertJsonPath('duration_minutes', 20)
                 ->assertJsonPath('geojson.type', 'LineString');
    }

    public function test_generates_resilient_fallback_when_osrm_fails(): void
    {
        Http::fake([
            'router.project-osrm.org/*' => Http::response([], 500),
        ]);

        $response = $this->getJson('/api/v1/routes/directions?coordinates=-34.8239,-7.1147;-34.7877,-7.1597&mode=driving');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('is_fallback', true)
                 ->assertJsonPath('geojson.type', 'LineString');

        $this->assertGreaterThan(0, $response->json('distance_km'));
        $this->assertGreaterThan(0, $response->json('duration_minutes'));
    }
}
