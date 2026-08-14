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
        'contato_emergencia',
        'responsavel',
        'duracao_horas',
        'valido_ate',
        'status',
        'vigencia_inicio',
        'vigencia_fim',
        'criado_por',
    ];

    protected $casts = [
        'segmentacao' => 'array',
        'vigencia_inicio' => 'datetime',
        'vigencia_fim' => 'datetime',
        'valido_ate' => 'datetime',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    /**
     * Escopo para carregar apenas alertas ativos e vigentes
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo')
            ->where(function ($q) {
                $q->whereNull('valido_ate')
                  ->orWhere('valido_ate', '>=', now());
            });
    }

    /**
     * Verifica se o alerta ainda está no prazo de validade
     */
    public function estaVigente(): bool
    {
        if ($this->status !== 'ativo') {
            return false;
        }
        if ($this->valido_ate && $this->valido_ate->isPast()) {
            return false;
        }
        return true;
    }
}
