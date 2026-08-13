<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_loads_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('O que fazer');
        $response->assertSee('Guia Turístico');
    }
    
    public function test_mapa_page_loads_correctly(): void
    {
        $response = $this->get('/mapa');

        $response->assertStatus(200);
        $response->assertSee('map-container');
        $response->assertSee('leaflet');
    }
}
