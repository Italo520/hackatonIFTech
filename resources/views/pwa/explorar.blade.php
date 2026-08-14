@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* =========================================================================
       2. ESTILIZAÇÃO CSS (PALETA INSPIRADA NO APP)
       ========================================================================= */
    :root {
        --teal-primary: #008282; 
        --blue-accent: #0066CC;  
        --surface: #FFFFFF;      
        --text-main: #1F2937;    
        --text-muted: #6B7280;   
        --border-light: #E5E7EB; 
    }

    /* CONTAINER PRINCIPAL (LAYOUT DO FÓRUM) */
    .explorar-container {
        display: flex;
        max-width: 1440px;
        margin: 0 auto;
        padding: 10px 10px;
        gap: 30px;
    }

    /* SIDEBAR / FILTROS (ESQUERDA) */
    .explorar-aside {
        width: 320px;
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        padding: 20px;
        height: fit-content;
    }
    
    .search-box {
        background-color: var(--teal-primary);
        padding: 20px;
        border-radius: 12px;
        color: white;
        margin-bottom: 25px;
        text-align: center;
    }
    .search-box h3 { font-size: 1.1rem; margin-bottom: 10px; font-weight: 600; }
    .search-box textarea {
        width: 100%; padding: 12px; border-radius: 8px; border: none;
        resize: none; height: 80px; font-family: inherit; font-size: 0.9rem;
    }

    .filter-section { margin-bottom: 25px; }
    .filter-section h4 { 
        font-size: 1rem; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; 
        color: var(--text-main); font-weight: 600;
    }
    .filter-section h4 i { color: var(--blue-accent); } 
    
    .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.9rem; color: var(--text-muted); }
    .checkbox-grid label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
    
    .slider-container { padding: 0 5px; }
    input[type=range] { width: 100%; accent-color: var(--teal-primary); }

    .btn-pesquisar {
        width: 100%;
        background-color: var(--teal-primary);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 10px;
    }
    .btn-pesquisar:hover { opacity: 0.9; transform: translateY(-2px); }

    /* CONTEÚDO PRINCIPAL (DIREITA) */
    .explorar-main { flex: 1; display: flex; flex-direction: column; gap: 40px; }
    
    .section-header h2 { font-size: 1.8rem; font-weight: 700; color: var(--text-main); text-transform: uppercase; letter-spacing: 0.5px; }

    /* GRID PRINCIPAIS LUGARES */
    .places-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        margin-top: 15px;
    }
    .place-card {
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        transition: 0.3s;
    }
    .place-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .place-card img { width: 100%; height: 160px; object-fit: cover; }
    .place-card-body { padding: 16px; }
    .place-card-body h3 { font-size: 1.1rem; margin-bottom: 8px; font-weight: 700; }
    .place-card-body p { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5; }
    .place-card-footer { display: flex; justify-content: space-between; align-items: center; }
    .stars { color: #F59E0B; font-size: 0.85rem; }
    
    .btn-explorar {
        background-color: var(--teal-primary);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
    }

    /* BANNER EXPERIÊNCIAS */
    .banner-experiencias {
        background: linear-gradient(rgba(0, 130, 130, 0.85), rgba(0, 130, 130, 0.85)), url('https://images.unsplash.com/photo-1506197603052-3cc9c3a201bd?auto=format&fit=crop&w=1200&q=80') center/cover;
        border-radius: 16px;
        padding: 40px 30px;
        text-align: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .banner-experiencias h2 { font-size: 2.2rem; margin-bottom: 10px; font-weight: 700; }
    .banner-experiencias p { font-size: 1.1rem; margin-bottom: 20px; opacity: 0.9; }
    .btn-banner {
        background-color: white;
        color: var(--teal-primary);
        padding: 12px 28px;
        border-radius: 30px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    /* ATIVIDADES GRATUITAS */
    .free-scroll {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 15px;
        margin-top: 15px;
    }
    .free-card {
        min-width: 220px;
        background: var(--surface);
        border-radius: 12px;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }
    .free-card img { width: 100%; height: 120px; object-fit: cover; }
    .free-card .content { padding: 12px; text-align: center; }
    .free-card h4 { font-size: 0.95rem; margin-bottom: 12px; color: var(--text-main); }
    .free-card button { 
        width: 100%; background: transparent; color: var(--teal-primary); 
        border: 1px solid var(--teal-primary); padding: 8px; border-radius: 8px; 
        font-weight: 600; cursor: pointer; transition: 0.2s;
    }
    .free-card button:hover { background: var(--teal-primary); color: white; }
    
    @media (max-width: 900px) {
        .explorar-container { flex-direction: column; }
        .explorar-aside { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="explorar-container">
    <!-- SIDEBAR -->
    <div class="explorar-aside">
        <div class="search-box">
            <h3>Mecanismo de Busca Inteligente</h3>
            <textarea placeholder="Encontre experiências familiares gratuitas ao ar livre..."></textarea>
        </div>

        <div class="filter-section">
            <h4><i class="fa-solid fa-layer-group"></i> Categorias</h4>
            <div class="checkbox-grid" id="filter-categories">
                <label><input type="checkbox" value="historico"> Histórico</label>
                <label><input type="checkbox" value="ecologico"> Ecológico</label>
                <label><input type="checkbox" value="cultural"> Cultural</label>
                <label><input type="checkbox" value="aventura"> Aventura</label>
                <label><input type="checkbox" value="negocios"> Negócios</label>
                <label><input type="checkbox" value="saude"> Saúde</label>
                <label><input type="checkbox" value="pet_friendly"> Pet Friendly</label>
            </div>
        </div>

        <div class="filter-section">
            <h4><i class="fa-regular fa-calendar"></i> Datas</h4>
            <div style="background: #F1F5F9; height: 50px; border-radius: 8px; display:flex; align-items:center; justify-content:center; color: var(--text-main); font-size: 0.95rem; padding: 0 15px;">
                <input type="text" id="filtro-datas" placeholder="Selecione as datas" style="width: 100%; border: none; background: transparent; text-align: center; font-family: inherit; font-weight: 600; outline: none; cursor: pointer;">
            </div>
        </div>

        <div class="filter-section">
            <h4><i class="fa-solid fa-wallet"></i> Orçamento</h4>
            <div class="slider-container">
                <input type="range" min="0" max="1000" value="300">
                <div style="display: flex; justify-content: space-between; margin-top: 5px; font-size: 0.85rem; color: var(--text-muted);">
                    <span>R$ 0</span>
                    <span>Até R$ 300</span>
                </div>
            </div>
        </div>
        
        <div class="filter-section">
            <h4><i class="fa-solid fa-wheelchair"></i> Acessibilidade</h4>
            <div class="checkbox-grid">
                <label><input type="checkbox"> Acessível (Cadeirante)</label>
                <label><input type="checkbox"> Libras</label>
            </div>
        </div>

        <button class="btn-pesquisar">PESQUISAR</button>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="explorar-main">
        <!-- SEÇÃO 1 -->
        <section>
            <div class="section-header">
                <h2>Principais Lugares</h2>
            </div>
            <div class="places-grid">
                @foreach ($principais_lugares as $lugar)
                    <div class="place-card">
                        <img src="https://images.unsplash.com/photo-1548625149-fc4a29cf7092?auto=format&fit=crop&w=400&q=80&sig={{ $lugar->id }}" alt="{{ $lugar->nome }}">
                        <div class="place-card-body">
                            <h3>{{ $lugar->nome }}</h3>
                            <p>{{ Str::limit($lugar->descricao, 80) }}</p>
                            <div class="place-card-footer">
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <a href="/atrativo/{{ $lugar->id }}" class="btn-explorar" style="text-decoration:none;">Explorar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- SEÇÃO 2: BANNER -->
        <section>
            <div class="banner-experiencias">
                <i class="fa-solid fa-wand-magic-sparkles" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                <h2>PLANEJE SEU PRÓXIMO LOCAL!</h2>
                <p>Deixe nossa inteligência artificial montar o dia perfeito para você.</p>
                <button class="btn-banner">CRIAR ROTEIRO PERSONALIZADO</button>
            </div>
        </section>

        <!-- SEÇÃO 3 -->
        <section>
            <div class="section-header">
                <h2>Atividades Gratuitas</h2>
            </div>
            <div class="free-scroll">
                @foreach ($atividades_gratuitas as $atividade)
                    <div class="free-card">
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=300&q=80&sig={{ $atividade->id }}" alt="{{ $atividade->nome }}">
                        <div class="content">
                            <h4>{{ $atividade->nome }}</h4>
                            <a href="/atrativo/{{ $atividade->id }}" style="text-decoration:none;"><button style="width:100%;">Ver Detalhes</button></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- SEÇÃO 4: EVENTOS -->
        @if(isset($eventos) && $eventos->count() > 0)
        <section>
            <div class="section-header">
                <h2>Eventos Próximos</h2>
            </div>
            <div class="free-scroll">
                @foreach ($eventos as $evento)
                    <div class="free-card" style="border-top: 4px solid var(--blue-accent);">
                        <div style="padding: 15px; background: rgba(0, 102, 204, 0.05); text-align: center; border-bottom: 1px solid var(--border-light);">
                            <span style="font-size: 0.85rem; color: var(--blue-accent); font-weight: 700; text-transform: uppercase;">
                                <i class="fa-regular fa-calendar-check"></i> {{ \Carbon\Carbon::parse($evento->inicio)->format('d M, Y') }}
                            </span>
                        </div>
                        <div class="content">
                            <h4 style="margin-bottom: 5px;">{{ $evento->nome }}</h4>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $evento->descricao }}
                            </p>
                            <a href="#" style="text-decoration:none;"><button style="width:100%; border-color: var(--blue-accent); color: var(--blue-accent);">Inscrever-se</button></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
<script>
    // 1. Inicializa o Calendário de verdade
    flatpickr("#filtro-datas", {
        mode: "range",
        locale: "pt",
        dateFormat: "d/m/Y",
        placeholder: "Selecione as datas..."
    });

    // =========================================================================
    // INSTRUÇÕES PARA EXTRAIR DADOS DO FILTRO (DEV)
    // =========================================================================
    document.querySelector('.btn-pesquisar').addEventListener('click', function() {
        // A. Buscar Categorias Selecionadas
        const categorias = Array.from(document.querySelectorAll('#filter-categories input:checked'))
                                .map(el => el.value);

        // B. Buscar Valor do Orçamento
        const orcamento = document.querySelector('input[type=range]').value;

        // C. Buscar Texto da Pesquisa Inteligente
        const queryBusca = document.querySelector('.search-box textarea').value;

        // D. Buscar Datas (ex: "10/08/2026 ao 15/08/2026")
        const datas = document.querySelector('#filtro-datas').value;

        // E. Buscar Acessibilidade
        const acessibilidade = Array.from(document.querySelectorAll('.filter-section:nth-of-type(4) .checkbox-grid input:checked'))
                                    .map(el => el.parentElement.textContent.trim());

        // F. Exemplo de objeto JSON para enviar para a API (Axios/Fetch)
        const payload = {
            busca: queryBusca,
            categorias: categorias,
            orcamento_max: orcamento,
            datas: datas,
            acessibilidade: acessibilidade
        };

        // Você pode enviar o "payload" para a rota API do Laravel aqui
        console.log("DADOS PRONTOS PARA API:", payload);
        alert("Abra o Console (F12) para ver os dados dos filtros organizados em JSON!");
    });
</script>
@endpush
