@props([
    'id',
    'nome',
    'descricao',
    'categoria' => 'Atrativo',
    'categoriaIcon' => 'bi-compass',
    'categoriaCor' => '#005f73',
    'imagem' => null,
    'avaliacao' => '4.8',
    'tempoVisita' => null,
    'distancia' => null,
    'lat' => null,
    'lng' => null,
    'destaque' => false,
])

<div class="col">
    <article class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 place-home-card bg-white position-relative" aria-labelledby="atrativo-title-{{ $id }}">
        <div class="position-relative" style="height: 160px; background-color: #f3f4f5;">
            <img src="{{ $imagem ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}" 
                 alt="Foto de {{ $nome }}" 
                 class="w-100 h-100 object-fit-cover" 
                 loading="lazy">
            <div class="position-absolute top-0 start-0 m-2">
                <span class="badge rounded-pill px-2.5 py-1 text-white shadow-sm" style="background-color: {{ $categoriaCor }}; font-size: 0.72rem;">
                    <i class="bi {{ $categoriaIcon }} me-1" aria-hidden="true"></i>{{ $categoria }}
                </span>
            </div>
            @if($avaliacao)
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-white text-dark rounded-pill px-2 py-1 shadow-sm fw-bold small">
                        <i class="bi bi-star-fill text-warning me-1" aria-hidden="true"></i>{{ $avaliacao }}
                    </span>
                </div>
            @endif
        </div>
        <div class="card-body p-3 d-flex flex-column justify-content-between">
            <div>
                <h3 id="atrativo-title-{{ $id }}" class="card-title fs-6 fw-bold mb-1 text-dark">{{ $nome }}</h3>
                <p class="card-text small text-secondary mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.8rem; line-height: 1.4;">
                    {{ $descricao }}
                </p>
            </div>
            <div>
                <div class="d-flex align-items-center justify-content-between pt-2 mb-3 border-top small text-muted" style="font-size: 0.75rem;">
                    <span><i class="bi bi-clock me-1 text-primary" aria-hidden="true"></i>{{ $tempoVisita ?? '1-2 horas' }}</span>
                    @if($distancia)
                        <span class="badge bg-warning-subtle text-dark border rounded-pill px-2 py-0.5">
                            <i class="bi bi-geo-alt-fill text-warning me-1" aria-hidden="true"></i>{{ $distancia }}
                        </span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pwa.atrativo', ['id' => $id]) }}" class="btn btn-primary rounded-pill btn-sm w-100 fw-bold py-1.5" style="font-size: 0.8rem;" aria-label="Ver detalhes de {{ $nome }}">
                        Ver Detalhes
                    </a>
                    @if($lat && $lng)
                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-bold py-1.5" style="font-size: 0.8rem;" 
                            onclick="window.LocationService ? window.LocationService.openDirections({{ $lat }}, {{ $lng }}, '{{ addslashes($nome) }}') : null" 
                            title="Traçar rota até {{ $nome }}"
                            aria-label="Traçar rota até {{ $nome }}">
                            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </article>
</div>
