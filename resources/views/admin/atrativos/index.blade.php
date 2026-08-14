@extends('layouts.admin')

@section('title', 'Gestão de Atrativos Turísticos')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Atrativos Cadastrados</h4>
        <p class="text-muted small mb-0">Gerencie todos os pontos turísticos, praias, monumentos e restaurantes do município.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNovoAtrativo">
            <i class="bi bi-plus-lg"></i> Novo Atrativo
        </button>
    </div>
</div>

<!-- Barra de Filtros e Busca -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" action="{{ route('admin.atrativos.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Buscar por nome ou descrição...">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="cidade" class="form-select bg-light shadow-none">
                <option value="">Todas as Cidades</option>
                <option value="João Pessoa" {{ request('cidade') == 'João Pessoa' ? 'selected' : '' }}>João Pessoa (PB)</option>
                <option value="Bonito" {{ request('cidade') == 'Bonito' ? 'selected' : '' }}>Bonito (MS)</option>
                <option value="Recife" {{ request('cidade') == 'Recife' ? 'selected' : '' }}>Recife (PE)</option>
                <option value="Natal" {{ request('cidade') == 'Natal' ? 'selected' : '' }}>Natal (RN)</option>
                <option value="São Paulo" {{ request('cidade') == 'São Paulo' ? 'selected' : '' }}>São Paulo (SP)</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select bg-light shadow-none">
                <option value="">Todos Status</option>
                <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 w-100 fw-semibold">Filtrar</button>
            <a href="{{ route('admin.atrativos.index') }}" class="btn btn-outline-secondary rounded-3" title="Limpar Filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<!-- Tabela de Atrativos -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Nome & Endereço</th>
                    <th>Cidade / UF</th>
                    <th>Categoria</th>
                    <th>Coordenadas GPS</th>
                    <th>Tempo Médio</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($atrativos as $atrativo)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 42px; height: 42px;">
                                    <i class="bi bi-geo-alt-fill fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $atrativo->nome }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ Str::limit($atrativo->endereco, 45) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $atrativo->municipio?->nome ?? 'Regional' }} - {{ $atrativo->municipio?->uf ?? 'BR' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                {{ $atrativo->categoria?->nome ?? 'Turismo Geral' }}
                            </span>
                        </td>
                        <td>
                            <span class="small text-muted font-monospace" style="font-size: 0.75rem;">
                                {{ number_format($atrativo->lat, 4) }}, {{ number_format($atrativo->lng, 4) }}
                            </span>
                        </td>
                        <td>
                            <span class="small text-muted"><i class="bi bi-clock me-1"></i>{{ $atrativo->tempo_medio_visita ? $atrativo->tempo_medio_visita . ' min' : 'Livre' }}</span>
                        </td>
                        <td>
                            @if($atrativo->status === 'ativo')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Ativo</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1">{{ ucfirst($atrativo->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="/atrativo/{{ $atrativo->id }}" class="btn btn-sm btn-light border rounded-circle p-2" target="_blank" title="Ver no PWA">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" title="Editar Atrativo">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-geo-alt fs-1 d-block mb-2 text-muted opacity-50"></i>
                            Nenhum atrativo encontrado com os filtros selecionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($atrativos->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Exibindo {{ $atrativos->firstItem() }} até {{ $atrativos->lastItem() }} de {{ $atrativos->total() }} registros</span>
            <div>
                {{ $atrativos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Novo Atrativo (Mock) -->
<div class="modal fade" id="modalNovoAtrativo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Cadastrar Novo Atrativo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="form-label fw-bold small text-secondary">Nome do Ponto Turístico</label>
                        <input type="text" class="form-control" placeholder="Ex: Piscinas Naturais do Seixas" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small text-secondary">Cidade / Município</label>
                        <select class="form-select">
                            <option>João Pessoa (PB)</option>
                            <option>Bonito (MS)</option>
                            <option>Recife (PE)</option>
                            <option>Natal (RN)</option>
                            <option>São Paulo (SP)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small text-secondary">Descrição Completa</label>
                        <textarea class="form-control" rows="3" placeholder="Detalhes, história e orientações para o visitante..."></textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-secondary">Latitude (GPS)</label>
                        <input type="number" step="0.0001" class="form-control" placeholder="-7.1153">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-secondary">Longitude (GPS)</label>
                        <input type="number" step="0.0001" class="form-control" placeholder="-34.8641">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Salvar Atrativo</button>
            </div>
        </div>
    </div>
</div>
@endsection
