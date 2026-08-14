@extends('layouts.admin')

@section('title', 'Dashboard & Visão Geral')

@section('content')
<!-- Grid de Indicadores Principais (KPIs) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Atrativos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.5px;">Atrativos Ativos</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h2 class="fw-bold text-dark mb-0 fs-2">{{ $kpi['atrativos_ativos'] ?? 0 }}</h2>
                <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-right"></i> Monitorados</span>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.75rem;">Pontos turísticos no catálogo</div>
        </div>
    </div>

    <!-- Card 2: Eventos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.5px;">Eventos Oficiais</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(238, 155, 0, 0.1); color: #ee9b00;">
                    <i class="bi bi-calendar-event-fill fs-5"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h2 class="fw-bold text-dark mb-0 fs-2">{{ $kpi['eventos_ativos'] ?? 0 }}</h2>
                <span class="badge bg-warning-subtle text-dark border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Calendário</span>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.75rem;">Programação cultural e feiras</div>
        </div>
    </div>

    <!-- Card 3: Interações com IA -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.5px;">Assistente IA</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(10, 147, 150, 0.1); color: var(--bs-secondary);">
                    <i class="bi bi-stars fs-5"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h2 class="fw-bold text-dark mb-0 fs-2">{{ $kpi['ia_interacoes'] ?? 0 }}</h2>
                <span class="text-primary small fw-semibold"><i class="bi bi-chat-dots-fill"></i> Roteiros gerados</span>
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.75rem;">Consultas e recomendações IA</div>
        </div>
    </div>

    <!-- Card 4: Parceiros Pendentes -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.5px;">Fila de Validação</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(155, 34, 38, 0.1); color: #9b2226;">
                    <i class="bi bi-shop fs-5"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h2 class="fw-bold text-dark mb-0 fs-2">{{ $kpi['parceiros_pendentes'] ?? 0 }}</h2>
                @if(($kpi['parceiros_pendentes'] ?? 0) > 0)
                    <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Requer Ação</span>
                @else
                    <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">Em dia</span>
                @endif
            </div>
            <div class="text-muted small mt-2" style="font-size: 0.75rem;">Empresas aguardando selo</div>
        </div>
    </div>
</div>

<!-- Seção do Mapa de Calor e Ações Rápidas -->
<div class="row g-4 mb-4">
    <!-- Mapa de Calor -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Mapa de Calor: Concentração Turística</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Densidade de engajamento e consultas de turistas em tempo real</p>
                </div>
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-lock me-1"></i> LGPD Conforme
                </span>
            </div>
            <div class="card-body p-3">
                <div id="admin-heatmap" class="rounded-4 overflow-hidden" style="height: 380px; width: 100%; border: 1px solid rgba(0,0,0,0.06);"></div>
            </div>
            <div class="card-footer bg-light border-0 px-4 py-2 text-muted small" style="font-size: 0.72rem;">
                * Células com menos de 5 interações individuais são automaticamente agregadas para proteger a privacidade dos usuários.
            </div>
        </div>
    </div>

    <!-- Ações Rápidas & Alertas Ativos -->
    <div class="col-12 col-xl-4">
        <div class="d-flex flex-column gap-3 h-100">
            <!-- Atalhos de Ação Rápida -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Ações Rápidas</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.alertas.index') }}" class="btn btn-outline-danger text-start py-2.5 rounded-3 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-broadcast me-2"></i> Emitir Alerta de Emergência</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="{{ route('admin.atrativos.index') }}" class="btn btn-outline-primary text-start py-2.5 rounded-3 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-plus-circle me-2"></i> Gerenciar Atrativos</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="{{ url('/admin/prestadores') }}" class="btn btn-outline-success text-start py-2.5 rounded-3 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-check2-circle me-2"></i> Validar Empreendedores</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="{{ route('admin.relatorios.export') }}" class="btn btn-outline-secondary text-start py-2.5 rounded-3 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-download me-2"></i> Exportar Dados (CSV)</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>

            <!-- Alertas Recentes -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bell-fill text-danger me-1"></i> Alertas Ativos</h6>
                    <a href="{{ route('admin.alertas.index') }}" class="small text-primary text-decoration-none fw-semibold">Ver todos</a>
                </div>
                @if(isset($alertasRecentes) && $alertasRecentes->count() > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach($alertasRecentes as $alerta)
                            <div class="p-2.5 rounded-3 bg-light border-start border-danger border-3">
                                <div class="fw-bold small text-dark">{{ $alerta->titulo }}</div>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ Str::limit($alerta->corpo, 60) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-shield-check fs-2 text-success mb-2 d-block"></i>
                        Nenhum alerta crítico ativo no momento.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Últimos Atrativos Cadastrados -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0">Atrativos Turísticos Recentes</h5>
            <p class="text-muted small mb-0" style="font-size: 0.78rem;">Pontos turísticos registrados no banco de dados</p>
        </div>
        <a href="{{ route('admin.atrativos.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
            Ver Todos
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Atrativo</th>
                        <th>Município</th>
                        <th>Categoria</th>
                        <th>Tempo Médio</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimosAtrativos as $atrativo)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                        <i class="bi bi-geo-alt fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $atrativo->nome }}</div>
                                        <div class="text-muted small" style="font-size: 0.72rem;">{{ Str::limit($atrativo->endereco, 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $atrativo->municipio?->nome ?? 'Regional' }} - {{ $atrativo->municipio?->uf ?? 'BR' }}</span></td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $atrativo->categoria?->nome ?? 'Geral' }}</span></td>
                            <td><span class="small text-muted">{{ $atrativo->tempo_medio_visita ? $atrativo->tempo_medio_visita . ' min' : 'Livre' }}</span></td>
                            <td>
                                @if($atrativo->status === 'ativo')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Ativo</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1">{{ ucfirst($atrativo->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="/atrativo/{{ $atrativo->id }}" class="btn btn-sm btn-light border rounded-pill px-3" target="_blank" title="Ver no PWA">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Nenhum atrativo cadastrado recentemente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializa o Mapa de Calor no Admin
        const mapEl = document.getElementById('admin-heatmap');
        if (mapEl) {
            const map = L.map('admin-heatmap', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([-14.235, -51.925], 4);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            fetch('/admin/heatmap-data')
                .then(res => res.json())
                .then(points => {
                    if (points && points.length > 0) {
                        L.heatLayer(points, {
                            radius: 35,
                            blur: 20,
                            maxZoom: 12,
                            gradient: { 0.2: '#005f73', 0.5: '#0a9396', 0.8: '#ee9b00', 1.0: '#9b2226' }
                        }).addTo(map);

                        // Ajusta o zoom inicial para os primeiros pontos
                        map.setView([-7.1153, -34.8641], 7);
                    }
                })
                .catch(err => console.log('Erro ao carregar dados do heatmap:', err));
        }
    });
</script>
@endpush
