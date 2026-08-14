<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Evento extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

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
