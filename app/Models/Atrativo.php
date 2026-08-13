<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atrativo extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipio_id',
        'categoria_id',
        'nome',
        'descricao',
        'historia',
        'endereco',
        'geo',
        'horarios',
        'tempo_medio_visita',
        'precos',
        'contatos',
        'acessibilidade',
        'restricoes',
        'seguranca',
        'status',
        'validado_por',
        'validado_em',
    ];

    protected $casts = [
        'horarios' => 'array',
        'precos' => 'array',
        'contatos' => 'array',
        'acessibilidade' => 'array',
        'validado_em' => 'datetime',
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function midias()
    {
        return $this->morphMany(Midia::class, 'entidade');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }
}
