<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:ativo,alterado,cancelado'],
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'gratuito' => ['nullable', 'boolean'],
        ];
    }
    
    protected function prepareForValidation()
    {
        if ($this->has('gratuito')) {
            $this->merge([
                'gratuito' => filter_var($this->gratuito, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
