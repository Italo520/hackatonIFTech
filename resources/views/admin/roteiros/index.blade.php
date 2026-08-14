@extends('layouts.admin')

@section('title', 'Gestão de Roteiros Turísticos')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Roteiros Oficiais</h4>
        <p class="text-muted small mb-0">Itinerários curados pela secretaria de turismo e sugeridos para os turistas no PWA.</p>
    </div>
    <div>
        <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-map"></i> Criar Roteiro
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Título do Roteiro</th>
                    <th>Tema</th>
                    <th>Duração / Dificuldade</th>
                    <th>Orçamento Est.</th>
                    <th>Paradas Conectadas</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roteiros as $roteiro)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                    <i class="bi bi-compass fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $roteiro->titulo }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">Perfil: {{ $roteiro->perfil ?? 'Geral' }} • {{ $roteiro->transporte ?? 'Carro / Caminhada' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $roteiro->tema }}</span></td>
                        <td>
                            <div class="small fw-semibold text-dark"><i class="bi bi-clock me-1 text-primary"></i>{{ $roteiro->duracao }} horas</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">Dificuldade: {{ $roteiro->dificuldade ?? 'Fácil' }}</div>
                        </td>
                        <td><span class="small fw-bold text-dark">R$ {{ number_format($roteiro->orcamento ?? 0, 2, ',', '.') }}</span></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">
                                <i class="bi bi-pin-map-fill me-1"></i>{{ $roteiro->itens?->count() ?? 0 }} paradas
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="/roteiro/{{ $roteiro->id }}" class="btn btn-sm btn-light border rounded-pill px-3" target="_blank" title="Ver no PWA">
                                <i class="bi bi-eye me-1"></i> Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-map fs-1 d-block mb-2 text-muted opacity-50"></i>
                            Nenhum roteiro cadastrado no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
