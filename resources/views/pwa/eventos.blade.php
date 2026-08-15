@extends('layouts.pwa')

@section('content')

{{-- Barra de busca e filtros de eventos --}}
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 100; top: 0;">
    @if(request('from') === 'admin' || (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 'gestor_cadastros', 'atendente'])))
        <div class="mb-2">
            <a href="{{ route('admin.eventos.index') }}" class="btn btn-dark rounded-pill btn-sm px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 text-white border border-warning shadow-sm" style="background: #003844; font-size: 0.8rem;">
                <i class="bi bi-arrow-left text-warning"></i>
                <span>Voltar ao Painel de Eventos</span>
            </a>
        </div>
    @endif
    <div class="position-relative">
        <div class="position-absolute top-50 start-0 translate-middle-y ps-3" aria-hidden="true">
            <i class="bi bi-search text-secondary"></i>
        </div>
        <input
            type="text"
            id="eventos-search-input"
            class="form-control rounded-pill border-0 shadow-sm ps-5 bg-white"
            placeholder="Buscar eventos, festivais, feiras..."
            aria-label="Buscar eventos por nome ou tema"
            style="height: 48px;"
            autocomplete="off"
        >
    </div>

    {{-- Chips de filtro por tipo --}}
    <div class="d-flex gap-2 mt-3 overflow-auto no-scrollbar pb-1"
         style="margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem;">
        <button class="btn btn-primary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 evt-filter-btn active"
                data-filter="todos" style="min-height: 36px;">
            Todos
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white evt-filter-btn"
                data-filter="hoje" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">
            <i class="bi bi-calendar-day me-1"></i>Hoje
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white evt-filter-btn"
                data-filter="semana" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">
            <i class="bi bi-calendar-week me-1"></i>Essa Semana
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white evt-filter-btn"
                data-filter="gratuito" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">
            <i class="bi bi-tag me-1"></i>Gratuitos
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white"
                data-bs-toggle="offcanvas" data-bs-target="#eventosFiltrosAvancados"
                style="min-height: 36px; border-color: rgba(0,0,0,0.1);">
            <i class="bi bi-sliders text-secondary"></i>
        </button>
    </div>
</div>

