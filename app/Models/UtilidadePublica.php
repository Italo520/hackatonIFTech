<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilidadePublica extends Model
{
    use HasFactory;

    protected $table = 'utilidades_publicas';

    protected $fillable = [
        'nome',
        'descricao',
        'telefone',
        'icone',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
