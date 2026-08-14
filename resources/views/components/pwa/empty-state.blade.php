@props([
    'icon' => 'bi-compass',
    'titulo' => 'Nenhum resultado encontrado',
    'descricao' => 'Tente ajustar seus filtros ou buscar por outro termo.',
    'actionLabel' => null,
    'actionUrl' => null,
    'actionIcon' => null,
])

<div class="card border-0 rounded-4 shadow-sm bg-white p-4 text-center my-3">
    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 bg-light text-primary mx-auto mb-3" style="width: 56px; height: 56px;" aria-hidden="true">
        <i class="bi {{ $icon }} fs-3"></i>
    </div>
    <h3 class="fw-bold text-dark fs-6 mb-1">{{ $titulo }}</h3>
    <p class="text-secondary small mb-3">{{ $descricao }}</p>
    @if($actionLabel && $actionUrl)
        <div>
            <a href="{{ $actionUrl }}" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold shadow-sm">
                @if($actionIcon)
                    <i class="bi {{ $actionIcon }} me-1" aria-hidden="true"></i>
                @endif
                {{ $actionLabel }}
            </a>
        </div>
    @endif
</div>
