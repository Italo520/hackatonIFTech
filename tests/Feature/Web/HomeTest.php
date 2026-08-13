<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_loads_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bom dia, Turista!');
        $response->assertSee('Roteiros Recomendados');
    }
    
    public function test_mapa_page_loads_correctly(): void
    {
        $response = $this->get('/mapa');

        $response->assertStatus(200);
        $response->assertSee('pwa-map');
        $response->assertSee('leaflet');
    }
}
