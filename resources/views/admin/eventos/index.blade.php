@extends('layouts.admin')

@section('title', 'Gestão de Eventos')

@section('content')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div>{{ session('status') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
@endif

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Calendário de Eventos</h4>
        <p class="text-muted small mb-0">Gerencie a programação oficial de festivais, feiras gastronômicas e eventos culturais.</p>
    </div>
    <div>
        <button type="button" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNovoEvento">
            <i class="bi bi-calendar-plus"></i> Novo Evento
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Evento</th>
                    <th>Período</th>
                    <th>Local / Organizador</th>
                    <th>Gratuito?</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eventos as $evento)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                    <i class="bi bi-calendar-event fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $evento->nome }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ Str::limit($evento->descricao, 60) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                <i class="bi bi-clock me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($evento->inicio)->format('d/m/Y H:i') }}
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                até {{ \Carbon\Carbon::parse($evento->fim ?? $evento->inicio)->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark">{{ $evento->local ?? 'Local a definir' }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $evento->organizador ?? 'Prefeitura / Secretaria' }}</div>
                        </td>
                        <td>
                            @if($evento->gratuito)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Gratuito</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1">Pago / Ingresso</span>
                            @endif
                        </td>
                        <td>
                            @if($evento->status === 'ativo')
                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Publicado</span>
                            @elseif($evento->status === 'cancelado')
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1">Cancelado</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">{{ ucfirst($evento->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1 align-items-center">
                                <a href="/eventos?from=admin" class="btn btn-sm btn-light border rounded-circle p-2" title="Visualizar na Agenda PWA">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" data-bs-toggle="modal" data-bs-target="#modalEditarEvento{{ $evento->id }}" title="Editar Evento">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.eventos.destroy', $evento->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border rounded-circle p-2 text-danger" title="Excluir Evento">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                            Nenhum evento registrado no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modais de Edição de Eventos -->
    @foreach($eventos as $evento)
        <div class="modal fade text-start" id="modalEditarEvento{{ $evento->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <form action="{{ route('admin.eventos.update', $evento->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Editar Evento Oficial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-bold small text-secondary">Nome do Evento</label>
                                    <input type="text" name="nome" value="{{ $evento->nome }}" class="form-control" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="ativo" {{ $evento->status == 'ativo' ? 'selected' : '' }}>Publicado / Ativo</option>
                                        <option value="cancelado" {{ $evento->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="encerrado" {{ $evento->status == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Início</label>
                                    <input type="datetime-local" name="inicio" value="{{ $evento->inicio ? $evento->inicio->format('Y-m-d\TH:i') : '' }}" class="form-control" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Término</label>
                                    <input type="datetime-local" name="fim" value="{{ $evento->fim ? $evento->fim->format('Y-m-d\TH:i') : '' }}" class="form-control">
                                </div>
                                <x-admin.location-autocomplete />
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Local do Evento</label>
                                    <input type="text" name="local" value="{{ $evento->local }}" class="form-control" placeholder="Ex: Busto de Tamandaré">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Organizador / Realização</label>
                                    <input type="text" name="organizador" value="{{ $evento->organizador }}" class="form-control" placeholder="Ex: Secretaria de Turismo">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-secondary">Descrição e Programação</label>
                                    <textarea name="descricao" class="form-control" rows="3" required>{{ $evento->descricao }}</textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="gratuito" value="1" id="editGratuito{{ $evento->id }}" {{ $evento->gratuito ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="editGratuito{{ $evento->id }}">Evento com entrada gratuita para o público</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 pb-4 px-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if($eventos->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Total de {{ $eventos->total() }} eventos</span>
            <div>
                {{ $eventos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Novo Evento -->
<div class="modal fade" id="modalNovoEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('admin.eventos.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Cadastrar Novo Evento Oficial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold small text-secondary">Nome do Evento</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Festival de Frutos do Mar" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-secondary">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="ativo" selected>Publicado / Ativo</option>
                                <option value="cancelado">Cancelado</option>
                                <option value="encerrado">Encerrado</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Data e Hora de Início</label>
                            <input type="datetime-local" name="inicio" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Data e Hora de Término</label>
                            <input type="datetime-local" name="fim" class="form-control">
                        </div>
                        <x-admin.location-autocomplete />
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Local do Evento</label>
                            <input type="text" name="local" class="form-control" placeholder="Ex: Busto de Tamandaré, Praia de Cabo Branco">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Organizador / Realização</label>
                            <input type="text" name="organizador" class="form-control" placeholder="Ex: Secretaria de Turismo e Cultura">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary">Descrição e Programação</label>
                            <textarea name="descricao" class="form-control" rows="3" placeholder="Atrações, horários dos shows e informações aos turistas..." required></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="gratuito" value="1" id="novoGratuito" checked>
                                <label class="form-check-label small fw-semibold" for="novoGratuito">Evento com entrada gratuita para o público</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">Cadastrar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
