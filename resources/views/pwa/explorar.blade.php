@extends('layouts.pwa')

@push('styles')
<style>
    .explorar-hero-search {
        background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);
        border-bottom-left-radius: 24px;
        border-bottom-right-radius: 24px;
    }
    .place-img-wrapper {
        position: relative;
        height: 180px;
        overflow: hidden;
    }
    .place-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .card-place:hover .place-img-wrapper img {
        transform: scale(1.05);
    }
    .filter-chip {
        white-space: nowrap;
        font-size: 0.82rem;
        transition: all 0.2s ease;
        border-radius: 50rem;
    }
    .filter-chip.active {
        background-color: var(--bs-primary) !important;
        color: #ffffff !important;
        border-color: var(--bs-primary) !important;
        box-shadow: 0 4px 10px rgba(0, 95, 115, 0.25);
    }
</style>
@endpush

@section('content')
<!-- Barra Superior de Pesquisa & IA -->
<div class="explorar-hero-search p-4 text-white shadow-sm mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="fw-bold fs-5 mb-0">Explorar Destinos</h1>
        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small">
            <i class="bi bi-geo-alt-fill text-warning me-1"></i> <span class="current-city-name">Sua Cidade</span>
        </span>
    </div>
    <p class="small text-white-50 mb-3">Encontre pontos turísticos, praias, monumentos e sabores</p>

    <!-- Campo de Busca -->
    <div class="position-relative">
        <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary">
            <i class="bi bi-search"></i>
        </div>
        <input type="text" id="input-busca-explorar" class="form-control form-control-lg rounded-pill border-0 shadow-sm ps-5 pe-5 bg-white text-dark" placeholder="Buscar praias, museus, restaurantes..." autocomplete="off">
        <button type="button" id="btn-limpar-busca" class="btn btn-sm text-secondary position-absolute top-50 end-0 translate-middle-y me-2 d-none">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    </div>
</div>

<!-- Filtros Rápidos (Chips Horizontais) -->
<div class="px-3 mb-3">
    <div class="d-flex gap-2 overflow-auto no-scrollbar pb-1" id="chips-categorias-container">
        <button class="btn btn-light border filter-chip active" data-cat="todos">
            <i class="bi bi-grid-fill me-1 text-primary"></i> Todos
        </button>
        <button class="btn btn-light border filter-chip" data-cat="praias">
            <i class="bi bi-water me-1 text-info"></i> Praias & Piscinas
        </button>
        <button class="btn btn-light border filter-chip" data-cat="gastronomia">
            <i class="bi bi-cup-hot-fill me-1 text-warning"></i> Gastronomia
        </button>
        <button class="btn btn-light border filter-chip" data-cat="historico">
            <i class="bi bi-bank me-1 text-danger"></i> História & Cultura
        </button>
        <button class="btn btn-light border filter-chip" data-cat="ecoturismo">
            <i class="bi bi-tree-fill me-1 text-success"></i> Ecoturismo
        </button>
        <button class="btn btn-light border filter-chip" data-cat="acessivel">
            <i class="bi bi-universal-access-circle me-1 text-primary"></i> ♿ Acessíveis
        </button>
        <button class="btn btn-outline-secondary filter-chip" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFiltros">
            <i class="bi bi-sliders me-1"></i> Filtros
        </button>
    </div>
</div>

<!-- Resultados Dinâmicos -->
<div class="container-fluid px-3 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fs-6 fw-bold text-dark mb-0">
            <span id="contador-resultados">Carregando</span> atrativos
        </h2>
        <div class="dropdown">
            <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-sort-down me-1"></i> <span id="label-ordem">Mais Próximos</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                <li><a class="dropdown-item small opt-ordem active" href="#" data-ordem="distancia">Mais Próximos (GPS)</a></li>
                <li><a class="dropdown-item small opt-ordem" href="#" data-ordem="rating">Melhor Avaliados</a></li>
                <li><a class="dropdown-item small opt-ordem" href="#" data-ordem="nome">Ordem Alfabética</a></li>
            </ul>
        </div>
    </div>

    <!-- Grid de Atrativos -->
    <div class="row g-3" id="grid-atrativos-explorar">
        <!-- Preenchido via JavaScript -->
    </div>

    <!-- Estado Vazio / Sem Resultados -->
    <div id="empty-state-explorar" class="text-center py-5 d-none">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 bg-light text-muted mb-3" style="width: 64px; height: 64px;">
            <i class="bi bi-search fs-3"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Nenhum local encontrado</h5>
        <p class="text-muted small mb-3">Tente buscar por termos mais genéricos ou limpe os filtros.</p>
        <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-sm" id="btn-reset-filtros">
            Limpar Filtros
        </button>
    </div>
</div>

