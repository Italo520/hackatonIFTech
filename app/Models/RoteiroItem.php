<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoteiroItem extends Model
{
    use HasFactory;

    protected $table = 'roteiro_itens';

    protected $fillable = [
        'roteiro_id',
        'atrativo_id',
        'ordem',
        'tempo_estimado',
    ];

    public function roteiro()
    {
        return $this->belongsTo(Roteiro::class);
    }

    public function atrativo()
    {
        return $this->belongsTo(Atrativo::class);
    }
}
