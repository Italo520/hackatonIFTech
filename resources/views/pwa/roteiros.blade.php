@extends('layouts.pwa')

@section('content')
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 1020; top: -10px !important;">
    @if(request('from') === 'admin' || (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 'gestor_cadastros', 'atendente'])))
        <div class="mb-2">
            <a href="{{ route('admin.roteiros.index') }}" class="btn btn-dark rounded-pill btn-sm px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 text-white border border-warning shadow-sm" style="background: #003844; font-size: 0.8rem;">
                <i class="bi bi-arrow-left text-warning"></i>
                <span>Voltar ao Painel de Roteiros</span>
            </a>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="fw-bold fs-5 mb-0">Roteiros Prontos</h1>
            <p class="small text-secondary mb-0">Itinerários otimizados para <span class="current-city-name text-primary fw-semibold">Sua Localização</span></p>
        </div>
        <a href="{{ route('pwa.ia') }}" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-3 shadow-sm">
            <i class="bi bi-stars me-1"></i> Criar com IA
        </a>
    </div>

    <!-- Chips de Filtro de Duração -->
    <div class="d-flex gap-2 overflow-auto no-scrollbar pb-1 pt-1" id="chips-roteiros-container">
        <a href="{{ route('pwa.roteiros') }}" class="btn {{ !request('duracao') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            Todos os Roteiros
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'curto']) }}" class="btn {{ request('duracao') === 'curto' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-clock me-1"></i> Até 4 Horas
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'dia']) }}" class="btn {{ request('duracao') === 'dia' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-sun me-1"></i> 1 Dia Completo
        </a>
        <a href="{{ route('pwa.roteiros', ['duracao' => 'fimdesemana']) }}" class="btn {{ request('duracao') === 'fimdesemana' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill btn-sm px-3 fw-medium flex-shrink-0">
            <i class="bi bi-calendar-range me-1"></i> Fim de Semana
        </a>
    </div>
</div>

<div class="container-fluid px-3 py-3 mb-5">
    <div class="d-flex flex-column gap-3" id="container-lista-roteiros">
        @if(isset($roteiros) && $roteiros->count() > 0)
            @foreach($roteiros as $r)
                @php
                    $primeiroAtrativo = $r->itens->first()?->atrativo;
                    $foto = $primeiroAtrativo?->midias?->first()?->url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';
                @endphp
                <div class="position-relative mb-2">
                    <a href="{{ route('pwa.roteiro', $r->id) }}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark card-hover-shadow transition-all bg-white d-block">
                        <div class="position-relative" style="height: 170px;">
                            <img src="{{ $foto }}" class="w-100 h-100 object-fit-cover" alt="{{ $r->titulo }}" loading="lazy" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';">
                            @if($r->origem === 'ia')
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-stars"></i> Roteiro IA</span>
                            @else
                                <span class="badge bg-success text-white position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-shield-check"></i> Roteiro Oficial</span>
                            @endif
                            
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
                                <span class="badge bg-primary rounded-pill px-2 py-0.5 text-white small">{{ $r->tema ?? 'Turismo Geral' }}</span>
                                <h2 class="text-white fw-bold fs-5 mb-0 mt-1">{{ $r->titulo }}</h2>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <p class="small text-secondary mb-3" style="line-height: 1.35;">{{ $r->descricao ?? 'Roteiro elaborado para otimizar sua visitação turística com segurança e conforto.' }}</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="small text-secondary d-flex align-items-center gap-2">
                                    <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>{{ $r->itens->count() }} paradas</strong></span>
                                    <span><i class="bi bi-clock-fill text-warning me-1"></i> {{ $r->duracao ? ($r->duracao > 60 ? round($r->duracao/60, 1) . ' horas' : $r->duracao . ' min') : '1 Dia' }}</span>
                                    @if($r->orcamento)
                                        <span><i class="bi bi-cash-coin text-success me-1"></i> R$ {{ number_format((float)$r->orcamento, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                                <span class="fw-bold text-primary small">Ver Rota <i class="bi bi-chevron-right"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif

        <div id="dynamic-ia-roteiros-container"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dynamicContainer = document.getElementById('dynamic-ia-roteiros-container');
    
    function renderSavedIARoteiros() {
        if (!dynamicContainer) return;
        let meusRoteiros = [];
        try {
            meusRoteiros = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
        } catch(e) {}

        if (meusRoteiros.length === 0) return;

        dynamicContainer.innerHTML = meusRoteiros.map(r => `
            <div class="position-relative mb-3">
                <a href="/roteiro/${r.id}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark card-hover-shadow transition-all bg-white d-block">
                    <div class="position-relative" style="height: 170px;">
                        <img src="${r.imagem || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'}" class="w-100 h-100 object-fit-cover" alt="${r.titulo}">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-stars"></i> Roteiro IA (Salvo)</span>
                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
                            <span class="badge bg-primary rounded-pill px-2 py-0.5 text-white small">${r.cidade || 'Seu Destino'}</span>
                            <h2 class="text-white fw-bold fs-5 mb-0 mt-1">${r.titulo}</h2>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <p class="small text-secondary mb-3">${r.descricao || 'Roteiro gerado pelo assistente de Inteligência Artificial.'}</p>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <div class="small text-secondary d-flex align-items-center gap-2">
                                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>${r.paradas ? r.paradas.length : 3} paradas</strong></span>
                                <span><i class="bi bi-clock-fill text-warning me-1"></i> ${r.duracao || '2 horas'}</span>
                            </div>
                            <span class="fw-bold text-primary small">Ver Rota <i class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                </a>
                <button class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm btn-delete-ia" data-id="${r.id}" style="top: 8px; right: 8px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;" title="Excluir Roteiro">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        `).join('');

        dynamicContainer.querySelectorAll('.btn-delete-ia').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('Deseja excluir este roteiro da IA salvo no seu navegador?')) {
                    const idToRemove = parseInt(this.getAttribute('data-id'));
                    try {
                        let saved = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
                        saved = saved.filter(r => parseInt(r.id) !== idToRemove);
                        localStorage.setItem('meus_roteiros_ia', JSON.stringify(saved));
                        renderSavedIARoteiros();
                    } catch(err) {}
                }
            });
        });
    }

    renderSavedIARoteiros();
});
</script>
@endpush
@endsection
