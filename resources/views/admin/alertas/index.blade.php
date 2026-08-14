@extends('layouts.admin')

@section('title', 'Alertas & Defesa Civil')

@section('content')
<div class="row g-4 mb-4">
    <!-- Formulário para Disparar Alerta -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(155, 34, 38, 0.1); color: #9b2226;">
                    <i class="bi bi-broadcast fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Emitir Comunicado / Alerta</h5>
                    <span class="text-muted small">Notificação transmitida em tempo real aos turistas no PWA</span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2 px-3 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3 small py-2 px-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.alertas.store') }}" class="d-flex flex-column gap-3">
                @csrf
                <div>
                    <label class="form-label fw-bold small text-secondary">Título do Alerta / Comunicado <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ex: Maré Alta / Alerta de Ressaca / Chuvas Fortes" required value="{{ old('titulo') }}">
                </div>

                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-secondary">Nível de Urgência <span class="text-danger">*</span></label>
                        <select name="urgencia" class="form-select rounded-3" required>
                            <option value="info" {{ old('urgencia') === 'info' ? 'selected' : '' }}>Informativo (Azul)</option>
                            <option value="aviso" {{ old('urgencia', 'aviso') === 'aviso' ? 'selected' : '' }}>Atenção / Meteorologia (Amarelo)</option>
                            <option value="urgente" {{ old('urgencia') === 'urgente' ? 'selected' : '' }}>Urgência / Defesa Civil (Vermelho)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-secondary">Duração da Notificação</label>
                        <select name="duracao_horas" class="form-select rounded-3">
                            <option value="6" {{ old('duracao_horas') == 6 ? 'selected' : '' }}>6 horas</option>
                            <option value="12" {{ old('duracao_horas') == 12 ? 'selected' : '' }}>12 horas</option>
                            <option value="24" {{ old('duracao_horas', 24) == 24 ? 'selected' : '' }}>24 horas (1 dia)</option>
                            <option value="48" {{ old('duracao_horas') == 48 ? 'selected' : '' }}>48 horas (2 dias)</option>
                            <option value="72" {{ old('duracao_horas') == 72 ? 'selected' : '' }}>72 horas (3 dias)</option>
                            <option value="168" {{ old('duracao_horas') == 168 ? 'selected' : '' }}>7 dias (1 semana)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label fw-bold small text-secondary">Órgão Responsável / Emissor</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-secondary"></i></span>
                        <input type="text" name="responsavel" class="form-control border-start-0" placeholder="Ex: Defesa Civil Municipal / Capitania dos Portos" value="{{ old('responsavel', 'Coordenação Municipal de Defesa Civil') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label fw-bold small text-secondary">Contatos de Emergência / Apoio</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone-fill text-danger"></i></span>
                        <input type="text" name="contato_emergencia" class="form-control border-start-0" placeholder="Ex: Defesa Civil: 199 | SAMU: 192 | Bombeiros: 193" value="{{ old('contato_emergencia', 'Defesa Civil 199 | SAMU 192 | Bombeiros 193') }}">
                    </div>
                    <span class="text-muted small" style="font-size: 0.75rem;">Esses números ficarão com discagem rápida e destaque no app do turista.</span>
                </div>

                <div>
                    <label class="form-label fw-bold small text-secondary">Mensagem Completa & Recomendações <span class="text-danger">*</span></label>
                    <textarea name="corpo" class="form-control rounded-3" rows="4" placeholder="Descreva os detalhes da ocorrência, medidas preventivas, locais a evitar e orientações de segurança aos turistas e moradores..." required>{{ old('corpo') }}</textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-2.5 rounded-3 fw-bold shadow-sm mt-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-send-fill"></i> Publicar Comunicado Oficial
                </button>
            </form>
        </div>
    </div>

    <!-- Histórico de Alertas Publicados -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100 d-flex flex-column">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Histórico de Alertas & Comunicados</h5>
                    <span class="text-muted small">Gerencie ou exclua notificações emitidas</span>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 font-monospace">
                    {{ $alertas->total() }} registro(s)
                </span>
            </div>
            <div class="card-body p-0 flex-grow-1">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Alerta / Mensagem</th>
                                <th>Nível</th>
                                <th>Responsável & Emergência</th>
                                <th>Validade</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertas as $alerta)
                                @php
                                    $isVigente = $alerta->estaVigente();
                                @endphp
                                <tr>
                                    <td class="ps-4" style="max-width: 260px;">
                                        <div class="fw-bold text-dark mb-1">{{ $alerta->titulo }}</div>
                                        <div class="text-muted small text-truncate" style="font-size: 0.78rem;" title="{{ $alerta->corpo }}">
                                            {{ Str::limit($alerta->corpo, 90) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($alerta->urgencia === 'urgente')
                                            <span class="badge bg-danger rounded-pill px-2.5 py-1">Urgente</span>
                                        @elseif($alerta->urgencia === 'aviso')
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Aviso</span>
                                        @else
                                            <span class="badge bg-info text-white rounded-pill px-2.5 py-1">Informativo</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 200px;">
                                        <div class="small fw-semibold text-dark text-truncate" title="{{ $alerta->responsavel }}">
                                            <i class="bi bi-shield-fill-check text-primary me-1"></i>{{ $alerta->responsavel ?? 'Defesa Civil' }}
                                        </div>
                                        <div class="small text-danger text-truncate" style="font-size: 0.75rem;" title="{{ $alerta->contato_emergencia }}">
                                            <i class="bi bi-telephone-fill me-1"></i>{{ $alerta->contato_emergencia ?? '199' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($isVigente)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                <i class="bi bi-check-circle me-1"></i>Vigente
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                <i class="bi bi-clock-history me-1"></i>Expirado
                                            </span>
                                        @endif
                                        <div class="small text-muted font-monospace mt-1" style="font-size: 0.7rem;">
                                            Até {{ $alerta->valido_ate ? $alerta->valido_ate->format('d/m/Y H:i') : ($alerta->created_at ? $alerta->created_at->addHours(24)->format('d/m/Y H:i') : 'Indeterminado') }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            <button type="button" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" data-bs-toggle="modal" data-bs-target="#modalVerAlertaAdmin{{ $alerta->id }}" title="Ver Detalhes">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <form action="{{ route('admin.alertas.destroy', $alerta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este alerta/comunicado? Ele será retirado imediatamente do app dos turistas.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border rounded-circle p-2 text-danger" title="Excluir Alerta">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal de Visualização Rápida no Admin -->
                                        <div class="modal fade text-start" id="modalVerAlertaAdmin{{ $alerta->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                                                    <div class="modal-header border-0 pb-0 pt-4 px-4 {{ $alerta->urgencia === 'urgente' ? 'bg-danger text-white' : ($alerta->urgencia === 'aviso' ? 'bg-warning text-dark' : 'bg-primary text-white') }}">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bi bi-broadcast fs-5"></i>
                                                            <h5 class="modal-title fw-bold fs-6">Detalhes do Comunicado</h5>
                                                        </div>
                                                        <button type="button" class="btn-close {{ $alerta->urgencia !== 'aviso' ? 'btn-close-white' : '' }}" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="d-flex gap-2 mb-3 flex-wrap">
                                                            <span class="badge bg-danger-subtle text-danger border rounded-pill px-2.5 py-1 fw-bold">
                                                                Nível: {{ strtoupper($alerta->urgencia) }}
                                                            </span>
                                                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">
                                                                Duração: {{ $alerta->duracao_horas ?? 24 }} horas
                                                            </span>
                                                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">
                                                                Emitido: {{ $alerta->created_at ? $alerta->created_at->format('d/m/Y H:i') : '-' }}
                                                            </span>
                                                        </div>

                                                        <h5 class="fw-bold text-dark mb-2">{{ $alerta->titulo }}</h5>
                                                        <p class="text-secondary small mb-4" style="line-height: 1.6; white-space: pre-line;">{{ $alerta->corpo }}</p>

                                                        <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                                            <div class="small fw-bold text-dark mb-1">
                                                                <i class="bi bi-shield-check text-primary me-1"></i> Órgão Responsável:
                                                            </div>
                                                            <div class="text-secondary small mb-2">{{ $alerta->responsavel ?? 'Defesa Civil & Gestão Municipal' }}</div>

                                                            <div class="small fw-bold text-danger mb-1">
                                                                <i class="bi bi-telephone-fill me-1"></i> Telefones & Contatos de Emergência:
                                                            </div>
                                                            <div class="fw-bold text-dark small">{{ $alerta->contato_emergencia ?? 'Defesa Civil 199 / SAMU 192' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-between">
                                                        <form action="{{ route('admin.alertas.destroy', $alerta->id) }}" method="POST" onsubmit="return confirm('Confirmar exclusão deste alerta?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                                <i class="bi bi-trash me-1"></i> Excluir Alerta
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success opacity-50"></i>
                                        Nenhum comunicado ou alerta emitido até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($alertas->hasPages())
                <div class="card-footer bg-white border-0 px-4 py-3">
                    {{ $alertas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
