@extends('layouts.admin')

@section('title', 'Auditoria & Logs Governamentais')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Trilha de Auditoria & Governança</h4>
        <p class="text-muted small mb-0">Rastreabilidade completa de ações de gestores, alterações cadastrais, consultas da IA e conformidade com a LGPD.</p>
    </div>
    <div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold">
            <i class="bi bi-shield-lock-fill me-1"></i> Trilha Imutável de Auditoria
        </span>
    </div>
</div>

<!-- Abas de Navegação -->
<ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-4 shadow-sm border" id="auditTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="audits-tab" data-bs-toggle="pill" data-bs-target="#audits-pane" type="button" role="tab">
            <i class="bi bi-journal-check"></i> Trilha de Ações do Sistema
            <span class="badge bg-primary text-white rounded-pill ms-1">{{ is_countable($audits) ? count($audits) : $audits->total() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="ia-tab" data-bs-toggle="pill" data-bs-target="#ia-pane" type="button" role="tab">
            <i class="bi bi-robot"></i> Consultas & Logs IA
            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ is_countable($logs) ? count($logs) : $logs->total() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics-pane" type="button" role="tab">
            <i class="bi bi-graph-up-arrow"></i> Telemetria & Analytics
            <span class="badge bg-success-subtle text-success rounded-pill ms-1">{{ count($analytics) }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="auditTabsContent">
    <!-- Aba 1: Trilha de Ações (Audits) -->
    <div class="tab-pane fade show active" id="audits-pane" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Data / Hora</th>
                            <th>Responsável</th>
                            <th>Ação</th>
                            <th>Módulo / Registro</th>
                            <th>IP de Origem</th>
                            <th class="text-end pe-4">Alterações (Diff)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $audit)
                            <tr>
                                <td class="ps-4 small text-muted font-monospace">
                                    {{ $audit->created_at ? $audit->created_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold small" style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($audit->user?->name ?? 'Sistema', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="small fw-bold text-dark">{{ $audit->user?->name ?? 'Sistema Automatizado' }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">{{ $audit->user?->email ?? 'daemon' }} • <span class="badge bg-light text-dark border">{{ $audit->user?->role ?? 'admin' }}</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($audit->event === 'created')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1"><i class="bi bi-plus-circle me-1"></i>Criado</span>
                                    @elseif($audit->event === 'updated')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1"><i class="bi bi-pencil-square me-1"></i>Atualizado</span>
                                    @elseif($audit->event === 'deleted')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1"><i class="bi bi-trash me-1"></i>Excluído</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">{{ ucfirst($audit->event) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small fw-bold text-dark font-monospace">
                                        {{ class_basename($audit->auditable_type ?? 'Registro') }} #{{ $audit->auditable_id }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-muted font-monospace" style="font-size: 0.75rem;">
                                        {{ $audit->ip_address ?? '127.0.0.1' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalDiff{{ $audit->id }}">
                                        <i class="bi bi-code-square me-1 text-primary"></i> Ver Diff
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-shield-check fs-1 d-block mb-2 text-primary opacity-50"></i>
                                    Nenhuma alteração registrada na trilha de auditoria até o momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modais de Diff de Auditoria -->
            @foreach($audits as $audit)
                <div class="modal fade text-start" id="modalDiff{{ $audit->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                <h5 class="modal-title fw-bold">Detalhes da Auditoria #{{ $audit->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="small text-muted">Ação Executada:</div>
                                        <div class="fw-bold text-uppercase">{{ $audit->event }} em {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Usuário / IP:</div>
                                        <div class="fw-bold">{{ $audit->user?->name ?? 'Sistema' }} ({{ $audit->ip_address ?? 'N/A' }})</div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold small text-danger"><i class="bi bi-dash-circle me-1"></i>Valores Anteriores (Old)</label>
                                        <pre class="bg-light p-3 rounded-3 small font-monospace border" style="max-height: 220px; overflow-y: auto;">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Nenhum valor anterior (novo registro)' }}</pre>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold small text-success"><i class="bi bi-plus-circle me-1"></i>Valores Novos (New)</label>
                                        <pre class="bg-light p-3 rounded-3 small font-monospace border" style="max-height: 220px; overflow-y: auto;">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Registro removido' }}</pre>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(method_exists($audits, 'hasPages') && $audits->hasPages())
                <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Exibindo {{ $audits->firstItem() }} até {{ $audits->lastItem() }} de {{ $audits->total() }} auditorias</span>
                    <div>{{ $audits->links('pagination::bootstrap-5') }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Aba 2: Logs do Assistente IA -->
    <div class="tab-pane fade" id="ia-pane" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Data / Hora</th>
                            <th>Consulta do Turista</th>
                            <th>Resposta / Roteiro Sugerido</th>
                            <th>Conformidade LGPD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 small text-muted font-monospace">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ Str::limit($log->prompt ?? $log->mensagem ?? 'Consulta de Roteiro', 65) }}</div>
                                </td>
                                <td>
                                    <div class="small text-muted">{{ Str::limit($log->resposta ?? 'Roteiro e atrações geradas com sucesso', 75) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                        <i class="bi bi-shield-fill-check me-1"></i> PII Anonimizado
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-robot fs-1 d-block mb-2 text-muted opacity-50"></i>
                                    Nenhum log de IA registrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Total de {{ $logs->total() }} interações com a IA</span>
                    <div>{{ $logs->links('pagination::bootstrap-5') }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Aba 3: Telemetria & Analytics -->
    <div class="tab-pane fade" id="analytics-pane" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Data / Hora</th>
                            <th>Tipo de Evento</th>
                            <th>Detalhes do Engajamento</th>
                            <th>Dispositivo / Canal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics as $event)
                            <tr>
                                <td class="ps-4 small text-muted font-monospace">
                                    {{ $event->created_at ? $event->created_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2.5 py-1 font-monospace">
                                        {{ $event->evento ?? 'page_view' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-dark">{{ is_array($event->dados) ? json_encode($event->dados) : ($event->dados ?? 'Visualização de Ponto Turístico') }}</span>
                                </td>
                                <td>
                                    <span class="small text-muted"><i class="bi bi-phone me-1"></i>PWA Mobile</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-graph-up fs-1 d-block mb-2 text-muted opacity-50"></i>
                                    Nenhum evento analítico registrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
