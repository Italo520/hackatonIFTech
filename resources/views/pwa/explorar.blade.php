@extends('layouts.pwa')

@section('content')
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 1020;">
    <div class="position-relative">
        <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
            <i class="bi bi-search text-secondary"></i>
        </div>
        <input type="text" class="form-control rounded-pill border-0 shadow-sm ps-5 bg-white" placeholder="Buscar atrativos, locais..." style="height: 48px;">
    </div>
    
    <!-- Filtros Chips -->
    <div class="d-flex gap-2 mt-3 overflow-auto no-scrollbar pb-1" style="margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem;">
        <button class="btn btn-primary rounded-pill btn-sm px-3 fw-medium flex-shrink-0" style="min-height: 36px;">Todos</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Rios e Nascentes</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Grutas</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Gastronomia</button>
    </div>
</div>

<div class="container-fluid px-3 py-4">
    <!-- Lista de Atrativos -->
    <div class="d-flex flex-column gap-3">
        @php
            $atrativos = [
                ['id' => 1, 'nome' => 'Flutuação no Rio Sucuri', 'cat' => 'Rios e Nascentes', 'img' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'color' => 'primary'],
                ['id' => 2, 'nome' => 'Gruta do Lago Azul', 'cat' => 'Grutas', 'img' => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'color' => 'warning'],
                ['id' => 3, 'nome' => 'Bóia Cross no Rio Formoso', 'cat' => 'Aventura', 'img' => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'color' => 'secondary'],
                ['id' => 4, 'nome' => 'Casa do João', 'cat' => 'Gastronomia', 'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'color' => 'danger'],
            ];
        @endphp

        @foreach($atrativos as $a)
        <a href="{{ route('pwa.atrativo', $a['id']) }}" class="card border-0 rounded-4 text-decoration-none text-dark d-flex flex-row overflow-hidden shadow-sm" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;">
            <div class="position-relative" style="width: 120px; background-color: #f3f4f5;">
                <img src="{{ $a['img'] }}" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" alt="{{ $a['nome'] }}">
            </div>
            <div class="card-body p-3 d-flex flex-column justify-content-center">
                <span class="small text-uppercase fw-bold text-{{ $a['color'] }} mb-1" style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ $a['cat'] }}</span>
                <h3 class="card-title fs-6 fw-bold mb-1" style="line-height: 1.2;">{{ $a['nome'] }}</h3>
                <div class="d-flex align-items-center gap-1 text-secondary mt-2" style="font-size: 0.7rem;">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                    <span class="ms-1">(128 avaliações)</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
