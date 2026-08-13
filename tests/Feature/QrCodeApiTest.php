<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Atrativo;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\QrCode;

class QrCodeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_scan_qrcode_and_increment_metrics(): void
    {
        $municipio = Municipio::create(['nome' => 'City', 'uf' => 'TS']);
        $categoria = Categoria::create(['nome' => 'Cat', 'slug' => 'cat']);
        $atrativo = Atrativo::create([
            'municipio_id' => $municipio->id,
            'categoria_id' => $categoria->id,
            'nome' => 'Place',
            'descricao' => 'Desc'
        ]);

        $qr = QrCode::create([
            'atrativo_id' => $atrativo->id,
            'hash_code' => 'TESTHASH123',
        ]);

        $response = $this->getJson('/api/v1/qr/TESTHASH123');

        $response->assertStatus(200)
                 ->assertJsonPath('atrativo.id', $atrativo->id);

        $this->assertDatabaseHas('qrcodes', [
            'hash_code' => 'TESTHASH123',
            'scans' => 1
        ]);
    }
}
