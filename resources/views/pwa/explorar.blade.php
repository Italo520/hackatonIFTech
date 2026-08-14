@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .place-hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .place-hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }
    
    /* Correção do Sidebar de Filtros */
    @media (min-width: 992px) {
        .filter-sidebar {
            top: 48px !important; /* Gruda no exato limite da margem, não empurra o card pra baixo */
            z-index: 2 !important; /* Garante que fique por baixo da nav inferior (z-3) */
            max-height: calc(100vh - 140px);
            overflow-y: auto;
        }
        .filter-container {
            padding-top: 0 !important; 
        }
    }
    @media (max-width: 991px) {
        .filter-container {
            padding-top: 0 !important;
            margin-top: -1.5rem; /* Puxa todo o container de filtros mais para cima no mobile */
        }
    }
    
    /* Estilo para a barra do slider de orçamento */
    .custom-range {
        --range-progress: 50%;
    }
    .custom-range::-webkit-slider-runnable-track {
        background: linear-gradient(to right, var(--bs-primary) var(--range-progress), #dee2e6 var(--range-progress)) !important;
    }
    .custom-range::-moz-range-track {
        background: linear-gradient(to right, var(--bs-primary) var(--range-progress), #dee2e6 var(--range-progress)) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 py-2 py-lg-3 filter-container">
    <div class="row g-4">
        <!-- SIDEBAR / FILTROS (ESQUERDA EM DESKTOP, TOPO EM MOBILE) -->
        <aside class="col-12 col-lg-4 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 p-md-4 bg-white sticky-lg-top filter-sidebar">
                <form action="{{ route('pwa.explorar') }}" method="GET" id="explorar-search-form">
                <!-- Search Box -->
                <div class="rounded-4 p-3 mb-4 text-white text-center shadow-sm" style="background: linear-gradient(135deg, #005f73, #0a9396);">
                    <h2 class="fs-6 fw-bold mb-2">Busca Inteligente</h2>
                        <div class="mb-2">
                            <textarea 
                                name="q" 
                                class="form-control rounded-3 border-0 small shadow-none" 
                                rows="2" 
                                placeholder="Encontre praias, atrativos ou passeios familiares..." 
                                aria-label="Termo de busca">{{ request('q') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill fw-bold btn-sm py-2 shadow-sm" aria-label="Executar pesquisa">
                            <i class="bi bi-search me-1" aria-hidden="true"></i> Pesquisar
                        </button>
                </div>

                <!-- Filtro de Categorias -->
                <div class="mb-4">
                    <h3 class="fs-6 fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill text-primary" aria-hidden="true"></i> Categorias
                    </h3>
                    <div class="d-flex flex-column gap-2 small text-secondary" id="filter-categories">
                        @foreach($categorias ?? [] as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cat[]" value="{{ $cat->slug }}" id="cat-{{ $cat->id }}" {{ in_array($cat->slug, (array)request('cat', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat-{{ $cat->id }}">
                                    {{ $cat->nome }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Filtro de Orçamento -->
                <div class="mb-4">
                    <h3 class="fs-6 fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-wallet2 text-success" aria-hidden="true"></i> Orçamento Estimado
                    </h3>
                    <input type="range" class="form-range custom-range" name="orcamento" min="0" max="1000" step="50" id="filtro-orcamento" value="{{ request('orcamento', 300) }}">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>R$ 0</span>
                        <span id="valor-orcamento-display" class="fw-bold text-primary">Até R$ {{ request('orcamento', 300) }}</span>
                    </div>
                </div>

                <!-- Acessibilidade -->
                <div class="mb-3">
                    <h3 class="fs-6 fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-universal-access-circle text-info" aria-hidden="true"></i> Acessibilidade
                    </h3>
                    <div class="d-flex flex-column gap-1 small text-secondary">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="acess[]" value="cadeirante" id="acess-cadeirante" {{ in_array('cadeirante', (array)request('acess', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="acess-cadeirante">♿ Acessível para Cadeirantes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="acess[]" value="libras" id="acess-libras" {{ in_array('libras', (array)request('acess', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="acess-libras">🤟 Atendimento em Libras</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm d-lg-none mt-2">
                    Aplicar Filtros
                </button>
                </form>
            </div>
        </aside>

        <!-- CONTEÚDO PRINCIPAL (DIREITA) -->
        <main class="col-12 col-lg-8 col-xl-9 d-flex flex-column gap-4">
            <!-- SEÇÃO 1: PRINCIPAIS LUGARES OU RESULTADOS -->
            <section aria-labelledby="section-lugares-title">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 id="section-lugares-title" class="fs-5 fw-bold text-dark mb-0">
                        {{ isset($is_search) && $is_search ? 'Resultados da Busca' : 'Principais Lugares' }}
                    </h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 small">
                        {{ count($principais_lugares ?? []) }} atrações
                    </span>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-3 g-3">
                    @forelse ($principais_lugares ?? [] as $lugar)
                        <div class="col">
                            <article class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 place-hover-card bg-white" aria-labelledby="lugar-title-{{ $lugar->id }}">
                                <div class="position-relative" style="height: 160px; background-color: #f3f4f5;">
                                    <img src="https://images.unsplash.com/photo-1548625149-fc4a29cf7092?auto=format&fit=crop&w=500&q=80&sig={{ $lugar->id }}" 
                                         alt="Foto de {{ $lugar->nome }}" 
                                         class="w-100 h-100 object-fit-cover" 
                                         loading="lazy">
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-primary rounded-pill px-2.5 py-1 text-white shadow-sm" style="font-size: 0.72rem;">
                                            {{ $lugar->categoria?->nome ?? 'Atrativo' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 id="lugar-title-{{ $lugar->id }}" class="card-title fs-6 fw-bold mb-1 text-dark">{{ $lugar->nome }}</h3>
                                        <p class="card-text small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.82rem; line-height: 1.4;">
                                            {{ $lugar->descricao }}
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="text-warning small" aria-label="Avaliação 4.8 de 5 estrelas">
                                            <i class="bi bi-star-fill" aria-hidden="true"></i>
                                            <i class="bi bi-star-fill" aria-hidden="true"></i>
                                            <i class="bi bi-star-fill" aria-hidden="true"></i>
                                            <i class="bi bi-star-fill" aria-hidden="true"></i>
                                            <i class="bi bi-star-half" aria-hidden="true"></i>
                                        </div>
                                        <a href="{{ route('pwa.atrativo', ['id' => $lugar->id]) }}" class="btn btn-primary rounded-pill btn-sm px-3 fw-bold" aria-label="Explorar {{ $lugar->nome }}">
                                            Explorar
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <x-pwa.empty-state 
                                titulo="Nenhum atrativo cadastrado" 
                                descricao="Novos locais serão inseridos em breve pelos parceiros e pela administração."
                            />
                        </div>
                    @endforelse
                </div>
            </section>

            @if(!isset($is_search) || !$is_search)
            <!-- SEÇÃO 2: BANNER IA -->
            <section aria-label="Criação de Roteiro com Inteligência Artificial">
                <div class="card border-0 rounded-4 shadow-sm p-4 text-white text-center position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, rgba(0, 95, 115, 0.95), rgba(10, 147, 150, 0.95)), url('https://images.unsplash.com/photo-1506197603052-3cc9c3a201bd?auto=format&fit=crop&w=1200&q=80') center/cover;">
                    <div class="position-relative z-1 py-2">
                        <i class="bi bi-stars display-4 mb-2 d-inline-block text-warning" aria-hidden="true"></i>
                        <h2 class="fs-4 fw-bold mb-2 text-uppercase" style="letter-spacing: 0.5px;">Planeje Seu Próximo Roteiro!</h2>
                        <p class="small opacity-90 mb-3 mx-auto" style="max-width: 500px;">
                            Deixe nossa inteligência artificial montar um itinerário sob medida e otimizado por proximidade.
                        </p>
                        <a href="{{ route('pwa.ia') }}" class="btn btn-light text-primary rounded-pill px-4 py-2 fw-bold shadow-sm" style="min-height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bi bi-robot me-1" aria-hidden="true"></i> Criar Roteiro Personalizado
                        </a>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO 3: ATIVIDADES GRATUITAS -->
            <section aria-labelledby="section-gratuitas-title">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 id="section-gratuitas-title" class="fs-5 fw-bold text-dark mb-0">Atividades Gratuitas</h2>
                </div>
                <div class="d-flex gap-3 overflow-auto no-scrollbar pb-2" style="margin-left: -0.5rem; margin-right: -0.5rem; padding-left: 0.5rem; padding-right: 0.5rem;">
                    @forelse ($atividades_gratuitas ?? [] as $atividade)
                        <article class="card border-0 rounded-4 overflow-hidden shadow-sm flex-shrink-0 bg-white" style="width: 220px;" aria-labelledby="gratuito-title-{{ $atividade->id }}">
                            <div style="height: 120px; background-color: #f3f4f5;">
                                <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=300&q=80&sig={{ $atividade->id }}" 
                                     alt="Foto de {{ $atividade->nome }}" 
                                     class="w-100 h-100 object-fit-cover" 
                                     loading="lazy">
                            </div>
                            <div class="card-body p-3 text-center">
                                <h3 id="gratuito-title-{{ $atividade->id }}" class="fs-6 fw-bold text-dark mb-2 text-truncate" title="{{ $atividade->nome }}">{{ $atividade->nome }}</h3>
                                <a href="{{ route('pwa.atrativo', ['id' => $atividade->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" aria-label="Ver detalhes de {{ $atividade->nome }}">
                                    Ver Detalhes
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="w-100 text-center py-3 text-muted small">
                            Nenhuma atividade gratuita registrada no momento.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- SEÇÃO 4: EVENTOS PRÓXIMOS -->
            @if(isset($eventos) && $eventos->count() > 0)
                <section aria-labelledby="section-eventos-title">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 id="section-eventos-title" class="fs-5 fw-bold text-dark mb-0">Eventos Próximos</h2>
                        <a href="{{ route('pwa.eventos') }}" class="small fw-semibold text-primary text-decoration-none">Ver todos</a>
                    </div>
                    <div class="d-flex gap-3 overflow-auto no-scrollbar pb-2" style="margin-left: -0.5rem; margin-right: -0.5rem; padding-left: 0.5rem; padding-right: 0.5rem;">
                        @foreach ($eventos as $evento)
                            <article class="card border-0 rounded-4 overflow-hidden shadow-sm flex-shrink-0 bg-white" style="width: 240px; border-top: 4px solid var(--bs-primary) !important;" aria-labelledby="evento-title-{{ $evento->id }}">
                                <div class="p-2 bg-light text-center border-bottom">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill small fw-bold">
                                        <i class="bi bi-calendar-check me-1" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($evento->inicio)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <h3 id="evento-title-{{ $evento->id }}" class="fs-6 fw-bold text-dark mb-1 text-truncate" title="{{ $evento->nome }}">{{ $evento->nome }}</h3>
                                    <p class="small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.78rem;">
                                        {{ $evento->descricao }}
                                    </p>
                                    <a href="{{ route('pwa.eventos') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" aria-label="Ver mais sobre o evento {{ $evento->nome }}">
                                        Ver Programação
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orcamentoInput = document.getElementById('filtro-orcamento');
    const orcamentoDisplay = document.getElementById('valor-orcamento-display');

    function updateRangeBackground() {
        if (!orcamentoInput) return;
        const min = parseFloat(orcamentoInput.min) || 0;
        const max = parseFloat(orcamentoInput.max) || 1000;
        const val = parseFloat(orcamentoInput.value);
        const percentage = ((val - min) / (max - min)) * 100;
        orcamentoInput.style.setProperty('--range-progress', `${percentage}%`);
    }

    if (orcamentoInput && orcamentoDisplay) {
        // Define o background inicial baseado no valor preenchido
        updateRangeBackground();

        orcamentoInput.addEventListener('input', function() {
            orcamentoDisplay.textContent = `Até R$ ${this.value}`;
            updateRangeBackground();
        });
    }
});
</script>
@endpush
