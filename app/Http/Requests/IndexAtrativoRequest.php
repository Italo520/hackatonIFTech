<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexAtrativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'categoria_id' => ['nullable', 'integer', 'exists:categorias,id'],
            'municipio_id' => ['nullable', 'integer'],
            'acessivel_para' => ['nullable', 'string', 'max:50'],
            'duracao_max' => ['nullable', 'integer', 'min:1'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
            'raio_km' => ['nullable', 'numeric', 'min:0.1', 'max:5000'],
            'sort_by' => ['nullable', 'string', 'in:nome,duracao,distancia,mais_proximos'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:500'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ];
    }
}

