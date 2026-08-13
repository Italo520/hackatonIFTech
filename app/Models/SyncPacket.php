<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncPacket extends Model
{
    use HasFactory;

    protected $fillable = [
        'versao',
        'hash',
        'gerado_em',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'gerado_em' => 'datetime',
    ];

    public function entidade()
    {
        return $this->morphTo();
    }
}
