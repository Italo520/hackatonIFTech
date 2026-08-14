<?php

namespace Database\Factories;

use App\Models\Atrativo;
use App\Models\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QrCode>
 */
class QrCodeFactory extends Factory
{
    protected $model = QrCode::class;

    public function definition(): array
    {
        return [
            'atrativo_id' => Atrativo::factory(),
            'hash_code' => Str::random(10),
            'impressoes' => 0,
            'scans' => 0,
        ];
    }
}
