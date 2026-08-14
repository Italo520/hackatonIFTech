<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Roteiro extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'titulo',
        'tema',
        'duracao',
        'dificuldade',
        'transporte',
        'orcamento',
        'perfil',
        'origem',
        'geo',
        'distancia_total',
        'publico',
    ];

    protected $casts = [
        'publico' => 'boolean',
    ];

    public function itens()
    {
        return $this->hasMany(RoteiroItem::class)->orderBy('ordem', 'asc');
    }
}
