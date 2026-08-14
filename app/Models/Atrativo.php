<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Atrativo extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'municipio_id',
        'categoria_id',
        'nome',
        'descricao',
        'historia',
        'endereco',
        'lat',
        'lng',
        'geo',
        'horarios',
        'tempo_medio_visita',
        'precos',
        'contatos',
        'acessibilidade',
        'restricoes',
        'seguranca',
        'status',
        'validado_por',
        'validado_em',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'horarios' => 'array',
        'precos' => 'array',
        'contatos' => 'array',
        'acessibilidade' => 'array',
        'validado_em' => 'datetime',
    ];

    /**
     * Calcula a distância em km para uma coordenada de usuário (Fórmula Haversine)
     */
    public function calcularDistanciaKm(?float $userLat, ?float $userLng): ?float
    {
        if (is_null($this->lat) || is_null($this->lng) || is_null($userLat) || is_null($userLng)) {
            return null;
        }

        $earthRadius = 6371; // km
        $dLat = deg2rad($this->lat - $userLat);
        $dLng = deg2rad($this->lng - $userLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($userLat)) * cos(deg2rad($this->lat)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }

    /**
     * Formata a distância para exibição amigável (ex: "450 m" ou "3.2 km")
     */
    public function formatarDistancia(?float $distanciaKm): ?string
    {
        if (is_null($distanciaKm)) {
            return null;
        }
        if ($distanciaKm < 1) {
            return round($distanciaKm * 1000) . ' m';
        }
        return number_format($distanciaKm, 1, ',', '.') . ' km';
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function midias()
    {
        return $this->morphMany(Midia::class, 'entidade');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    /**
     * Avaliações e comentários de visitantes sobre este atrativo.
     * Usa relação polimórfica para permitir avaliar também Prestadores e Eventos.
     */
    public function avaliacoes()
    {
        return $this->morphMany(Avaliacao::class, 'entidade');
    }
}

