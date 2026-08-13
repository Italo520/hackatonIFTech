<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'geo',
        'metadados',
    ];

    protected $casts = [
        'metadados' => 'array',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }
}
