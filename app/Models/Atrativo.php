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

    protected $appends = [
        'imagem_url',
        'fotos',
        'categoria_slug',
        'categoria_icone',
    ];

    public function getImagemUrlAttribute(): string
    {
        $foto = $this->relationLoaded('midias') 
            ? $this->midias->firstWhere('tipo', 'foto')
            : $this->midias()->where('tipo', 'foto')->first();

        if ($foto && !empty($foto->url)) {
            return $foto->url;
        }

        return $this->resolveFallbackImage();
    }

    public function getFotosAttribute(): array
    {
        $midias = $this->relationLoaded('midias') ? $this->midias : $this->midias()->get();
        $urls = $midias->where('tipo', 'foto')->pluck('url')->filter()->values()->toArray();
        if (empty($urls)) {
            return [$this->imagem_url];
        }
        return $urls;
    }

    public function getCategoriaSlugAttribute(): string
    {
        return $this->categoria?->slug ?? 'geral';
    }

    public function getCategoriaIconeAttribute(): string
    {
        return $this->categoria?->icone ?? 'bi-geo-alt';
    }

    public function resolveFallbackImage(): string
    {
        $slug = $this->categoria?->slug ?? '';
        $fallbacks = [
            'rios' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'praias-e-rios' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'aventura' => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=1000&q=80',
            'grutas' => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=1000&q=80',
            'gastronomia' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80',
            'hospedagem' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1000&q=80',
            'cultura' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1000&q=80',
        ];

        return $fallbacks[$slug] ?? 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1000&q=80';
    }

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

