@extends('layouts.admin')

@section('title', 'Fila de Validação de Empreendedores')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Validação de Estabelecimentos & Parceiros</h4>
        <p class="text-muted small mb-0">Analise os cadastros de pousadas, restaurantes e guias para conceder o Selo Oficial do Município.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Estabelecimento / Razão Social</th>
                    <th>Tipo</th>
                    <th>Documentação</th>
                    <th>Status Atual</th>
                    <th class="text-end pe-4">Julgamento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestadores as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                    <i class="bi bi-shop fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $p->dados['nome_negocio'] ?? 'Negócio Sem Nome' }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">Responsável: {{ $p->user?->name ?? 'Empreendedor Cadastrado' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border text-uppercase">{{ $p->tipo }}</span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="alert('Documentos válidos anexados pelo proponente.')">
                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i> Ver Anexos
                            </button>
                        </td>
                        <td>
                            @if($p->status === 'pendente')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1">Pendente Análise</span>
                            @elseif($p->status === 'aprovado')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Aprovado</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1">{{ ucfirst($p->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <form action="{{ route('admin.prestadores.update', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="aprovado">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                        <i class="bi bi-check2 me-1"></i> Aprovar
                                    </button>
                                </form>
                                <form action="{{ route('admin.prestadores.update', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="rejeitado">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-x"></i> Rejeitar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 d-block mb-2 text-success opacity-50"></i>
                            Nenhum estabelecimento pendente de validação no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($prestadores->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Total de {{ $prestadores->total() }} registros</span>
            <div>
                {{ $prestadores->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