<!-- Offcanvas de Filtros Avançados -->
<div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="offcanvasFiltros" style="max-height: 85vh;">
    <div class="offcanvas-header border-bottom pb-3">
        <h5 class="offcanvas-title fw-bold text-dark">
            <i class="bi bi-sliders me-2 text-primary"></i>Filtros Avançados
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-4">
        <!-- Acessibilidade e Inclusão -->
        <div class="mb-4">
            <label class="fw-bold small text-secondary mb-2 d-block text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                Acessibilidade (WCAG / Inclusão)
            </label>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input class="form-check-input filter-check-ac" type="checkbox" value="cadeirante" id="chk-cadeirante">
                    <label class="form-check-label small text-dark" for="chk-cadeirante">♿ Acessível para cadeirantes / rampas</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-check-ac" type="checkbox" value="libras" id="chk-libras">
                    <label class="form-check-label small text-dark" for="chk-libras">🤟 Atendimento ou suporte em Libras</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-check-ac" type="checkbox" value="cego" id="chk-cego">
                    <label class="form-check-label small text-dark" for="chk-cego">🦯 Audiodescrição / Piso tátil</label>
                </div>
            </div>
        </div>

        <!-- Duração da Visita -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="fw-bold small text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Duração Máxima</label>
                <span class="badge bg-light text-primary border" id="badge-duracao-valor">Qualquer tempo</span>
            </div>
            <input type="range" class="form-range" min="30" max="360" step="30" id="range-duracao" value="360">
            <div class="d-flex justify-content-between text-muted" style="font-size: 0.7rem;">
                <span>30 min</span>
                <span>2h</span>
                <span>4h</span>
                <span>Livre</span>
            </div>
        </div>

        <div class="d-flex gap-2 pt-2">
            <button type="button" class="btn btn-light rounded-pill flex-grow-1" id="btn-limpar-offcanvas" data-bs-dismiss="offcanvas">Limpar</button>
            <button type="button" class="btn btn-primary rounded-pill flex-grow-1 fw-bold" id="btn-aplicar-offcanvas" data-bs-dismiss="offcanvas">Aplicar Filtros</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCategory = 'todos';
    let searchQuery = '';
    let sortOrder = 'distancia';
    let accessibilityFilters = [];
    let maxDuration = 360;

    const inputBusca = document.getElementById('input-busca-explorar');
    const btnLimparBusca = document.getElementById('btn-limpar-busca');
    const gridAtrativos = document.getElementById('grid-atrativos-explorar');
    const emptyState = document.getElementById('empty-state-explorar');
    const contadorResultados = document.getElementById('contador-resultados');
    const chipsContainer = document.getElementById('chips-categorias-container');
    const rangeDuracao = document.getElementById('range-duracao');
    const badgeDuracao = document.getElementById('badge-duracao-valor');

    function renderAtrativos() {
        if (!window.LocationService || !window.LocationService.getAttractionsByCity) {
            setTimeout(renderAtrativos, 100);
            return;
        }

        const city = window.LocationService.getCurrentCity();
        let list = window.LocationService.getAttractionsByCity(city.key) || [];
        const userLoc = window.LocationService.getUserCoordinates();

        // 1. Filtro de Categoria
        if (currentCategory === 'praias') {
            list = list.filter(i => (i.categoria_slug || '').includes('praia') || (i.categoria_slug || '').includes('rios') || (i.categoria_slug || '').includes('gruta'));
        } else if (currentCategory === 'gastronomia') {
            list = list.filter(i => (i.categoria_slug || '').includes('gastro') || (i.categoria_slug || '').includes('restaurante'));
        } else if (currentCategory === 'historico') {
            list = list.filter(i => (i.categoria_slug || '').includes('cultura') || (i.categoria_slug || '').includes('monumento') || (i.categoria_slug || '').includes('histor'));
        } else if (currentCategory === 'ecoturismo') {
            list = list.filter(i => (i.categoria_slug || '').includes('aventura') || (i.categoria_slug || '').includes('eco') || (i.categoria_slug || '').includes('parque'));
        } else if (currentCategory === 'acessivel') {
            list = list.filter(i => i.acessibilidade && i.acessibilidade.length > 0);
        }

        // 2. Filtro de Acessibilidade
        if (accessibilityFilters.length > 0) {
            list = list.filter(i => {
                if (!i.acessibilidade) return false;
                return accessibilityFilters.every(f => i.acessibilidade.includes(f));
            });
        }

        // 3. Filtro de Busca por Palavra-chave / Linguagem Natural
        if (searchQuery.trim().length > 0) {
            const q = searchQuery.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            list = list.filter(i => {
                const nome = (i.nome || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const desc = (i.descricao || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const cat = (i.categoria || '').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                return nome.includes(q) || desc.includes(q) || cat.includes(q);
            });
        }

        // 4. Filtro de Duração
        if (maxDuration < 360) {
            list = list.filter(i => !i.tempo_medio || i.tempo_medio <= maxDuration);
        }

        // 5. Cálculo de Distâncias
        list.forEach(i => {
            if (userLoc && i.lat && i.lng) {
                const dist = window.LocationService.calculateDistanceKm(userLoc.lat, userLoc.lng, i.lat, i.lng);
                i.distancia_km = dist;
                i.distancia_formatada = window.LocationService.formatDistance(dist);
            } else {
                i.distancia_km = null;
                i.distancia_formatada = null;
            }
        });

        // 6. Ordenação
        if (sortOrder === 'distancia') {
            list.sort((a, b) => (a.distancia_km ?? 9999) - (b.distancia_km ?? 9999));
        } else if (sortOrder === 'rating') {
            list.sort((a, b) => (b.rating ?? 0) - (a.rating ?? 0));
        } else if (sortOrder === 'nome') {
            list.sort((a, b) => a.nome.localeCompare(b.nome));
        }

        // Atualizar Contador
        if (contadorResultados) contadorResultados.textContent = list.length;

        // Renderizar Cards
        if (list.length === 0) {
            gridAtrativos.innerHTML = '';
            emptyState.classList.remove('d-none');
        } else {
            emptyState.classList.add('d-none');
            gridAtrativos.innerHTML = list.map(item => `
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm bg-white card-place">
                        <div class="place-img-wrapper">
                            <img src="${item.imagem || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'}" alt="${item.nome}" loading="lazy">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2.5 py-1 small fw-semibold" style="backdrop-filter: blur(4px);">
                                    ${item.categoria || 'Atrativo'}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 small fw-bold shadow-sm">
                                    <i class="bi bi-star-fill small"></i> ${item.rating || '4.8'}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <h3 class="fw-bold text-dark fs-6 mb-1">${item.nome}</h3>
                                <p class="text-muted small mb-2" style="font-size: 0.78rem; line-height: 1.3;">
                                    ${item.descricao ? item.descricao.substring(0, 85) + '...' : ''}
                                </p>
                            </div>
                            <div class="pt-2 border-top d-flex align-items-center justify-content-between mt-2">
                                <span class="small text-secondary fw-semibold">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> ${item.distancia_formatada || 'Próximo'}
                                </span>
                                <a href="/atrativo/${item.id}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    Conhecer <i class="bi bi-arrow-right small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }

    // Eventos de Busca
    inputBusca?.addEventListener('input', function() {
        searchQuery = this.value;
        if (searchQuery.length > 0) {
            btnLimparBusca.classList.remove('d-none');
        } else {
            btnLimparBusca.classList.add('d-none');
        }
        renderAtrativos();
    });

    btnLimparBusca?.addEventListener('click', function() {
        inputBusca.value = '';
        searchQuery = '';
        btnLimparBusca.classList.add('d-none');
        renderAtrativos();
    });

    // Eventos de Categorias
    chipsContainer?.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            if (this.hasAttribute('data-bs-toggle')) return;
            chipsContainer.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-cat');
            renderAtrativos();
        });
    });

    // Eventos de Ordenação
    document.querySelectorAll('.opt-ordem').forEach(opt => {
        opt.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.opt-ordem').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            sortOrder = this.getAttribute('data-ordem');
            document.getElementById('label-ordem').textContent = this.textContent;
            renderAtrativos();
        });
    });

    // Filtros de Acessibilidade no Offcanvas
    document.getElementById('btn-aplicar-offcanvas')?.addEventListener('click', function() {
        accessibilityFilters = [];
        document.querySelectorAll('.filter-check-ac:checked').forEach(chk => {
            accessibilityFilters.push(chk.value);
        });
        maxDuration = parseInt(rangeDuracao.value, 10);
        renderAtrativos();
    });

    rangeDuracao?.addEventListener('input', function() {
        const val = parseInt(this.value, 10);
        badgeDuracao.textContent = val >= 360 ? 'Qualquer tempo' : `Até ${Math.floor(val/60)}h ${val%60 > 0 ? (val%60)+'min' : ''}`;
    });

    document.getElementById('btn-limpar-offcanvas')?.addEventListener('click', function() {
        document.querySelectorAll('.filter-check-ac').forEach(chk => chk.checked = false);
        accessibilityFilters = [];
        rangeDuracao.value = 360;
        badgeDuracao.textContent = 'Qualquer tempo';
        maxDuration = 360;
        renderAtrativos();
    });

    document.getElementById('btn-reset-filtros')?.addEventListener('click', function() {
        inputBusca.value = '';
        searchQuery = '';
        currentCategory = 'todos';
        accessibilityFilters = [];
        chipsContainer?.querySelectorAll('.filter-chip').forEach(c => {
            if (c.getAttribute('data-cat') === 'todos') c.classList.add('active');
            else c.classList.remove('active');
        });
        renderAtrativos();
    });

    // Ouvir troca de cidade global
    window.addEventListener('turismo:location-changed', renderAtrativos);

    renderAtrativos();
});
</script>
@endpush
@endsection
