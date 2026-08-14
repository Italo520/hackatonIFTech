@extends('layouts.pwa')

@section('content')

{{-- Barra de busca e filtros de eventos --}}
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 1020;">
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

<div class="container-fluid px-3 py-4">

    {{-- Contagem e título --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold fs-5 mb-0">Agenda de Eventos</h1>
        <span class="badge bg-primary rounded-pill px-3 py-1 small" id="eventos-count">
            Carregando...
        </span>
    </div>

    {{-- Lista de eventos --}}
    <div id="eventos-container" class="d-flex flex-column gap-3">
        {{-- Skeleton inicial --}}
        @for ($i = 0; $i < 3; $i++)
            <div class="card border-0 rounded-4 overflow-hidden skeleton-card" style="height: 120px;">
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <div class="shimmer rounded" style="height: 12px; width: 30%;"></div>
                    <div class="shimmer rounded" style="height: 18px; width: 65%;"></div>
                    <div class="shimmer rounded" style="height: 12px; width: 45%;"></div>
                    <div class="shimmer rounded" style="height: 12px; width: 55%;"></div>
                </div>
            </div>
        @endfor
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // ESTADO DOS FILTROS
    // =========================================================================
    const state = {
        query: '',
        filtro: 'todos',     // 'todos' | 'hoje' | 'semana' | 'gratuito'
        dataInicio: null,
        dataFim: null,
        gratuito: false,
        acessivel: null,
    };

    // =========================================================================
    // REFERÊNCIAS DOM
    // =========================================================================
    const searchInput    = document.getElementById('eventos-search-input');
    const container      = document.getElementById('eventos-container');
    const countBadge     = document.getElementById('eventos-count');
    const filterBtns     = document.querySelectorAll('.evt-filter-btn');
    const dataInicio     = document.getElementById('filtro-data-inicio');
    const dataFim        = document.getElementById('filtro-data-fim');
    const gratuitoToggle = document.getElementById('evt-filtro-gratuito');
    const btnLimpar      = document.getElementById('evt-btn-limpar');
    const btnAplicar     = document.getElementById('evt-btn-aplicar');

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
        return d.toLocaleDateString('pt-BR', {
            weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
        });
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
        const dataLabel    = formatarData(evento.inicio);
        const horarioInicio = formatarHorario(evento.inicio);
        const horarioFim    = evento.fim ? formatarHorario(evento.fim) : '';
        const horarioStr    = horarioFim ? `${horarioInicio} – ${horarioFim}` : horarioInicio;

        return `
            <div class="card border-0 rounded-4 overflow-hidden"
                 style="box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge rounded-pill fw-semibold ${evento.gratuito ? 'text-bg-success' : 'text-bg-secondary'} bg-opacity-15"
                              style="font-size: 0.7rem; opacity: 0.85;">
                            ${evento.gratuito ? '🎟 Gratuito' : '🎫 Pago'}
                        </span>
                        ${evento.faixa_etaria
                            ? `<span class="badge bg-light text-dark border rounded-pill" style="font-size: 0.65rem;">${evento.faixa_etaria}</span>`
                            : ''}
                    </div>

                    <h3 class="fw-bold mb-2" style="font-size: 1rem; line-height: 1.3;">${evento.nome}</h3>

                    ${evento.descricao
                        ? `<p class="text-secondary small mb-2 text-truncate" style="font-size: 0.82rem;">${evento.descricao}</p>`
                        : ''}

                    <div class="d-flex flex-column gap-1 mt-2">
                        ${dataLabel ? `
                        <div class="d-flex align-items-center gap-2 small text-secondary">
                            <i class="bi bi-calendar3 text-primary" style="width: 16px;"></i>
                            <span>${dataLabel}${horarioStr ? ' · ' + horarioStr : ''}</span>
                        </div>` : ''}

                        ${evento.local ? `
                        <div class="d-flex align-items-center gap-2 small text-secondary">
                            <i class="bi bi-geo-alt text-primary" style="width: 16px;"></i>
                            <span class="text-truncate">${evento.local}</span>
                        </div>` : ''}

                        ${evento.organizador ? `
                        <div class="d-flex align-items-center gap-2 small text-secondary">
                            <i class="bi bi-person text-primary" style="width: 16px;"></i>
                            <span>${evento.organizador}</span>
                        </div>` : ''}

                        ${evento.capacidade ? `
                        <div class="d-flex align-items-center gap-2 small text-secondary">
                            <i class="bi bi-people text-primary" style="width: 16px;"></i>
                            <span>Capacidade: ${evento.capacidade} pessoas</span>
                        </div>` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    /** Exibe skeleton de carregamento */
    function showSkeleton() {
        container.innerHTML = Array.from({ length: 3 }, () => `
            <div class="card border-0 rounded-4 overflow-hidden skeleton-card" style="height: 120px;">
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <div class="shimmer rounded" style="height: 12px; width: 30%;"></div>
                    <div class="shimmer rounded" style="height: 18px; width: 65%;"></div>
                    <div class="shimmer rounded" style="height: 12px; width: 45%;"></div>
                    <div class="shimmer rounded" style="height: 12px; width: 55%;"></div>
                </div>
            </div>
        `).join('');
        countBadge.textContent = '...';
    }

    /** Exibe estado vazio */
    function showEmpty() {
        container.innerHTML = `
            <div class="text-center py-5 bg-white rounded-4 border p-4">
                <i class="bi bi-calendar-x text-muted display-4"></i>
                <h4 class="fw-bold fs-6 mt-3">Nenhum evento encontrado</h4>
                <p class="small text-secondary mb-0">Tente outro período ou ajuste os filtros.</p>
            </div>
        `;
        countBadge.textContent = '0';
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
            const items  = filtrarPorBusca(todos);

            countBadge.textContent = `${items.length} evento${items.length !== 1 ? 's' : ''}`;

            if (items.length === 0) {
                showEmpty();
                return;
            }
            container.innerHTML = items.map(renderEventoCard).join('');
        } catch (err) {
            console.error('[Eventos] Erro:', err);
            container.innerHTML = `
                <div class="text-center py-5 bg-white rounded-4 border p-4">
                    <i class="bi bi-wifi-off text-muted display-4"></i>
                    <h4 class="fw-bold fs-6 mt-3">Erro ao carregar eventos</h4>
                    <p class="small text-secondary mb-0">Verifique sua conexão.</p>
                </div>
            `;
            countBadge.textContent = '—';
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
