<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Atrativo>
 */
class AtrativoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Atrativo::class;

    public function definition(): array
    {
        return [
            'municipio_id' => \App\Models\Municipio::factory(),
            'categoria_id' => \App\Models\Categoria::factory(),
            'nome' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(),
            'status' => 'ativo',
        ];
    }
}
