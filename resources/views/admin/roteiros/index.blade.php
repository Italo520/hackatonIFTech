@extends('layouts.admin')

@section('title', 'Gestão de Roteiros Turísticos')

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
        <h4 class="fw-bold text-dark mb-1">Roteiros Oficiais</h4>
        <p class="text-muted small mb-0">Itinerários curados pela secretaria de turismo e sugeridos para os turistas no PWA.</p>
    </div>
    <div>
        <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalNovoRoteiro">
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
                            <div class="text-muted small" style="font-size: 0.72rem;">Dificuldade: {{ ucfirst($roteiro->dificuldade ?? 'Fácil') }}</div>
                        </td>
                        <td><span class="small fw-bold text-dark">R$ {{ number_format($roteiro->orcamento ?? 0, 2, ',', '.') }}</span></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">
                                <i class="bi bi-pin-map-fill me-1"></i>{{ $roteiro->itens?->count() ?? 0 }} paradas
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1 align-items-center">
                                <a href="/roteiro/{{ $roteiro->id }}" class="btn btn-sm btn-light border rounded-pill px-3" target="_blank" title="Ver no PWA">
                                    <i class="bi bi-eye me-1"></i> Ver
                                </a>
                                <form action="{{ route('admin.roteiros.destroy', $roteiro->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este roteiro oficial?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border rounded-circle p-2 text-danger" title="Excluir Roteiro">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
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

<!-- Modal Novo Roteiro -->
<div class="modal fade" id="modalNovoRoteiro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('admin.roteiros.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Criar Roteiro Oficial / Sugerido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-bold small text-secondary">Título do Roteiro</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: Rota Histórica e Cultural no Centro" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-secondary">Tema</label>
                            <select name="tema" class="form-select" required>
                                <option value="Histórico & Cultural">Histórico & Cultural</option>
                                <option value="Praias & Natureza">Praias & Natureza</option>
                                <option value="Gastronomia & Vida Noturna">Gastronomia & Vida Noturna</option>
                                <option value="Aventura & Ecoturismo">Aventura & Ecoturismo</option>
                                <option value="Família & Lazer">Família & Lazer</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-secondary">Duração Estimada (horas)</label>
                            <input type="number" step="0.5" name="duracao" value="4" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-secondary">Dificuldade</label>
                            <select name="dificuldade" class="form-select" required>
                                <option value="facil" selected>Fácil</option>
                                <option value="medio">Médio</option>
                                <option value="dificil">Difícil</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-secondary">Orçamento Médio (R$)</label>
                            <input type="number" step="0.01" name="orcamento" value="50.00" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Perfil do Visitante</label>
                            <input type="text" name="perfil" value="Família e Casais" class="form-control" placeholder="Ex: Casal, Mochileiro, Família">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-secondary">Modo de Transporte</label>
                            <input type="text" name="transporte" value="Caminhada / Carro" class="form-control" placeholder="Ex: Caminhada, Carro, Bicicleta">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary">Selecione os Pontos Turísticos Conectados (na ordem do roteiro):</label>
                            <div class="border rounded-3 p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @forelse($atrativosDisponiveis as $at)
                                        <div class="col-12 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="atrativos[]" value="{{ $at->id }}" id="atrativoItem{{ $at->id }}">
                                                <label class="form-check-label small" for="atrativoItem{{ $at->id }}">
                                                    <span class="fw-semibold">{{ $at->nome }}</span>
                                                    <span class="text-muted d-block" style="font-size: 0.72rem;">{{ $at->municipio?->nome }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted small">Nenhum atrativo ativo cadastrado.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Salvar Roteiro</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
