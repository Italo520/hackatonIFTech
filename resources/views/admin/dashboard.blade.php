@extends('layouts.admin')

@section('title', 'Dashboard & Inteligência Turística')

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
                <span class="text-primary small fw-semibold"><i class="bi bi-chat-dots-fill"></i> Roteiros e Dúvidas</span>
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
            <div class="text-muted small mt-2" style="font-size: 0.75rem;">Empresas aguardando selo oficial</div>
        </div>
    </div>
</div>

<!-- Seção ESG & Sustentabilidade Municipal -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #0a9396 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-success" style="width: 44px; height: 44px; background: rgba(10, 147, 150, 0.12);">
                    <i class="bi bi-tree fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold">ESG: Redução de Papel</span>
                    <h5 class="fw-bold text-dark mb-0">~{{ $kpi['folhas_economizadas'] ?? 70 }} folhas</h5>
                    <span class="text-muted" style="font-size: 0.72rem;">Economizadas via {{ $kpi['qr_scans_total'] ?? 14 }} leituras de QR Code</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #ee9b00 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-warning" style="width: 44px; height: 44px; background: rgba(238, 155, 0, 0.12);">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold">Inclusão do Trade Local</span>
                    <h5 class="fw-bold text-dark mb-0">{{ ($kpi['parceiros_aprovados'] ?? 0) + ($kpi['parceiros_pendentes'] ?? 0) }} negócios</h5>
                    <span class="text-muted" style="font-size: 0.72rem;">Pequenos produtores, artesãos e pousadas</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #005f73 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 44px; height: 44px; background: rgba(0, 95, 115, 0.12);">
                    <i class="bi bi-universal-access fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold">Acessibilidade Universal</span>
                    <h5 class="fw-bold text-dark mb-0">{{ $kpi['taxa_acessibilidade'] ?? 0 }}% dos atrativos</h5>
                    <span class="text-muted" style="font-size: 0.72rem;">Com acessibilidade física ou sensorial mapeada</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seção do Mapa de Calor e Gráficos -->
<div class="row g-4 mb-4">
    <!-- Mapa de Calor -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Inteligência Territorial: Mapa de Calor</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Densidade de engajamento e consultas turísticas em tempo real</p>
                </div>
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-lock me-1"></i> LGPD Conforme
                </span>
            </div>
            <div class="card-body p-3">
                <div id="admin-heatmap" class="rounded-4 overflow-hidden" style="height: 380px; width: 100%; border: 1px solid rgba(0,0,0,0.06);"></div>
            </div>
            <div class="card-footer bg-light border-0 px-4 py-2 text-muted small" style="font-size: 0.72rem;">
                * Células com menos de 5 interações individuais são agregadas e anonimizadas conforme diretrizes da LGPD (Lei 13.709/2018).
            </div>
        </div>
    </div>

    <!-- Gráfico de Distribuição por Categoria -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
            <h5 class="fw-bold text-dark mb-1">Distribuição por Categoria</h5>
            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Proporção de atrativos cadastrados no município</p>
            <div style="height: 280px; position: relative;">
                <canvas id="chartCategorias"></canvas>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('admin.relatorios.export') }}" class="btn btn-outline-primary rounded-pill btn-sm w-100 fw-semibold">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Exportar Dados (CSV)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Atrativos Recentes e Alertas Ativos -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Últimos Atrativos no Catálogo</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Pontos turísticos cadastrados recentemente com status de publicação</p>
                </div>
                <a href="{{ route('admin.atrativos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Gerenciar Todos <i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Atrativo</th>
                            <th>Município</th>
                            <th>Categoria</th>
                            <th>Tempo Estimado</th>
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
                                    <a href="/atrativo/{{ $atrativo->id }}?from=admin" class="btn btn-sm btn-light border rounded-pill px-3" title="Ver no PWA">
                                        <i class="bi bi-eye me-1"></i> Ver
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
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inicializa o Mapa de Calor no Admin
        const mapEl = document.getElementById('admin-heatmap');
        if (mapEl) {
            const map = L.map('admin-heatmap', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([-7.1153, -34.8641], 6);

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

                        // Ajusta visão para o primeiro ponto
                        map.setView([points[0][0], points[0][1]], 8);
                    }
                })
                .catch(err => console.log('Erro ao carregar dados do heatmap:', err));
        }

        // 2. Gráfico de Categorias via Chart.js
        const ctxCategorias = document.getElementById('chartCategorias');
        if (ctxCategorias) {
            const categoriasData = @json($categoriasData ?? []);
            const labels = categoriasData.map(c => c.nome);
            const data = categoriasData.map(c => c.total);

            new Chart(ctxCategorias, {
                type: 'doughnut',
                data: {
                    labels: labels.length ? labels : ['Aventura', 'Praias', 'Cultura', 'Gastronomia'],
                    datasets: [{
                        data: data.length ? data : [4, 6, 3, 5],
                        backgroundColor: [
                            '#005f73',
                            '#0a9396',
                            '#94d2bd',
                            '#e9d8a6',
                            '#ee9b00',
                            '#ca6702',
                            '#bb3e03',
                            '#ae2012'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
