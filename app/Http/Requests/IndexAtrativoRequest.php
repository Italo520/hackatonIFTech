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
            'acessivel_para' => ['nullable', 'string', 'max:50'],
            'duracao_max' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
