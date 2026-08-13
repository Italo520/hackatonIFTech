<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'chunk',
        'vector_data',
        'idioma',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }
}