{{-- Offcanvas: Filtros Avançados de Eventos --}}
<div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="eventosFiltrosAvancados"
     aria-labelledby="eventosFiltrosLabel" style="max-height: 85vh;">
    <div class="offcanvas-header border-bottom pb-3">
        <h5 class="offcanvas-title fw-bold" id="eventosFiltrosLabel">
            <i class="bi bi-sliders me-2 text-primary"></i>Filtrar Eventos
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body px-4 py-3">

        {{-- Período personalizado --}}
        <div class="mb-4">
            <label class="fw-semibold small text-dark mb-2 d-block">
                <i class="bi bi-calendar-range me-1 text-primary"></i>Período
            </label>
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <label class="text-muted" style="font-size: 0.75rem;">De</label>
                    <input type="date" class="form-control form-control-sm rounded-3 border-0 bg-light shadow-none"
                           id="filtro-data-inicio">
                </div>
                <div class="flex-grow-1">
                    <label class="text-muted" style="font-size: 0.75rem;">Até</label>
                    <input type="date" class="form-control form-control-sm rounded-3 border-0 bg-light shadow-none"
                           id="filtro-data-fim">
                </div>
            </div>
        </div>

        {{-- Gratuidade --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <label class="fw-semibold small text-dark" for="evt-filtro-gratuito">
                    <i class="bi bi-ticket-perforated me-1 text-primary"></i>
                    Apenas eventos gratuitos
                </label>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="evt-filtro-gratuito" role="switch">
                </div>
            </div>
        </div>

        {{-- Acessibilidade --}}
        <div class="mb-4">
            <label class="fw-semibold small text-dark mb-2 d-block">
                <i class="bi bi-universal-access-circle me-1 text-primary"></i>Acessibilidade
            </label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="evt-ac-cadeirante"
                       name="evt-acessivel" value="cadeirante">
                <label class="form-check-label small" for="evt-ac-cadeirante">
                    ♿ Acessível para cadeirantes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="evt-ac-libras"
                       name="evt-acessivel" value="libras">
                <label class="form-check-label small" for="evt-ac-libras">
                    🤟 Atendimento em Libras
                </label>
            </div>
        </div>

        <div class="d-flex gap-2 pt-3 border-top">
            <button id="evt-btn-limpar" class="btn btn-outline-secondary rounded-pill flex-grow-1 fw-semibold">
                Limpar
            </button>
            <button id="evt-btn-aplicar" class="btn btn-primary rounded-pill flex-grow-1 fw-bold"
                    data-bs-dismiss="offcanvas">
                Aplicar
            </button>
        </div>
    </div>
</div>

<div class="container-fluid px-3 py-4 mb-5">

    {{-- Contagem e título --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold fs-5 mb-0">Agenda de Eventos</h1>
        <span class="badge bg-primary rounded-pill px-3 py-1 small" id="eventos-count">
            Carregando...
        </span>
    </div>

    {{-- Lista de eventos em Grid (3 por linha no desktop) --}}
    <div id="eventos-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        {{-- Skeleton inicial --}}
        @for ($i = 0; $i < 6; $i++)
            <div class="col">
                <div class="card border-0 rounded-4 overflow-hidden skeleton-card h-100" style="min-height: 180px;">
                    <div class="card-body p-3 d-flex flex-column gap-2">
                        <div class="shimmer rounded" style="height: 12px; width: 30%;"></div>
                        <div class="shimmer rounded" style="height: 18px; width: 65%;"></div>
                        <div class="shimmer rounded" style="height: 12px; width: 45%;"></div>
                        <div class="shimmer rounded" style="height: 12px; width: 55%;"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    {{-- Controles de Paginação de Eventos --}}
    <div id="eventos-pagination-container" class="d-flex justify-content-center mt-4 d-none">
        <nav aria-label="Paginação de Eventos">
            <ul class="pagination pagination-sm rounded-pill shadow-sm" id="eventos-pagination-list"></ul>
        </nav>
    </div>

</div>

@endsection

@push('styles')
<style>
    @keyframes shimmer {
        0%   { background-position: -800px 0; }
        100% { background-position: 800px 0; }
    }
    .shimmer {
        background: linear-gradient(to right, #e9ecef 8%, #d1d5db 18%, #e9ecef 33%);
        background-size: 800px 100%;
        animation: shimmer 1.5s infinite linear;
    }
    .skeleton-card {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04) !important;
    }
    .evt-filter-btn.active {
        background-color: var(--bs-primary) !important;
        color: white !important;
        border-color: var(--bs-primary) !important;
    }
    .place-hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .place-hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // ESTADO DOS FILTROS E PAGINAÇÃO
    // =========================================================================
    const state = {
        query: '',
        filtro: 'todos',     // 'todos' | 'hoje' | 'semana' | 'gratuito'
        dataInicio: null,
        dataFim: null,
        gratuito: false,
        acessivel: null,
        page: 1,
        perPage: 9,
    };

    let allFilteredEvents = [];

    // =========================================================================
    // REFERÊNCIAS DOM
    // =========================================================================
    const searchInput      = document.getElementById('eventos-search-input');
    const container        = document.getElementById('eventos-container');
    const countBadge       = document.getElementById('eventos-count');
    const filterBtns       = document.querySelectorAll('.evt-filter-btn');
    const dataInicio       = document.getElementById('filtro-data-inicio');
    const dataFim          = document.getElementById('filtro-data-fim');
    const gratuitoToggle   = document.getElementById('evt-filtro-gratuito');
    const btnLimpar        = document.getElementById('evt-btn-limpar');
    const btnAplicar       = document.getElementById('evt-btn-aplicar');
    const paginationContainer = document.getElementById('eventos-pagination-container');
    const paginationList   = document.getElementById('eventos-pagination-list');

    let searchDebounce = null;

    // =========================================================================
    // BUSCA NA API
    // =========================================================================

    /**
     * Monta query params e chama /api/v1/eventos.
     */
    async function fetchEventos() {
        const params = new URLSearchParams({ status: 'ativo' });

        // Filtros rápidos (chips)
        const hoje = new Date().toISOString().split('T')[0];
        const fimSemana = new Date(Date.now() + 7 * 86400000).toISOString().split('T')[0];

        if (state.filtro === 'hoje') {
            params.append('data_inicio', hoje);
            params.append('data_fim', hoje);
        } else if (state.filtro === 'semana') {
            params.append('data_inicio', hoje);
            params.append('data_fim', fimSemana);
        } else if (state.filtro === 'gratuito') {
            params.append('gratuito', '1');
        }

        // Filtros avançados (offcanvas)
        if (state.dataInicio) params.append('data_inicio', state.dataInicio);
        if (state.dataFim)    params.append('data_fim', state.dataFim);
        if (state.gratuito)   params.append('gratuito', '1');

        const response = await fetch(`/api/v1/eventos?${params.toString()}`);
        if (!response.ok) throw new Error('Erro ao buscar eventos');
        return response.json();
    }

    // =========================================================================
    // RENDERIZAÇÃO
    // =========================================================================

    /**
     * Formata uma data ISO para exibição amigável.
     * @param {string} iso
     * @returns {string}
     */
    function formatarData(iso) {
        if (!iso) return '';
        const d = new Date(iso);
        return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    /**
     * Formata horário a partir de ISO.
     * @param {string} iso
     * @returns {string}
     */
    function formatarHorario(iso) {
        if (!iso) return '';
        const d = new Date(iso);
        return d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    }

    /**
     * Renderiza um card de evento na lista.
     * @param {Object} evento
     * @returns {string}
     */
    function renderEventoCard(evento) {
        const dataLabel     = formatarData(evento.inicio);
        const horarioInicio = formatarHorario(evento.inicio);
        const horarioFim    = evento.fim ? formatarHorario(evento.fim) : '';
        const horarioStr    = horarioFim ? `${horarioInicio} – ${horarioFim}` : horarioInicio;

        return `
            <div class="col">
                <div class="card border-0 rounded-4 overflow-hidden place-hover-card bg-white h-100 d-flex flex-column"
                     style="box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);">
                    <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge rounded-pill fw-semibold ${evento.gratuito ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border'}"
                                      style="font-size: 0.72rem;">
                                    ${evento.gratuito ? '🎟 Gratuito' : '🎫 Pago'}
                                </span>
                                ${evento.faixa_etaria
                                    ? `<span class="badge bg-light text-dark border rounded-pill" style="font-size: 0.68rem;">${evento.faixa_etaria}</span>`
                                    : ''}
                            </div>

                            <h3 class="fw-bold mb-2 text-dark" style="font-size: 1rem; line-height: 1.35;">${evento.nome}</h3>

                            ${evento.descricao
                                ? `<p class="text-secondary small mb-3" style="font-size: 0.82rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">${evento.descricao}</p>`
                                : ''}
                        </div>

                        <div class="d-flex flex-column gap-1.5 pt-2 border-top mt-auto">
                            ${dataLabel ? `
                            <div class="d-flex align-items-center gap-2 small text-secondary" style="font-size: 0.78rem;">
                                <i class="bi bi-calendar3 text-primary"></i>
                                <span>${dataLabel}${horarioStr ? ' · ' + horarioStr : ''}</span>
                            </div>` : ''}

                            ${evento.local ? `
                            <div class="d-flex align-items-center gap-2 small text-secondary" style="font-size: 0.78rem;">
                                <i class="bi bi-geo-alt text-primary"></i>
                                <span class="text-truncate">${evento.local}</span>
                            </div>` : ''}

                            ${evento.organizador ? `
                            <div class="d-flex align-items-center gap-2 small text-secondary" style="font-size: 0.78rem;">
                                <i class="bi bi-person text-primary"></i>
                                <span class="text-truncate">${evento.organizador}</span>
                            </div>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /** Exibe skeleton de carregamento */
    function showSkeleton() {
        container.innerHTML = Array.from({ length: 6 }, () => `
            <div class="col">
                <div class="card border-0 rounded-4 overflow-hidden skeleton-card h-100" style="min-height: 180px;">
                    <div class="card-body p-3 d-flex flex-column gap-2">
                        <div class="shimmer rounded" style="height: 12px; width: 30%;"></div>
                        <div class="shimmer rounded" style="height: 18px; width: 65%;"></div>
                        <div class="shimmer rounded" style="height: 12px; width: 45%;"></div>
                        <div class="shimmer rounded" style="height: 12px; width: 55%;"></div>
                    </div>
                </div>
            </div>
        `).join('');
        countBadge.textContent = '...';
        if (paginationContainer) paginationContainer.classList.add('d-none');
    }

    /** Exibe estado vazio */
    function showEmpty() {
        container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border p-4">
                    <i class="bi bi-calendar-x text-muted display-4"></i>
                    <h4 class="fw-bold fs-6 mt-3">Nenhum evento encontrado</h4>
                    <p class="small text-secondary mb-0">Tente outro período ou ajuste os filtros.</p>
                </div>
            </div>
        `;
        countBadge.textContent = '0';
        if (paginationContainer) paginationContainer.classList.add('d-none');
    }

    /** Renderiza lista paginada */
    function renderCurrentPage() {
        const total = allFilteredEvents.length;
        const totalPages = Math.ceil(total / state.perPage) || 1;
        if (state.page > totalPages) state.page = totalPages;
        if (state.page < 1) state.page = 1;

        const startIdx = (state.page - 1) * state.perPage;
        const pageItems = allFilteredEvents.slice(startIdx, startIdx + state.perPage);

        container.innerHTML = pageItems.map(renderEventoCard).join('');

        if (totalPages > 1 && paginationContainer && paginationList) {
            paginationContainer.classList.remove('d-none');
            let pagesHtml = '';
            
            // Botão Anterior
            pagesHtml += `
                <li class="page-item ${state.page === 1 ? 'disabled' : ''}">
                    <a class="page-link rounded-start-pill" href="#" data-page="${state.page - 1}">Anterior</a>
                </li>
            `;

            for (let p = 1; p <= totalPages; p++) {
                pagesHtml += `
                    <li class="page-item ${state.page === p ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${p}">${p}</a>
                    </li>
                `;
            }

            // Botão Próximo
            pagesHtml += `
                <li class="page-item ${state.page === totalPages ? 'disabled' : ''}">
                    <a class="page-link rounded-end-pill" href="#" data-page="${state.page + 1}">Próximo</a>
                </li>
            `;

            paginationList.innerHTML = pagesHtml;

            paginationList.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetPage = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== state.page) {
                        state.page = targetPage;
                        renderCurrentPage();
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                });
            });
        } else if (paginationContainer) {
            paginationContainer.classList.add('d-none');
        }
    }

    /** Filtra eventos localmente por query de busca */
    function filtrarPorBusca(eventos) {
        const q = state.query.trim().toLowerCase();
        if (!q) return eventos;
        return eventos.filter(e =>
            e.nome?.toLowerCase().includes(q) ||
            e.descricao?.toLowerCase().includes(q) ||
            e.local?.toLowerCase().includes(q) ||
            e.organizador?.toLowerCase().includes(q)
        );
    }

    // =========================================================================
    // FUNÇÃO PRINCIPAL
    // =========================================================================
    async function loadEventos() {
        showSkeleton();
        try {
            const data   = await fetchEventos();
            const todos  = data.data ?? [];
            allFilteredEvents = filtrarPorBusca(todos);

            countBadge.textContent = `${allFilteredEvents.length} evento${allFilteredEvents.length !== 1 ? 's' : ''}`;

            if (allFilteredEvents.length === 0) {
                showEmpty();
                return;
            }

            state.page = 1;
            renderCurrentPage();
        } catch (err) {
            console.error('[Eventos] Erro:', err);
            container.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 border p-4">
                        <i class="bi bi-wifi-off text-muted display-4"></i>
                        <h4 class="fw-bold fs-6 mt-3">Erro ao carregar eventos</h4>
                        <p class="small text-secondary mb-0">Verifique sua conexão.</p>
                    </div>
                </div>
            `;
            countBadge.textContent = '—';
            if (paginationContainer) paginationContainer.classList.add('d-none');
        }
    }

    // =========================================================================
    // EVENT LISTENERS
    // =========================================================================

    // Busca com debounce
    searchInput?.addEventListener('input', function (e) {
        state.query = e.target.value;
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(loadEventos, 350);
    });

    // Chips de filtro rápido
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary', 'active', 'text-white');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary', 'active', 'text-white');

            state.filtro = this.getAttribute('data-filter');
            loadEventos();
        });
    });

    // Filtros avançados
    btnAplicar?.addEventListener('click', function () {
        state.dataInicio = dataInicio?.value || null;
        state.dataFim    = dataFim?.value    || null;
        state.gratuito   = gratuitoToggle?.checked ?? false;
        loadEventos();
    });

    btnLimpar?.addEventListener('click', function () {
        state.dataInicio = null;
        state.dataFim    = null;
        state.gratuito   = false;
        if (dataInicio)    dataInicio.value    = '';
        if (dataFim)       dataFim.value       = '';
        if (gratuitoToggle) gratuitoToggle.checked = false;
        loadEventos();
    });

    // =========================================================================
    // INICIALIZAÇÃO
    // =========================================================================
    loadEventos();
});
</script>
@endpush
