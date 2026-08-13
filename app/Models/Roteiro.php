<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roteiro extends Model
{
    use HasFactory;

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
