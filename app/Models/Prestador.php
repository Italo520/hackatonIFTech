<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    use HasFactory;

    protected $table = 'prestadores';

    protected $fillable = [
        'user_id',
        'tipo',
        'dados',
        'documentos',
        'validade_documentos',
        'status',
        'selo_validado',
        'ultima_atualizacao',
    ];

    protected $casts = [
        'dados' => 'array',
        'documentos' => 'array',
        'validade_documentos' => 'date',
        'selo_validado' => 'boolean',
        'ultima_atualizacao' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
