<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistantLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'pergunta',
        'resposta',
        'fontes',
        'idioma',
        'feedback_util',
    ];

    protected $casts = [
        'fontes' => 'array',
        'feedback_util' => 'boolean',
    ];
}
