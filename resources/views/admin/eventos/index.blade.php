@extends('layouts.admin')

@section('title', 'Gestão de Eventos')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Calendário de Eventos</h4>
        <p class="text-muted small mb-0">Gerencie a programação oficial de festivais, feiras gastronômicas e eventos culturais.</p>
    </div>
    <div>
        <button type="button" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
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
                                até {{ \Carbon\Carbon::parse($evento->fim)->format('d/m/Y H:i') }}
                            </div>
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
                            @else
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">{{ ucfirst($evento->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" title="Editar Evento">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                            Nenhum evento registrado no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($eventos->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Total de {{ $eventos->total() }} eventos</span>
            <div>
                {{ $eventos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
