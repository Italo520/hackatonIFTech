<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'user_id',
        'entidade_id',
        'entidade_type',
        'nota',
        'comentario',
        'sentimento',
        'status_moderacao',
        'origem_offline',
    ];

    protected $casts = [
        'origem_offline' => 'boolean',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
