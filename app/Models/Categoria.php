<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'icone',
        'tipo',
    ];

    public function atrativos()
    {
        return $this->hasMany(Atrativo::class);
    }
}
