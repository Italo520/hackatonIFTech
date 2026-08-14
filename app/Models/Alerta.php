<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Alerta extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'titulo',
        'corpo',
        'segmentacao',
        'geo',
        'urgencia',
        'vigencia_inicio',
        'vigencia_fim',
        'criado_por',
    ];

    protected $casts = [
        'segmentacao' => 'array',
        'vigencia_inicio' => 'datetime',
        'vigencia_fim' => 'datetime',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
