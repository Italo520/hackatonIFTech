@extends('layouts.pwa')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Saudação -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark fs-1 mb-1" style="letter-spacing: -0.02em;">Bom dia, Turista!</h1>
        <p class="text-secondary small mt-1">O que vamos descobrir hoje em <span class="current-city-name fw-semibold text-primary">João Pessoa</span>?</p>
    </div>

    <!-- Search Bar -->
    <a href="{{ route('pwa.explorar') }}" class="d-block mb-4 text-decoration-none">
        <div class="form-control rounded-pill border-0 shadow-sm d-flex align-items-center gap-2 text-secondary px-3" style="height: 48px; background-color: #ffffff;">
            <i class="bi bi-search text-primary"></i>
            <span class="small">Buscar praias, atrativos, restaurantes...</span>
        </div>
    </a>

    <!-- Banner de Permissão de Localização (exibido se GPS não detectado) -->
    <div id="location-permission-banner" class="card border-0 rounded-4 p-3 mb-4 text-white shadow-sm d-none" style="background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pe-2">
                <div class="fw-bold fs-6"><i class="bi bi-geo-alt-fill text-warning me-1"></i> Ativar Localização Real</div>
                <div class="small opacity-90" style="font-size: 0.78rem;">Descubra atrativos, praias e restaurantes próximos a você via OpenStreetMap.</div>
            </div>
            <button class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 flex-shrink-0 shadow-sm" id="btn-enable-gps-home" style="font-size: 0.85rem;">
                <i class="bi bi-crosshair me-1"></i> Ativar GPS
            </button>
        </div>
    </div>

    <!-- Roteiros em Destaque -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <h2 class="fs-5 fw-bold text-dark m-0">Roteiros Recomendados</h2>
            <a href="{{ route('pwa.roteiros') }}" class="small fw-semibold text-primary text-decoration-none" style="min-height: 44px; display: flex; align-items: center;">Ver todos</a>
        </div>
        
        <div class="d-flex overflow-auto no-scrollbar gap-3 pb-3" style="margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem; scroll-snap-type: x mandatory;">
            <!-- Card 1: João Pessoa -->
            <a href="{{ route('pwa.roteiro', 101) }}" class="card border-0 rounded-4 text-decoration-none text-dark flex-shrink-0" style="width: 270px; scroll-snap-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
                <div class="position-relative overflow-hidden rounded-top-4" style="height: 140px; background-color: #f3f4f5;">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80" alt="Orla & Farol" class="w-100 h-100 object-fit-cover">
                    <div class="position-absolute top-0 start-0 m-2 px-2 py-1 rounded bg-white bg-opacity-75" style="backdrop-filter: blur(4px);">
                        <span class="small fw-bold text-primary">Top Escolha</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h3 class="card-title fs-6 fw-bold mb-1">Praias, Piscinas & Farol</h3>
                    <p class="card-text small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Orla de Tambaú, piscinas naturais dos Seixas e pôr do sol no Farol.</p>
                    <div class="d-flex align-items-center gap-3 small fw-medium text-secondary">
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-clock text-primary"></i> 1 Dia</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-geo-alt-fill text-warning"></i> João Pessoa</span>
                    </div>
                </div>
            </a>
            
            <!-- Card 2: Centro Histórico -->
            <a href="{{ route('pwa.roteiro', 102) }}" class="card border-0 rounded-4 text-decoration-none text-dark flex-shrink-0" style="width: 270px; scroll-snap-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
                <div class="position-relative overflow-hidden rounded-top-4" style="height: 140px; background-color: #f3f4f5;">
                    <img src="https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=800&q=80" alt="Centro Cultural" class="w-100 h-100 object-fit-cover">
                    <div class="position-absolute top-0 start-0 m-2 px-2 py-1 rounded bg-white bg-opacity-75" style="backdrop-filter: blur(4px);">
                        <span class="small fw-bold text-danger">Cultura</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h3 class="card-title fs-6 fw-bold mb-1">História & Gastronomia</h3>
                    <p class="card-text small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Arte barroca no São Francisco e culinária paraibana no Mangai.</p>
                    <div class="d-flex align-items-center gap-3 small fw-medium text-secondary">
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-clock text-primary"></i> 4 Horas</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-wallet2 text-primary"></i> $$</span>
                    </div>
                </div>
            </a>

            <!-- Card 3: Assistente IA -->
            <a href="{{ route('pwa.ia') }}" class="card border-0 rounded-4 text-decoration-none text-dark flex-shrink-0" style="width: 270px; scroll-snap-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
                <div class="position-relative overflow-hidden rounded-top-4 d-flex flex-column align-items-center justify-content-center text-white" style="height: 140px; background: linear-gradient(135deg, #005f73, #0a9396);">
                    <i class="bi bi-robot display-5 mb-1"></i>
                    <span class="fw-bold">Assistente com IA</span>
                </div>
                <div class="card-body p-3">
                    <h3 class="card-title fs-6 fw-bold mb-1">Roteiro Personalizado</h3>
                    <p class="card-text small text-secondary">Deixe nossa inteligência artificial montar o dia perfeito para você.</p>
                </div>
            </a>
        </div>
    </div>


    <!-- Categorias -->
    <div class="mb-5">
        <h2 class="fs-5 fw-bold text-dark mb-3">Explorar por Categoria</h2>
        <div class="row row-cols-4 g-2 text-center">
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=rios" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                        <i class="bi bi-water fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Rios</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=grutas" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(238, 155, 0, 0.1); color: #ee9b00;">
                        <i class="bi bi-geo fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Grutas</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=aventura" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(10, 147, 150, 0.1); color: var(--bs-secondary);">
                        <i class="bi bi-bicycle fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Aventura</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=gastronomia" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(186, 26, 26, 0.1); color: #ba1a1a;">
                        <i class="bi bi-cup-hot fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Comer</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Banner DTI / App -->
    <div class="card border-0 rounded-4 text-white overflow-hidden mb-4" style="background: linear-gradient(135deg, #0a9396, #005f73); box-shadow: 0 8px 24px rgba(0, 95, 115, 0.25);">
        <div class="position-absolute opacity-25" style="right: -20px; bottom: -20px;">
            <i class="bi bi-shield-check" style="font-size: 8rem;"></i>
        </div>
        <div class="card-body p-4 position-relative z-1">
            <h3 class="fw-bold fs-5 mb-1">Turismo Seguro</h3>
            <p class="small text-white-50 mb-3 w-75">Acesse telefones úteis, hospitais e alertas da Defesa Civil.</p>
            <a href="{{ route('pwa.utilidade') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm" style="min-height: 44px; display: inline-flex; align-items: center;">
                Acessar Central
            </a>
        </div>
    </div>
</div>
@endsection
