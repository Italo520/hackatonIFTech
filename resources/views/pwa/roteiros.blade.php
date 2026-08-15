@extends('layouts.pwa')

@section('content')
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 100; top: 0;">
    @if(request('from') === 'admin' || (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 'gestor_cadastros', 'atendente'])))
        <div class="mb-2">
            <a href="{{ route('admin.roteiros.index') }}" class="btn btn-dark rounded-pill btn-sm px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 text-white border border-warning shadow-sm" style="background: #003844; font-size: 0.8rem;">
                <i class="bi bi-arrow-left text-warning"></i>
                <span>Voltar ao Painel de Roteiros</span>
            </a>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="fw-bold fs-5 mb-0">Roteiros Prontos</h1>
            <p class="small text-secondary mb-0">Itinerários otimizados para <span class="current-city-name text-primary fw-semibold">Sua Localização</span></p>
        </div>
        <a href="{{ route('pwa.ia') }}" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-3 shadow-sm">
            <i class="bi bi-stars me-1"></i> Criar com IA
        </a>
    </div>

    <!-- Chips de Filtro de Duração -->
    <div class="d-flex gap-2 overflow-auto no-scrollbar pb-1 pt-1" id="chips-roteiros-container">
        <a href="{{ route('pwa.roteiros') }}" class="btn {{ !request('duracao') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            Todos os Roteiros
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'curto']) }}" class="btn {{ request('duracao') === 'curto' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-clock me-1"></i> Até 4 Horas
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'dia']) }}" class="btn {{ request('duracao') === 'dia' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-sun me-1"></i> 1 Dia Completo
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'fimdesemana']) }}" class="btn {{ request('duracao') === 'fimdesemana' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-calendar-range me-1"></i> Fim de Semana
        </a>
    </div>
</div>

<div class="container-fluid px-3 py-4 mb-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="container-lista-roteiros">
        @if(isset($roteiros) && $roteiros->count() > 0)
            @foreach($roteiros as $r)
                @php
                    $primeiroAtrativo = $r->itens->first()?->atrativo;
                    $foto = $primeiroAtrativo?->midias?->first()?->url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';
                @endphp
                <div class="col">
                    <div class="position-relative h-100">
                        <a href="{{ route('pwa.roteiro', $r->id) }}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark card-hover-shadow transition-all bg-white d-flex flex-column h-100">
                            <div class="position-relative" style="height: 190px;">
                                <img src="{{ $foto }}" class="w-100 h-100 object-fit-cover" alt="{{ $r->titulo }}" loading="lazy" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';">
                                @if($r->origem === 'ia')
                                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-stars"></i> Roteiro IA</span>
                                @else
                                    <span class="badge bg-success text-white position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-shield-check"></i> Roteiro Oficial</span>
                                @endif
                                
                                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
                                    <span class="badge bg-primary rounded-pill px-2 py-0.5 text-white small">{{ $r->tema ?? 'Turismo Geral' }}</span>
                                    <h2 class="text-white fw-bold fs-5 mb-0 mt-1 text-truncate">{{ $r->titulo }}</h2>
                                </div>
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                                <p class="small text-secondary mb-3" style="line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $r->descricao ?? 'Roteiro elaborado para otimizar sua visitação turística com segurança e conforto.' }}
                                </p>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto">
                                    <div class="small text-secondary d-flex align-items-center gap-2">
                                        <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>{{ $r->itens->count() }} paradas</strong></span>
                                        <span><i class="bi bi-clock-fill text-warning me-1"></i> {{ $r->duracao ? ($r->duracao > 60 ? round($r->duracao/60, 1) . 'h' : $r->duracao . ' min') : '1 Dia' }}</span>
                                        @if($r->orcamento)
                                            <span><i class="bi bi-cash-coin text-success me-1"></i> R$ {{ number_format((float)$r->orcamento, 2, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <span class="fw-bold text-primary small">Ver Rota <i class="bi bi-chevron-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Container do Roteiro Personalizado -->
    <div id="dynamic-custom-roteiro-section" class="mt-2 mb-4 d-none p-3 rounded-4" style="background: linear-gradient(135deg, rgba(0, 95, 115, 0.05), rgba(10, 147, 150, 0.05)); border: 1px dashed rgba(0, 95, 115, 0.3);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fs-6 fw-bold text-dark mb-0"><i class="bi bi-cart-check-fill text-primary me-1"></i> Meu Roteiro Personalizado</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary rounded-pill fw-bold shadow-sm" id="btn-route-custom-roteiro" style="font-size: 0.75rem;"><i class="bi bi-cursor-fill"></i> Iniciar Rota</button>
                <button class="btn btn-sm btn-outline-danger rounded-pill fw-bold bg-white shadow-sm px-2" id="btn-clear-custom-roteiro" style="font-size: 0.75rem;" title="Limpar"><i class="bi bi-trash"></i></button>
            </div>
        </div>
        <div class="d-flex flex-column gap-2" id="dynamic-custom-roteiro-container"></div>
    </div>

    <!-- Container dos Roteiros Salvos da IA (Renderizado em Grid) -->
    <div id="dynamic-ia-roteiros-section" class="mt-4 d-none">
        <h4 class="fs-6 fw-bold text-dark mb-3"><i class="bi bi-stars text-warning me-1"></i> Seus Roteiros Gerados por IA</h4>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="dynamic-ia-roteiros-container"></div>
    </div>

    <!-- Paginação Laravel -->
    @if(isset($roteiros) && method_exists($roteiros, 'hasPages') && $roteiros->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $roteiros->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Modal: Escolher app de navegação para roteiro customizado --}}
<div class="modal fade" id="modal-route-custom-options" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Como deseja chegar?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="small text-secondary mb-3">
                    Navegue pelo seu <strong>Roteiro Personalizado</strong> usando:
                </p>
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-custom-app-btn"
                            data-provider="google">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-google text-danger fs-4"></i>
                            <div>
                                <div class="fw-bold">Google Maps</div>
                                <div class="small text-secondary">Melhor para múltiplas paradas</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-custom-app-btn"
                            data-provider="waze">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-compass text-info fs-4"></i>
                            <div>
                                <div class="fw-bold">Waze</div>
                                <div class="small text-secondary">Destino final com alertas de tráfego</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-custom-app-btn"
                            data-provider="osm">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-map-fill text-success fs-4"></i>
                            <div>
                                <div class="fw-bold">OpenStreetMap</div>
                                <div class="small text-secondary">Rotas abertas e gratuitas</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dynamicSection = document.getElementById('dynamic-ia-roteiros-section');
    const dynamicContainer = document.getElementById('dynamic-ia-roteiros-container');
    
    function renderSavedIARoteiros() {
        if (!dynamicContainer || !dynamicSection) return;
        let meusRoteiros = [];
        try {
            meusRoteiros = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
        } catch(e) {}

        if (meusRoteiros.length === 0) {
            dynamicSection.classList.add('d-none');
            return;
        }

        dynamicSection.classList.remove('d-none');
        dynamicContainer.innerHTML = meusRoteiros.map(r => `
            <div class="col">
                <div class="position-relative h-100">
                    <a href="/roteiro/${r.id}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark card-hover-shadow transition-all bg-white d-flex flex-column h-100">
                        <div class="position-relative" style="height: 190px;">
                            <img src="${r.imagem || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'}" class="w-100 h-100 object-fit-cover" alt="${r.titulo}">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-stars"></i> Roteiro IA (Salvo)</span>
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
                                <span class="badge bg-primary rounded-pill px-2 py-0.5 text-white small">${r.cidade || 'Seu Destino'}</span>
                                <h2 class="text-white fw-bold fs-5 mb-0 mt-1 text-truncate">${r.titulo}</h2>
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <p class="small text-secondary mb-3" style="line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${r.descricao || 'Roteiro gerado pelo assistente de Inteligência Artificial.'}
                            </p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto">
                                <div class="small text-secondary d-flex align-items-center gap-2">
                                    <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>${r.paradas ? r.paradas.length : 3} paradas</strong></span>
                                    <span><i class="bi bi-clock-fill text-warning me-1"></i> ${r.duracao || '2 horas'}</span>
                                </div>
                                <span class="fw-bold text-primary small">Ver Rota <i class="bi bi-chevron-right"></i></span>
                            </div>
                        </div>
                    </a>
                    <button class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm btn-delete-ia" data-id="${r.id}" style="top: 8px; right: 8px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; z-index: 5;" title="Excluir Roteiro">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </div>
        `).join('');

        dynamicContainer.querySelectorAll('.btn-delete-ia').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('Deseja excluir este roteiro da IA salvo no seu navegador?')) {
                    const idToRemove = parseInt(this.getAttribute('data-id'));
                    try {
                        let saved = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
                        saved = saved.filter(r => parseInt(r.id) !== idToRemove);
                        localStorage.setItem('meus_roteiros_ia', JSON.stringify(saved));
                        renderSavedIARoteiros();
                    } catch(err) {}
                }
            });
        });
    }

    renderSavedIARoteiros();

    // ==========================================
    // RENDERIZAR ROTEIRO PERSONALIZADO
    // ==========================================
    function renderCustomRoteiro() {
        const section = document.getElementById('dynamic-custom-roteiro-section');
        const container = document.getElementById('dynamic-custom-roteiro-container');
        if (!section || !container) return;

        let roteiroArray = [];
        try {
            roteiroArray = JSON.parse(localStorage.getItem('meu_roteiro_customizado') || '[]');
        } catch(e) {}

        if (roteiroArray.length === 0) {
            section.classList.add('d-none');
            return;
        }

        section.classList.remove('d-none');
        container.innerHTML = roteiroArray.map((item, index) => `
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden text-decoration-none text-dark bg-white position-relative pe-5">
                <a href="/atrativo/${item.id}" class="d-flex align-items-center p-2 text-decoration-none text-dark h-100">
                    <img src="${item.imagem || 'https://picsum.photos/800/500'}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 70px; height: 70px;" alt="${item.nome}">
                    <div class="ms-3 flex-grow-1 text-truncate">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5 mb-1" style="font-size: 0.65rem;">${item.categoria}</span>
                        <h3 class="fs-6 fw-bold mb-0 text-truncate" style="font-size: 0.9rem !important;">${item.nome}</h3>
                    </div>
                </a>
                
                <!-- Controles de Ordem e Remoção -->
                <div class="position-absolute end-0 top-0 h-100 d-flex flex-column justify-content-center align-items-center bg-light border-start px-2 py-1 gap-1" style="width: 44px; z-index: 5;">
                    <button class="btn btn-sm text-secondary p-0 btn-move-up ${index === 0 ? 'invisible' : ''}" data-index="${index}" title="Mover para Cima">
                        <i class="bi bi-chevron-up fs-6"></i>
                    </button>
                    
                    <button class="btn btn-sm text-danger p-1 rounded-circle btn-remove-custom-item d-flex align-items-center justify-content-center bg-white shadow-sm border" data-id="${item.id}" style="width: 26px; height: 26px;" title="Remover">
                        <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                    </button>

                    <button class="btn btn-sm text-secondary p-0 btn-move-down ${index === roteiroArray.length - 1 ? 'invisible' : ''}" data-index="${index}" title="Mover para Baixo">
                        <i class="bi bi-chevron-down fs-6"></i>
                    </button>
                </div>
            </div>
        `).join('');

        // Listeners: Remover
        container.querySelectorAll('.btn-remove-custom-item').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const idToRemove = parseInt(this.getAttribute('data-id'));
                roteiroArray = roteiroArray.filter(i => i.id !== idToRemove);
                localStorage.setItem('meu_roteiro_customizado', JSON.stringify(roteiroArray));
                renderCustomRoteiro();
            });
        });

        // Listeners: Mover para Cima
        container.querySelectorAll('.btn-move-up').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const index = parseInt(this.getAttribute('data-index'));
                if (index > 0) {
                    const temp = roteiroArray[index - 1];
                    roteiroArray[index - 1] = roteiroArray[index];
                    roteiroArray[index] = temp;
                    localStorage.setItem('meu_roteiro_customizado', JSON.stringify(roteiroArray));
                    renderCustomRoteiro();
                }
            });
        });

        // Listeners: Mover para Baixo
        container.querySelectorAll('.btn-move-down').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const index = parseInt(this.getAttribute('data-index'));
                if (index < roteiroArray.length - 1) {
                    const temp = roteiroArray[index + 1];
                    roteiroArray[index + 1] = roteiroArray[index];
                    roteiroArray[index] = temp;
                    localStorage.setItem('meu_roteiro_customizado', JSON.stringify(roteiroArray));
                    renderCustomRoteiro();
                }
            });
        });
    }

    const btnClearCustom = document.getElementById('btn-clear-custom-roteiro');
    if (btnClearCustom) {
        btnClearCustom.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja limpar todo o seu roteiro personalizado?')) {
                localStorage.removeItem('meu_roteiro_customizado');
                renderCustomRoteiro();
            }
        });
    }

    const btnRouteCustom = document.getElementById('btn-route-custom-roteiro');
    let routeCustomModal = null;
    if (document.getElementById('modal-route-custom-options')) {
        routeCustomModal = new bootstrap.Modal(document.getElementById('modal-route-custom-options'));
    }

    if (btnRouteCustom) {
        btnRouteCustom.addEventListener('click', function() {
            let roteiroArray = [];
            try { roteiroArray = JSON.parse(localStorage.getItem('meu_roteiro_customizado') || '[]'); } catch(e) {}
            
            const locations = roteiroArray.filter(i => i.lat && i.lng && i.lat !== 'null');
            
            if (locations.length === 0) {
                alert('Nenhuma atração com localização válida para traçar rota.');
                return;
            }

            if (routeCustomModal) {
                routeCustomModal.show();
            }
        });
    }

    document.querySelectorAll('.nav-custom-app-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const provider = this.getAttribute('data-provider');
            let roteiroArray = [];
            try { roteiroArray = JSON.parse(localStorage.getItem('meu_roteiro_customizado') || '[]'); } catch(e) {}
            const locations = roteiroArray.filter(i => i.lat && i.lng && i.lat !== 'null');
            
            if (locations.length === 0) return;

            let origin = null;
            if (window.LocationService) {
                origin = window.LocationService.getSavedLocation();
            }

            const destination = locations[locations.length - 1];
            let url = '';

            if (provider === 'google') {
                let originStr = '';
                if (origin && origin.lat && origin.lng) {
                    originStr = `&origin=${origin.lat},${origin.lng}`;
                }
                let waypointsStr = '';
                if (locations.length > 1) {
                    const waypoints = locations.slice(0, locations.length - 1).map(i => `${i.lat},${i.lng}`).join('|');
                    waypointsStr = `&waypoints=${waypoints}`;
                }
                url = `https://www.google.com/maps/dir/?api=1${originStr}&destination=${destination.lat},${destination.lng}${waypointsStr}&travelmode=driving`;
            } else if (provider === 'waze') {
                url = `https://waze.com/ul?ll=${destination.lat},${destination.lng}&navigate=yes`;
            } else if (provider === 'osm') {
                let coords = [];
                if (origin && origin.lat && origin.lng) {
                    coords.push(`${origin.lat}%2C${origin.lng}`);
                }
                locations.forEach(loc => {
                    coords.push(`${loc.lat}%2C${loc.lng}`);
                });
                const routeStr = coords.join('%3B');
                url = `https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=${routeStr}`;
            }

            window.open(url, '_blank');
            if (routeCustomModal) {
                routeCustomModal.hide();
            }
        });
    });

    renderCustomRoteiro();

    // Ouvir eventos globais caso adicione em outra aba e volte
    window.addEventListener('turismo:roteiro-updated', renderCustomRoteiro);
});
</script>
@endpush
@endsection
