<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Midia extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'url',
        'autor',
        'licenca',
        'alt_text',
        'legenda',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }
}
