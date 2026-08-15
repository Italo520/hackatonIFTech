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
        <button class="btn btn-primary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 chip-duracao active" data-duracao="todos">
            Todos os Roteiros
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white chip-duracao" data-duracao="curto">
            <i class="bi bi-clock me-1"></i> Até 4 Horas
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white chip-duracao" data-duracao="dia">
            <i class="bi bi-sun me-1"></i> 1 Dia Completo
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white chip-duracao" data-duracao="fimdesemana">
            <i class="bi bi-calendar-range me-1"></i> Fim de Semana
        </button>
    </div>
</div>

<div class="container-fluid px-3 py-3 mb-5">
    <div class="d-flex flex-column gap-3" id="container-lista-roteiros">
        <!-- Renderizado dinamicamente via JS -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let filtroDuracao = 'todos';
    const containerRoteiros = document.getElementById('container-lista-roteiros');
    const chipsContainer = document.getElementById('chips-roteiros-container');

    function renderRoteiros() {
        if (!window.LocationService || !window.LocationService.getRoteirosByCity) {
            setTimeout(renderRoteiros, 100);
            return;
        }

        const savedLoc = window.LocationService.getSavedLocation() || { city: 'João Pessoa' };
        let roteirosPadrao = window.LocationService.getRoteirosByCity(savedLoc.city) || [];
        
        let meusRoteiros = [];
        try {
            meusRoteiros = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
        } catch(e) {}
        
        let roteirosIA = meusRoteiros.filter(r => r.cidade === savedLoc.city);
        
        let roteiros = [...roteirosIA, ...roteirosPadrao];
        const city = { name: savedLoc.city };

        // Filtro de duração
        if (filtroDuracao === 'curto') {
            roteiros = roteiros.filter(r => (r.duracao || '').toLowerCase().includes('4') || (r.duracao || '').toLowerCase().includes('meio') || (r.duracao || '').toLowerCase().includes('2'));
        } else if (filtroDuracao === 'dia') {
            roteiros = roteiros.filter(r => (r.duracao || '').toLowerCase().includes('1 dia') || (r.duracao || '').toLowerCase().includes('6 a 8'));
        } else if (filtroDuracao === 'fimdesemana') {
            roteiros = roteiros.filter(r => (r.duracao || '').toLowerCase().includes('2 dias') || (r.duracao || '').toLowerCase().includes('fim de semana'));
        }

        if (roteiros.length === 0) {
            containerRoteiros.innerHTML = `
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 bg-light text-primary mx-auto mb-2" style="width: 56px; height: 56px;">
                        <i class="bi bi-compass fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Nenhum roteiro específico para este filtro</h6>
                    <p class="text-muted small mb-3">Você pode gerar um roteiro personalizado com inteligência artificial.</p>
                    <div>
                        <a href="/ia" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold">
                            <i class="bi bi-stars me-1"></i> Gerar com IA
                        </a>
                    </div>
                </div>
            `;
            return;
        }

        containerRoteiros.innerHTML = roteiros.map(r => `
            <div class="position-relative mb-3">
                <a href="/roteiro/${r.id}" class="card border-0 rounded-4 overflow-hidden shadow-sm text-decoration-none text-dark card-hover-shadow transition-all bg-white d-block">
                    <div class="position-relative" style="height: 170px;">
                        <img src="${r.imagem || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'}" class="w-100 h-100 object-fit-cover" alt="${r.titulo}" loading="lazy">
                        ${r.is_ia ? `<span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm rounded-pill fw-bold" style="font-size: 0.75rem;"><i class="bi bi-stars"></i> Roteiro IA</span>` : ''}
                        
                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
                            <span class="badge bg-primary rounded-pill px-2 py-0.5 text-white small">${r.cidade || city.name}</span>
                            <h2 class="text-white fw-bold fs-5 mb-0 mt-1">${r.titulo}</h2>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <p class="small text-secondary mb-3" style="line-height: 1.35;">${r.descricao}</p>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <div class="small text-secondary d-flex align-items-center gap-2">
                                <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>${r.paradas ? r.paradas.length : 3} paradas</strong></span>
                                <span><i class="bi bi-clock-fill text-warning me-1"></i> ${r.duracao}</span>
                            </div>
                            <span class="fw-bold text-primary small">Ver <i class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                </a>
                ${r.is_ia ? `
                    <button class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm btn-delete-ia" data-id="${r.id}" style="top: 8px; right: 8px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;" title="Excluir Roteiro">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                ` : ''}
            </div>
        `).join('');

        // Eventos para deletar
        containerRoteiros.querySelectorAll('.btn-delete-ia').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if(confirm('Deseja realmente apagar este roteiro da IA da sua lista?')) {
                    const idToRemove = parseInt(this.getAttribute('data-id'));
                    try {
                        let saved = JSON.parse(localStorage.getItem('meus_roteiros_ia') || '[]');
                        saved = saved.filter(r => parseInt(r.id) !== idToRemove);
                        localStorage.setItem('meus_roteiros_ia', JSON.stringify(saved));
                        renderRoteiros();
                    } catch(err) {}
                }
            });
        });
    }

    chipsContainer?.querySelectorAll('.chip-duracao').forEach(btn => {
        btn.addEventListener('click', function() {
            chipsContainer.querySelectorAll('.chip-duracao').forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-secondary', 'bg-white');
            });
            this.classList.remove('btn-outline-secondary', 'bg-white');
            this.classList.add('btn-primary', 'active');
            filtroDuracao = this.getAttribute('data-duracao');
            renderRoteiros();
        });
    });

    window.addEventListener('turismo:location-changed', renderRoteiros);
    renderRoteiros();
});
</script>
@endpush
@endsection
