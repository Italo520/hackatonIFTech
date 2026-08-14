<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Prestador extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

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
