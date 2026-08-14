@extends('layouts.pwa')

@section('content')
<div class="container px-3 py-4" style="max-width: 650px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Painel do Parceiro</h4>
            <span class="text-muted small">Status do seu credenciamento municipal</span>
        </div>
        <a href="{{ route('pwa.home') }}" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">
            <i class="bi bi-phone me-1"></i> App Turista
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    @if($prestador)
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-light text-dark border text-uppercase mb-1">{{ $prestador->tipo }}</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $prestador->dados['nome_negocio'] ?? 'Meu Negócio' }}</h5>
                    <div class="text-muted small">Cadastrado em {{ $prestador->created_at ? $prestador->created_at->format('d/m/Y') : date('d/m/Y') }}</div>
                </div>
                <div>
                    @if($prestador->status === 'aprovado')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold">
                            <i class="bi bi-patch-check-fill me-1"></i> Selo Validado
                        </span>
                    @elseif($prestador->status === 'pendente')
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold">
                            <i class="bi bi-hourglass-split me-1"></i> Em Análise
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold">
                            <i class="bi bi-x-circle me-1"></i> {{ ucfirst($prestador->status) }}
                        </span>
                    @endif
                </div>
            </div>

            @if($prestador->status === 'aprovado')
                <div class="p-3 rounded-3 bg-success-subtle text-success border border-success-subtle small mt-3">
                    <i class="bi bi-check-circle-fill me-1"></i> Parabéns! Seu estabelecimento possui o <strong>Selo Oficial de Qualidade Turística</strong> e já aparece com destaque para os visitantes no mapa e na inteligência artificial.
                </div>
            @else
                <div class="p-3 rounded-3 bg-light text-muted small mt-3">
                    <i class="bi bi-info-circle me-1"></i> Seus documentos foram enviados para a equipe de gestão da Secretaria de Turismo. Você será notificado assim que a análise for concluída.
                </div>
            @endif
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
            <div class="rounded-circle d-inline-flex p-3 bg-light text-primary mx-auto mb-3" style="width: 64px; height: 64px; align-items: center; justify-content: center;">
                <i class="bi bi-shop fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Nenhum estabelecimento cadastrado</h5>
            <p class="text-muted small mb-4">Credencie sua pousada, restaurante ou serviço de guia para fazer parte da rede oficial.</p>
            <div>
                <a href="{{ route('empreendedor.create') }}" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Cadastrar Meu Negócio
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
