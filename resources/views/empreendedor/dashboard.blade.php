@extends('layouts.pwa')

@section('content')
<div class="container px-3 py-4" style="max-width: 680px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Painel do Parceiro</h4>
            <span class="text-muted small">Gerenciamento do seu negócio e credenciamento oficial</span>
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
        <!-- Card do Estabelecimento -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-light text-dark border text-uppercase mb-1">{{ $prestador->tipo }}</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $prestador->dados['nome_negocio'] ?? 'Meu Negócio' }}</h5>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i> {{ $prestador->dados['endereco'] ?? 'Endereço cadastrado' }}
                    </div>
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
                <div class="p-3 rounded-3 bg-success-subtle text-success border border-success-subtle small mt-2">
                    <i class="bi bi-check-circle-fill me-1"></i> Parabéns! Seu estabelecimento possui o <strong>Selo Oficial de Qualidade Turística</strong>. Suas atrações e serviços já estão ativos no aplicativo e indexados nas recomendações do Guia IA.
                </div>
            @else
                <div class="p-3 rounded-3 bg-warning-subtle text-dark border border-warning-subtle small mt-2">
                    <i class="bi bi-info-circle me-1"></i> <strong>Cadastro em análise:</strong> Seus dados foram submetidos para a Secretaria de Turismo. Você já pode cadastrar as atrações e passeios do seu negócio abaixo; eles ficarão em <strong>rascunho</strong> e serão publicados automaticamente assim que sua conta for aprovada.
                </div>
            @endif
        </div>

        <!-- Seção de Atrativos & Serviços do Empreendimento -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Atrativos, Passeios & Serviços</h6>
                    <span class="text-muted small">Itens ofertados aos visitantes</span>
                </div>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#novoAtrativoModal">
                    <i class="bi bi-plus-lg me-1"></i> Novo Item
                </button>
            </div>

            <div class="p-3 rounded-3 bg-light text-center border">
                <i class="bi bi-collection text-secondary fs-3 d-block mb-1"></i>
                <div class="fw-semibold text-dark small">Cadastre as experiências e serviços do seu estabelecimento</div>
                <div class="text-muted" style="font-size: 0.75rem;">Ex: Degustação de frutos do mar, Passeio de lancha, Diária standard com café da manhã.</div>
            </div>
        </div>

        <!-- Modal de Novo Atrativo/Serviço -->
        <div class="modal fade" id="novoAtrativoModal" tabindex="-1" aria-labelledby="novoAtrativoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; background: rgba(0, 95, 115, 0.1);">
                                <i class="bi bi-plus-circle-fill fs-5"></i>
                            </div>
                            <h5 class="modal-title fw-bold" id="novoAtrativoModalLabel">Propor Novo Atrativo / Serviço</h5>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <form action="{{ route('empreendedor.atrativo.store') }}" method="POST">
                        @csrf
                        <div class="modal-body d-flex flex-column gap-3 py-3">
                            <div>
                                <label class="form-label fw-bold small text-secondary mb-1">Título do Atrativo / Serviço</label>
                                <input type="text" name="nome" class="form-control bg-light shadow-none" placeholder="Ex: Passeio de Catamarã nas Piscinas" required>
                            </div>

                            <div>
                                <label class="form-label fw-bold small text-secondary mb-1">Categoria</label>
                                <select name="categoria_id" class="form-select bg-light shadow-none" required>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label fw-bold small text-secondary mb-1">Descrição Completa</label>
                                <textarea name="descricao" rows="3" class="form-control bg-light shadow-none" placeholder="Descreva o que está incluído, diferenciais e informações aos turistas" required></textarea>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Duração Média (min)</label>
                                    <input type="number" name="tempo_medio_visita" class="form-control bg-light shadow-none" value="60" min="15" step="15">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-secondary mb-1">Local / Ponto de Saída</label>
                                    <input type="text" name="endereco" class="form-control bg-light shadow-none" placeholder="Ex: Praia de Tambaú">
                                </div>
                            </div>

                            <div class="alert alert-info py-2 px-3 small rounded-3 mb-0" style="font-size: 0.78rem;">
                                <i class="bi bi-info-circle-fill me-1"></i> Este item será criado em modo <strong>Rascunho</strong> e passará pela validação da gestão antes de ser publicado no mapa e no assistente IA.
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Salvar Rascunho</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @else
        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
            <div class="rounded-circle d-inline-flex p-3 bg-light text-primary mx-auto mb-3" style="width: 64px; height: 64px; align-items: center; justify-content: center;">
                <i class="bi bi-shop fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Nenhum estabelecimento cadastrado</h5>
            <p class="text-muted small mb-4">Credencie sua pousada, restaurante ou serviço de guia para fazer parte da rede oficial e receber o Selo Municipal.</p>
            <div>
                <a href="{{ route('empreendedor.create') }}" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Cadastrar Meu Negócio
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
