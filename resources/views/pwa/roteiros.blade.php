@extends('layouts.pwa')

@section('content')
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 1020;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold fs-5 mb-0">Roteiros Prontos</h1>
            <p class="small text-secondary mb-0">Itinerários otimizados para <span class="current-city-name text-primary fw-semibold">sua localização</span></p>
        </div>
        <a href="{{ route('pwa.ia') }}" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-3">
            <i class="bi bi-stars me-1"></i> Criar com IA
        </a>
    </div>
</div>

<div class="container-fluid px-3 py-4 mb-5">
    <div class="d-flex flex-column gap-3">
        <!-- Roteiro 1: João Pessoa Praias e Farol -->
        <a href="{{ route('pwa.roteiro', 101) }}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark position-relative">
            <div class="position-relative" style="height: 160px;">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Orla e Farol">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-fill text-warning me-1"></i> 1 Dia Inteiro
                    </span>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);">
                    <span class="badge bg-primary rounded-pill px-2 py-1 text-white small">João Pessoa - PB</span>
                    <h2 class="text-white fw-bold fs-5 mb-0 mt-1">Orla, Piscinas do Seixas & Farol</h2>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="small text-secondary mb-3">Passeio completo pelas águas calmas de Tambaú, mergulho nas piscinas de corais e pôr do sol no ponto mais oriental das Américas.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <div class="small text-secondary">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>3 paradas</strong> conectadas
                    </div>
                    <span class="fw-bold text-primary small">Ver Itinerário <i class="bi bi-chevron-right"></i></span>
                </div>
            </div>
        </a>

        <!-- Roteiro 2: Centro Histórico e Sabores de João Pessoa -->
        <a href="{{ route('pwa.roteiro', 102) }}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark position-relative">
            <div class="position-relative" style="height: 160px;">
                <img src="https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Centro Histórico">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-fill text-warning me-1"></i> 4 Horas
                    </span>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);">
                    <span class="badge bg-danger rounded-pill px-2 py-1 text-white small">João Pessoa - PB</span>
                    <h2 class="text-white fw-bold fs-5 mb-0 mt-1">História Barroca & Culinária Regional</h2>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="small text-secondary mb-3">Arquitetura barroca no Centro Cultural São Francisco seguido por almoço com autêntica gastronomia nordestina.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <div class="small text-secondary">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>2 paradas</strong> inclusivas
                    </div>
                    <span class="fw-bold text-primary small">Ver Itinerário <i class="bi bi-chevron-right"></i></span>
                </div>
            </div>
        </a>

        <!-- Roteiro 3: Bonito Ecoturismo -->
        <a href="{{ route('pwa.roteiro', 1) }}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark position-relative">
            <div class="position-relative" style="height: 160px;">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Flutuação Bonito">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-fill text-warning me-1"></i> 2 Dias
                    </span>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);">
                    <span class="badge bg-info rounded-pill px-2 py-1 text-white small">Bonito - MS</span>
                    <h2 class="text-white fw-bold fs-5 mb-0 mt-1">Águas Cristalinas & Cavernas</h2>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="small text-secondary mb-3">Flutuação de águas ultra transparentes no Rio Sucuri e exploração da Gruta do Lago Azul.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <div class="small text-secondary">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>3 paradas</strong>
                    </div>
                    <span class="fw-bold text-primary small">Ver Itinerário <i class="bi bi-chevron-right"></i></span>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

