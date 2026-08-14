<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';

    protected $fillable = [
        'tipo',
        'local_texto',
        'geo',
        'gravidade',
        'descricao',
        'status_atendimento',
        'origem',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }
}
