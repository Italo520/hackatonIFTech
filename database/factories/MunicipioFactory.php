<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipio>
 */
class MunicipioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Municipio::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->city(),
            'uf' => 'PB',
            'tema_visual' => 'default',
        ];
    }
}
