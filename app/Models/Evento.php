<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'local',
        'geo',
        'inicio',
        'fim',
        'organizador',
        'ingressos',
        'capacidade',
        'faixa_etaria',
        'gratuito',
        'acessibilidade',
        'status',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'gratuito' => 'boolean',
        'acessibilidade' => 'array',
    ];
}
