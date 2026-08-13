<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'uf',
        'bbox_geo',
        'tema_visual',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function atrativos()
    {
        return $this->hasMany(Atrativo::class);
    }
}
