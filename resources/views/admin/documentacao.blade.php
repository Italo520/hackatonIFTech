@extends('layouts.admin')

@section('title', 'Dossiê de Documentação Técnica')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       DOSSIÊ DE ENTREGA — SPA COM ABAS ULTRA-RÁPIDA (ZERO LAG)
       ═══════════════════════════════════════════════════════════════ */
    :root {
        --doc-bg: #0f172a;
        --doc-sidebar-bg: #1e293b;
        --doc-card-bg: #1e293b;
        --doc-card-border: rgba(255, 255, 255, 0.08);
        --doc-accent: #00d4aa;
        --doc-accent-dim: rgba(0, 212, 170, 0.12);
        --doc-text-primary: #f8fafc;
        --doc-text-secondary: #94a3b8;
        --doc-text-muted: #64748b;
        --doc-border: #334155;
    }

    .doc-spa-wrapper {
        background-color: var(--doc-bg);
        color: var(--doc-text-primary);
        margin: -1.5rem -1.5rem 0;
        min-height: calc(100vh - 64px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Topbar da Documentação */
    .doc-spa-header {
        background: #111827;
        border-bottom: 1px solid var(--doc-border);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .doc-spa-header .badge-version {
        background: rgba(0, 212, 170, 0.15);
        color: var(--doc-accent);
        border: 1px solid rgba(0, 212, 170, 0.3);
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 9999px;
        font-weight: 600;
    }

    .doc-search-box {
        position: relative;
        min-width: 240px;
        max-width: 380px;
        flex-grow: 1;
    }

    .doc-search-box input {
        background: #1e293b;
        border: 1px solid var(--doc-border);
        color: #fff;
        padding: 8px 14px 8px 36px;
        border-radius: 9999px;
        font-size: 0.82rem;
        width: 100%;
        outline: none;
        transition: border-color 0.15s ease;
    }

    .doc-search-box input:focus {
        border-color: var(--doc-accent);
        box-shadow: 0 0 0 2px var(--doc-accent-dim);
    }

    .doc-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--doc-text-muted);
        font-size: 0.9rem;
    }

    /* Layout SPA: Sidebar de Abas + Conteúdo */
    .doc-spa-body {
        display: flex;
        min-height: calc(100vh - 140px);
    }

    /* Sidebar de Navegação por Abas */
    .doc-spa-sidebar {
        width: 280px;
        background: var(--doc-sidebar-bg);
        border-right: 1px solid var(--doc-border);
        flex-shrink: 0;
        padding: 16px 12px;
        overflow-y: auto;
        max-height: calc(100vh - 140px);
        position: sticky;
        top: 73px;
        transition: width 0.2s ease, padding 0.2s ease;
    }

    .doc-spa-sidebar.collapsed {
        display: none !important;
    }

    .doc-tab-btn {
        width: 100%;
        background: transparent;
        border: 1px solid transparent;
        color: var(--doc-text-secondary);
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 600;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 4px;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .doc-tab-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--doc-text-primary);
    }

    .doc-tab-btn.active {
        background: var(--doc-accent-dim);
        color: var(--doc-accent);
        border-color: rgba(0, 212, 170, 0.3);
        box-shadow: 0 2px 8px rgba(0, 212, 170, 0.1);
    }

    .doc-tab-btn .tab-num {
        font-size: 0.72rem;
        font-weight: 700;
        opacity: 0.7;
        font-family: monospace;
    }

    .doc-tab-btn .tab-icon {
        font-size: 1.1rem;
    }

    /* Barra Superior Compacta de Abas (visível quando recolhido) */
    .doc-mini-tabs-bar {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding: 8px 12px;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid var(--doc-border);
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .doc-mini-tab-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--doc-border);
        color: var(--doc-text-secondary);
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .doc-mini-tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .doc-mini-tab-btn.active {
        background: var(--doc-accent);
        color: #0f172a;
        border-color: var(--doc-accent);
        font-weight: 700;
    }

    /* Área Principal de Conteúdo */
    .doc-spa-content {
        flex-grow: 1;
        padding: 24px 32px;
        max-width: 1300px;
        margin: 0 auto;
        overflow-x: hidden;
        transition: max-width 0.2s ease;
    }

    /* Submenu de Tópicos rápidos */
    .doc-subnav-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 24px;
        padding: 12px 16px;
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid var(--doc-border);
        border-radius: 12px;
    }

    .doc-subnav-pill {
        color: var(--doc-text-secondary);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--doc-border);
        border-radius: 9999px;
        padding: 4px 12px;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .doc-subnav-pill:hover {
        background: var(--doc-accent);
        color: #0f172a;
        border-color: var(--doc-accent);
    }

    /* Cards de Seção & Tópicos */
    .doc-section-card {
        background: var(--doc-card-bg);
        border: 1px solid var(--doc-card-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .doc-section-card h3 {
        color: var(--doc-accent);
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .doc-section-card h4 {
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 700;
        margin: 18px 0 10px;
    }

    .doc-section-card p, .doc-section-card li {
        color: var(--doc-text-secondary);
        font-size: 0.88rem;
        line-height: 1.6;
    }

    .doc-section-card ul {
        padding-left: 20px;
        margin-bottom: 14px;
    }

    .doc-section-card code {
        background: rgba(0, 0, 0, 0.4);
        color: #38bdf8;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.82rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Banner Especial Swagger */
    .doc-swagger-banner {
        background: linear-gradient(135deg, rgba(0, 95, 115, 0.4) 0%, rgba(10, 147, 150, 0.2) 100%);
        border: 1px solid rgba(0, 212, 170, 0.4);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .doc-swagger-banner .btn-swagger-hero {
        background: var(--doc-accent);
        color: #0f172a;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 9999px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.86rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .doc-swagger-banner .btn-swagger-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 212, 170, 0.4);
    }

    /* Responsividade */
    @media (max-width: 991.98px) {
        .doc-spa-body {
            flex-direction: column;
        }
        .doc-spa-sidebar {
            width: 100%;
            max-height: none;
            position: relative;
            top: 0;
            display: flex;
            overflow-x: auto;
            gap: 6px;
            padding: 10px;
        }
        .doc-tab-btn {
            width: auto;
            white-space: nowrap;
            margin-bottom: 0;
        }
        .doc-spa-content {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="doc-spa-wrapper">
    <!-- Topbar SPA -->
    <header class="doc-spa-header">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--doc-accent-dim); color: var(--doc-accent);">
                <i class="bi bi-journal-code fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-white">Dossiê de Entrega & Arquitetura</h5>
                <span class="text-muted small" style="font-size: 0.72rem;">Destino Inteligente • Guia Completo de Engenharia</span>
            </div>
            <span class="badge-version ms-2">v1.0.0 Prod</span>
        </div>

        <!-- Busca Rápida + Acesso Swagger + Toggle Índice -->
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5" id="btnToggleDocSidebar" title="Recolher / Expandir Menu de Seções">
                <i class="bi bi-layout-sidebar text-info" id="iconToggleDocSidebar"></i>
                <span class="d-none d-md-inline" id="textToggleDocSidebar">Recolher Índice</span>
            </button>
            <div class="doc-search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="docSearchInput" placeholder="Filtrar tópicos (ex: Haversine, RBAC, RAG, DER)..." autocomplete="off">
            </div>
            <a href="{{ route('admin.swagger') }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-semibold d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <i class="bi bi-code-slash"></i>
                <span>Swagger UI</span>
                <i class="bi bi-box-arrow-up-right small"></i>
            </a>
        </div>
    </header>

    <div class="doc-spa-body">
        <!-- Sidebar de Abas (Navegação SPA) -->
        <nav class="doc-spa-sidebar" id="docSidebar">
            <button class="doc-tab-btn active" data-tab="tab1">
                <span class="tab-num">01</span>
                <i class="bi bi-briefcase tab-icon"></i>
                <span>Resumo & Negócio</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab2">
                <span class="tab-num">02</span>
                <i class="bi bi-person-walking tab-icon"></i>
                <span>Requisitos & Regras</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab3">
                <span class="tab-num">03</span>
                <i class="bi bi-diagram-3 tab-icon"></i>
                <span>Arquitetura & DER</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab4">
                <span class="tab-num">04</span>
                <i class="bi bi-stack tab-icon"></i>
                <span>Engenharia & Stack</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab5">
                <span class="tab-num">05</span>
                <i class="bi bi-arrow-left-right tab-icon"></i>
                <span>APIs & Swagger UI</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab6">
                <span class="tab-num">06</span>
                <i class="bi bi-hdd-network tab-icon"></i>
                <span>Infra & Deploy</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab7">
                <span class="tab-num">07</span>
                <i class="bi bi-gear-wide-connected tab-icon"></i>
                <span>Operação & Suporte</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab8">
                <span class="tab-num">08</span>
                <i class="bi bi-clipboard2-check tab-icon"></i>
                <span>Qualidade & Testes</span>
            </button>
            <button class="doc-tab-btn" data-tab="tab9">
                <span class="tab-num">09</span>
                <i class="bi bi-shield-check tab-icon"></i>
                <span>Segurança & LGPD</span>
            </button>
        </nav>

        <!-- Conteúdo Principal das Abas -->
        <main class="doc-spa-content" id="docContent">
            <!-- Barra Superior Compacta de Abas -->
            <div class="doc-mini-tabs-bar" id="docMiniTabsBar">
                <button class="doc-mini-tab-btn active" data-tab="tab1"><span class="tab-num">01</span> Resumo</button>
                <button class="doc-mini-tab-btn" data-tab="tab2"><span class="tab-num">02</span> Requisitos</button>
                <button class="doc-mini-tab-btn" data-tab="tab3"><span class="tab-num">03</span> Arquitetura</button>
                <button class="doc-mini-tab-btn" data-tab="tab4"><span class="tab-num">04</span> Engenharia</button>
                <button class="doc-mini-tab-btn" data-tab="tab5"><span class="tab-num">05</span> APIs & Swagger</button>
                <button class="doc-mini-tab-btn" data-tab="tab6"><span class="tab-num">06</span> Infra & Deploy</button>
                <button class="doc-mini-tab-btn" data-tab="tab7"><span class="tab-num">07</span> Operação</button>
                <button class="doc-mini-tab-btn" data-tab="tab8"><span class="tab-num">08</span> Testes</button>
                <button class="doc-mini-tab-btn" data-tab="tab9"><span class="tab-num">09</span> Segurança & LGPD</button>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 01 — Resumo Executivo & Negócio
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane" id="tab1">
                <div class="doc-subnav-pills">
                    <a href="#t1-resumo" class="doc-subnav-pill">1.1. Resumo Executivo</a>
                    <a href="#t1-licenciamento" class="doc-subnav-pill">1.2. Licenciamento</a>
                    <a href="#t1-contratos" class="doc-subnav-pill">1.3. Contratos SaaS</a>
                    <a href="#t1-roadmap" class="doc-subnav-pill">1.4. Roadmap</a>
                </div>

                <div class="doc-section-card" id="t1-resumo">
                    <h3><i class="bi bi-file-earmark-text"></i> 1.1. Resumo Executivo do Projeto</h3>
                    <p>O <strong>Destino Inteligente</strong> é uma plataforma completa de Inteligência Territorial e Gestão Turística desenvolvida para prefeituras e secretarias municipais. O sistema unifica um <strong>PWA voltado a turistas</strong> (com inteligência artificial contextual e modo offline) a um <strong>Painel Administrativo Executivo</strong> para monitoramento em tempo real de fluxo, atrativos e zeladoria urbana.</p>
                    <ul>
                        <li><strong>Missão:</strong> Digitalizar o turismo municipal, reduzir em até 90% os custos com folhetos impressos (ESG) e gerar dados confiáveis para planejamento e editais.</li>
                        <li><strong>Diferenciais:</strong> Assistente IA com RAG guardrails, mapas interativos com Leaflet, cálculo de proximidade Haversine, telemetria anonimizada em compliance com a LGPD e sistema de auditoria integral.</li>
                    </ul>
                </div>

                <div class="doc-section-card" id="t1-licenciamento">
                    <h3><i class="bi bi-award"></i> 1.2. Licenciamento e Propriedade Intelectual</h3>
                    <p>O software é disponibilizado sob licença proprietária de uso governamental. Todos os direitos de código-fonte, banco de dados e arquitetura pertencem à organização desenvolvedora, sendo concedida à prefeitura contratante a licença de operação e uso contínuo.</p>
                </div>

                <div class="doc-section-card" id="t1-contratos">
                    <h3><i class="bi bi-cloud-check"></i> 1.3. Contratos SaaS e Dependências Externas</h3>
                    <ul>
                        <li><strong>Google Gemini 3.5 Flash API:</strong> Motor de inteligência artificial para o chatbot conversacional e gerador de roteiros inteligentes.</li>
                        <li><strong>OpenStreetMap / Nominatim:</strong> Base cartográfica aberta para geocodificação reversa e renderização de tiles.</li>
                        <li><strong>PostgreSQL 15+ & Redis:</strong> Armazenamento relacional e cache de alto desempenho.</li>
                    </ul>
                </div>

                <div class="doc-section-card" id="t1-roadmap">
                    <h3><i class="bi bi-signpost-split"></i> 1.4. Roadmap e Próximas Entregas</h3>
                    <ul>
                        <li><strong>Sprint 1 (Concluída):</strong> Core Laravel 11, Models, Migrations e Autenticação.</li>
                        <li><strong>Sprint 2 (Concluída):</strong> PWA Turista, Catálogo de Atrativos e Mapas com Leaflet.</li>
                        <li><strong>Sprint 3 (Concluída):</strong> Integração com IA (Gemini RAG), Validação de Parceiros e Alertas de Defesa Civil.</li>
                        <li><strong>Sprint 4 (Concluída):</strong> Painel de KPIs, Heatmap de fluxo, Exportação CSV e Conformidade LGPD.</li>
                        <li><strong>Sprint 5 (Próxima):</strong> Aplicativo nativo empacotado (Capacitor/Cordova) e integração com totens físicos via Web Bluetooth.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 02 — Requisitos & Regras de Negócio
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab2">
                <div class="doc-subnav-pills">
                    <a href="#t2-us" class="doc-subnav-pill">2.1. User Stories (12 US)</a>
                    <a href="#t2-manual-user" class="doc-subnav-pill">2.2. Manual Usuário</a>
                    <a href="#t2-manual-admin" class="doc-subnav-pill">2.3. Manual Admin</a>
                    <a href="#t2-regras" class="doc-subnav-pill">2.4. Regras de Negócio (8 RN)</a>
                </div>

                <div class="doc-section-card" id="t2-us">
                    <h3><i class="bi bi-person-lines-fill"></i> 2.1. Casos de Uso e Histórias de Usuário</h3>
                    
                    <h4>🧳 Persona: Turista</h4>
                    <ul>
                        <li><strong>US-001 — Explorar Atrativos:</strong> <em>Como turista, quero explorar atrativos turísticos filtrando por categoria e localização, para encontrar pontos de interesse próximos a mim.</em> (Implementado em <code>ExplorarController</code> e <code>GET /api/v1/atrativos</code>).</li>
                        <li><strong>US-002 — Assistente IA com RAG:</strong> <em>Como turista, quero conversar com um assistente de IA para receber recomendações oficiais de pontos turísticos e serviços cadastrados no município.</em> (Implementado em <code>IAService::chat</code>).</li>
                        <li><strong>US-003 — Roteiro Inteligente:</strong> <em>Como turista, quero gerar um roteiro personalizado com base no meu orçamento, tempo disponível e ponto de partida.</em> (Implementado em <code>IAService::gerarRoteiro</code>).</li>
                        <li><strong>US-004 — Mapa Interativo & Trajetos:</strong> <em>Como turista, quero navegar no mapa interativo para traçar rotas e localizar pontos turísticos.</em> (Implementado com Leaflet e OSRM).</li>
                        <li><strong>US-005 — Leitura de QR Code:</strong> <em>Como turista, quero escanear QR Codes nas placas dos atrativos para acessar guias digitais direto no celular.</em> (Implementado em <code>QrCodeController@resolve</code>).</li>
                        <li><strong>US-006 — Privacidade LGPD:</strong> <em>Como turista, quero gerenciar meus consentimentos de GPS e ter a opção de exportar ou excluir meus dados.</em> (Implementado em <code>LGPDController</code>).</li>
                    </ul>

                    <h4>🏪 Persona: Empreendedor Local</h4>
                    <ul>
                        <li><strong>US-007 — Cadastro de Parceiro:</strong> <em>Como empreendedor, quero cadastrar meu negócio para receber turistas e solicitar o selo oficial de turismo da prefeitura.</em> (Implementado em <code>EmpreendedorController@store</code>).</li>
                        <li><strong>US-008 — Gestão de Negócios:</strong> <em>Como parceiro aprovado, quero gerenciar as informações e serviços do meu estabelecimento.</em> (Implementado em <code>EmpreendedorController@dashboard</code>).</li>
                    </ul>

                    <h4>🏛️ Persona: Gestor Público (Prefeito / Secretário / Conteúdo)</h4>
                    <ul>
                        <li><strong>US-009 — Dashboard Executivo:</strong> <em>Como gestor, quero visualizar indicadores consolidados de fluxo turístico, engajamento e sustentabilidade.</em> (Implementado em <code>AdminController@dashboard</code>).</li>
                        <li><strong>US-010 — Alertas de Defesa Civil:</strong> <em>Como secretário ou prefeito, quero emitir alertas urgentes com prazo de vigência configurável para notificar turistas.</em> (Implementado em <code>AlertaController</code>).</li>
                        <li><strong>US-011 — Validação de Parceiros:</strong> <em>Como gestor de cadastros, quero avaliar solicitações de empreendedores e emitir o selo validado.</em> (Implementado em <code>PrestadorValidationController</code>).</li>
                        <li><strong>US-012 — Relatórios CSV:</strong> <em>Como gestor, quero exportar relatórios executivos em CSV formatados para prestação de contas.</em> (Implementado em <code>RelatorioController@exportCsv</code>).</li>
                    </ul>
                </div>

                <div class="doc-section-card" id="t2-regras">
                    <h3><i class="bi bi-calculator"></i> 2.4. Dicionário de Regras de Negócio</h3>
                    <ul>
                        <li><strong>📐 RN-001 (Fórmula Haversine):</strong> Cálculo de distância esférica entre coordenadas em <code>Atrativo.php</code> (fórmula: <code>d = 6371 × 2 × atan2(√a, √(1-a))</code>).</li>
                        <li><strong>🤖 RN-002 (IA com RAG Anti-Alucinação):</strong> Injeção automática de até 10 atrativos, 10 eventos e 10 prestadores oficiais no prompt do Gemini, com censura automática de dados sensíveis e restrição estrita a temas turísticos.</li>
                        <li><strong>🔄 RN-003 (Status de Prestadores):</strong> Fluxo de aprovação (<code>pendente</code> → <code>aprovado</code> | <code>rejeitado</code> | <code>suspenso</code> | <code>complementar</code>) com selo emitido apenas no status aprovado.</li>
                        <li><strong>🗺️ RN-004 (Intensidade do Heatmap):</strong> Cálculo da intensidade do mapa de calor ponderando visualizações e tempo médio de permanência (<code>0.3 ≤ I ≤ 1.0</code>).</li>
                        <li><strong>⏰ RN-005 (Vigência de Alertas):</strong> Cálculo automático de expiração via <code>valido_ate = now() + duracao_horas</code> e escopo <code>Alerta::ativos()</code>.</li>
                        <li><strong>🔐 RN-006 (Controle de Acesso RBAC):</strong> 8 perfis de usuário com isolamento de rotas e bypass automático para <code>super_admin</code>.</li>
                        <li><strong>📊 RN-007 (Métricas ESG):</strong> Estimativa de folhas salvas através da proporção de <code>5 folhas economizadas por scan de QR Code</code>.</li>
                        <li><strong>🔍 RN-008 (Full-Text Search):</strong> Índice GIN e vetor tsvector em PostgreSQL para pesquisas ultra-rápidas em português.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 03 — Arquitetura & Modelagem de Dados
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab3">
                <div class="doc-subnav-pills">
                    <a href="#t3-c4" class="doc-subnav-pill">3.2. Diagramas C4</a>
                    <a href="#t3-der" class="doc-subnav-pill">3.4. Modelo de Dados (DER)</a>
                    <a href="#t3-adrs" class="doc-subnav-pill">3.3. Decisões Arquiteturais (ADRs)</a>
                </div>

                <div class="doc-section-card" id="t3-c4">
                    <h3><i class="bi bi-boxes"></i> 3.2. Diagrama C4 — Nível 2 (Containers)</h3>
                    <div style="background: rgba(0,0,0,0.3); border-radius: 12px; padding: 20px; overflow-x: auto;">
                        <pre class="mermaid">graph TB
    subgraph Usuários
        T["🧳 Turista<br/>(PWA Mobile)"]
        G["🏛️ Gestor Público<br/>(Admin Desktop)"]
        E["🏪 Empreendedor<br/>(Painel Parceiro)"]
    end

    subgraph Docker["Docker Network"]
        APP["📦 Laravel 11 App<br/>PHP 8.2-FPM Alpine<br/>Porta 80"]
        PG[("🗄️ PostgreSQL 15+<br/>unaccent + pg_trgm<br/>Porta 5432")]
        RD[("⚡ Redis Alpine<br/>Cache & Filas<br/>Porta 6379")]
    end

    subgraph Externos
        OSM["🗺️ OpenStreetMap / Nominatim"]
        GEM["🤖 Google Gemini API"]
        SMTP["📧 Servidor SMTP"]
    end

    T --> APP
    G --> APP
    E --> APP
    APP --> PG
    APP --> RD
    APP --> OSM
    APP --> GEM
    APP --> SMTP</pre>
                    </div>
                </div>

                <div class="doc-section-card" id="t3-der">
                    <h3><i class="bi bi-database"></i> 3.4. Diagrama Entidade-Relacionamento (DER)</h3>
                    <p>O banco de dados é estruturado em <strong>15 tabelas principais de domínio</strong> com suporte a relacionamentos polimórficos:</p>
                    <div style="background: rgba(0,0,0,0.3); border-radius: 12px; padding: 20px; overflow-x: auto;">
                        <pre class="mermaid">erDiagram
    USERS ||--o{ PRESTADORES : "has"
    USERS ||--o{ AVALIACOES : "writes"
    USERS ||--o{ CONSENTIMENTOS : "grants"
    USERS ||--o{ ALERTAS : "creates"

    MUNICIPIOS ||--o{ ATRATIVOS : "contains"
    CATEGORIAS ||--o{ ATRATIVOS : "classifies"
    USERS ||--o{ ATRATIVOS : "validates"

    ATRATIVOS ||--o{ MIDIAS : "has photos"
    ATRATIVOS ||--o{ AVALIACOES : "receives"
    ATRATIVOS ||--o{ ANALYTIC_EVENTS : "tracks"
    ATRATIVOS ||--o{ ROTEIRO_ITENS : "included in"

    ROTEIROS ||--o{ ROTEIRO_ITENS : "contains"
    ROTEIRO_ITENS }o--|| ATRATIVOS : "references"

    USERS {
        bigint id PK
        string name
        string email UK
        enum role
        json consentimentos
    }
    ATRATIVOS {
        bigint id PK
        bigint municipio_id FK
        bigint categoria_id FK
        string nome
        decimal lat
        decimal lng
        string status
    }
    PRESTADORES {
        bigint id PK
        bigint user_id FK
        string tipo
        string status
        boolean selo_validado
    }
    ALERTAS {
        bigint id PK
        string titulo
        string urgencia
        datetime valido_ate
    }</pre>
                    </div>
                </div>

                <div class="doc-section-card" id="t3-adrs">
                    <h3><i class="bi bi-journal-check"></i> 3.3. Registros de Decisões de Arquitetura (ADRs)</h3>
                    <ul>
                        <li><strong>ADR-001:</strong> Escolha de Monolito Modular Laravel com Blade + PWA em Vanilla JS para máxima velocidade em dispositivos móveis populares.</li>
                        <li><strong>ADR-002:</strong> Utilização do PostgreSQL com extensão unaccent e busca vetorial tsvector em substituição a motores pesados como Elasticsearch.</li>
                        <li><strong>ADR-003:</strong> Estratégia de RAG (Retrieval-Augmented Generation) com Google Gemini Flash para IA contextual sem alucinações.</li>
                        <li><strong>ADR-004:</strong> Autenticação híbrida: Sessões protegidas por CSRF para Admin e Tokens Sanctum para APIs.</li>
                        <li><strong>ADR-005:</strong> Armazenamento de telemetria sem retenção de IPs pessoais em conformidade nativa com a LGPD.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 04 — Engenharia & Código-Fonte
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab4">
                <div class="doc-subnav-pills">
                    <a href="#t4-stack" class="doc-subnav-pill">4.1. Stack Tecnológico</a>
                    <a href="#t4-estrutura" class="doc-subnav-pill">4.2. Estrutura do Código</a>
                    <a href="#t4-setup" class="doc-subnav-pill">4.3. Setup Local</a>
                </div>

                <div class="doc-section-card" id="t4-stack">
                    <h3><i class="bi bi-stack"></i> 4.1. Stack Tecnológico Completo</h3>
                    <ul>
                        <li><strong>Backend:</strong> PHP 8.2+ / Laravel 11.x</li>
                        <li><strong>Frontend Admin:</strong> Bootstrap 5.3.3 / Bootstrap Icons 1.11.3</li>
                        <li><strong>Frontend PWA Turista:</strong> HTML5 / TailwindCSS 3.x / Vanilla JS</li>
                        <li><strong>Mapas & Geodados:</strong> Leaflet 1.9.4 / Leaflet Heat / OpenStreetMap</li>
                        <li><strong>Inteligência Artificial:</strong> Google Gemini 3.5 Flash API via cURL / SDK</li>
                        <li><strong>Banco de Dados:</strong> PostgreSQL 15+ (Produção) / SQLite (Desenvolvimento)</li>
                        <li><strong>Cache & Filas:</strong> Redis 7.x Alpine</li>
                        <li><strong>Auditoria:</strong> owen-it/laravel-auditing</li>
                        <li><strong>Build & Assets:</strong> Vite 6.x / PostCSS</li>
                    </ul>
                </div>

                <div class="doc-section-card" id="t4-estrutura">
                    <h3><i class="bi bi-folder-symlink"></i> 4.2. Organização dos Diretórios</h3>
                    <ul>
                        <li><code>app/Http/Controllers/Api/</code> — 12 controllers RESTful da API v1.</li>
                        <li><code>app/Http/Controllers/Web/</code> — Controllers das rotas do Painel e PWA.</li>
                        <li><code>app/Services/IAService.php</code> — Lógica do RAG, orquestração e sanitização com o Gemini.</li>
                        <li><code>app/Models/</code> — 18 modelos Eloquent com traits de auditoria.</li>
                        <li><code>resources/views/</code> — Templates Blade divididos em <code>admin/</code>, <code>pwa/</code> e <code>layouts/</code>.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 05 — Integrações, APIs & Swagger
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab5">
                <!-- Banner Swagger -->
                <div class="doc-swagger-banner">
                    <div>
                        <h4 class="text-white fw-bold mb-1"><i class="bi bi-code-square me-2 text-info"></i> Swagger / OpenAPI 3.0 Interativo</h4>
                        <p class="text-white-50 small mb-0">Consulte schemas, realize testes em tempo real (Try it out) e visualize a especificação OpenAPI 3.0 completa da API.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ asset('docs/openapi.json') }}" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Baixar openapi.json
                        </a>
                        <a href="{{ route('admin.swagger') }}" target="_blank" class="btn-swagger-hero">
                            <span>Abrir Swagger UI</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="doc-section-card">
                    <h3><i class="bi bi-plug"></i> 5.1. Catálogo Completo de Endpoints REST (v1)</h3>
                    <p>Base URL: <code>/api/v1/</code></p>
                    
                    <h4>📍 Endpoints Públicos</h4>
                    <ul>
                        <li><code>GET /api/v1/atrativos</code> — Listagem filtrada por categoria, município e proximidade Haversine.</li>
                        <li><code>GET /api/v1/atrativos/{id}</code> — Detalhes completos com fotos e acessibilidade.</li>
                        <li><code>GET /api/v1/eventos</code> — Calendário oficial de eventos e feiras.</li>
                        <li><code>GET /api/v1/roteiros</code> — Listagem de roteiros turísticos e pontos ordenados.</li>
                        <li><code>GET /api/v1/roteiros/{id}/export</code> — Exportação de dados para navegação offline.</li>
                        <li><code>POST /api/v1/ia/chat</code> — Conversa com assistente IA contextualizado via RAG.</li>
                        <li><code>POST /api/v1/ia/roteiro</code> — Gerador de roteiro otimizado por IA.</li>
                        <li><code>GET /api/v1/location/search</code> — Geocodificação de endereços.</li>
                        <li><code>GET /api/v1/location/reverse</code> — Geocodificação reversa (coordenadas para endereço).</li>
                        <li><code>GET /api/v1/routes/directions</code> — Rota e instruções de navegação entre pontos.</li>
                        <li><code>POST /api/v1/ocorrencias</code> — Registro de ocorrências turísticas e zeladoria.</li>
                        <li><code>GET /api/v1/qr/{hash}</code> — Resolução de QR Code de placa turística.</li>
                        <li><code>POST /api/v1/sync/avaliacoes</code> — Sincronização offline em lote.</li>
                        <li><code>POST /api/v1/analytics</code> — Registro de telemetria e mapa de calor.</li>
                        <li><code>POST /api/v1/lgpd/consentimentos</code> — Salva consentimentos de privacidade (GPS/alertas).</li>
                    </ul>

                    <h4>🔒 Endpoints Protegidos (Bearer Token Sanctum)</h4>
                    <ul>
                        <li><code>GET /api/user</code> — Perfil do usuário autenticado.</li>
                        <li><code>POST /api/v1/lgpd/exportar</code> — Portabilidade de dados pessoais (LGPD).</li>
                        <li><code>POST /api/v1/lgpd/excluir</code> — Direito ao esquecimento e anonimização de dados.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 06 — Infraestrutura & Deploy
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab6">
                <div class="doc-section-card">
                    <h3><i class="bi bi-hdd-network"></i> 6.1. Topologia de Infraestrutura</h3>
                    <p>O sistema foi projetado para execução em contêineres Docker leves em qualquer VPS com Coolify, Docker Compose ou Kubernetes.</p>
                    <ul>
                        <li><strong>Dockerfile:</strong> Base Alpine PHP 8.2-FPM + Nginx integrado em estágio único otimizado.</li>
                        <li><strong>Docker Compose:</strong> Orquestração dos serviços <code>laravel.test</code>, <code>postgres</code> e <code>redis</code>.</li>
                        <li><strong>Coolify Ready:</strong> Suporte a deploy contínuo via webhooks Git com SSL automático (Let's Encrypt).</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 07 — Operação & Suporte
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab7">
                <div class="doc-section-card">
                    <h3><i class="bi bi-headset"></i> 7.1. Rotinas Operacionais & Resolução de Incidentes</h3>
                    <ul>
                        <li><strong>Logs de Aplicação:</strong> Centralizados em <code>storage/logs/laravel.log</code> e tabela <code>assistant_logs</code>.</li>
                        <li><strong>Limpeza de Cache:</strong> <code>php artisan optimize:clear</code></li>
                        <li><strong>Backup Automático do Banco:</strong> Dump diário agendado via cron do PostgreSQL com retenção de 30 dias.</li>
                    </ul>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 08 — Qualidade & Testes
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab8">
                <div class="doc-section-card">
                    <h3><i class="bi bi-check2-all"></i> 8.1. Suíte de Testes Automatizados</h3>
                    <p>O projeto conta com mais de <strong>80 testes automatizados (274 asserções)</strong> passando com 100% de sucesso:</p>
                    <ul>
                        <li><code>RbacMiddlewareTest.php</code> — Validação rigorosa dos 8 perfis e permissões.</li>
                        <li><code>AtrativoApiTest.php</code> — Testes de filtros, busca e cálculo de distância.</li>
                        <li><code>IAApiTest.php</code> — Testes do assistente IA, censura de dados pessoais e roteiro.</li>
                        <li><code>E2EDemoTest.php</code> — Testes de fluxos de ponta a ponta do turista e gestor.</li>
                        <li><code>LgpdConsentTest.php</code> — Testes de consentimento, exportação e exclusão de contas.</li>
                    </ul>
                    <p>Execução local: <code>php artisan test</code></p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ABA 09 — Segurança, RBAC & LGPD
                 ═══════════════════════════════════════════════════════ -->
            <div class="doc-tab-pane d-none" id="tab9">
                <div class="doc-section-card">
                    <h3><i class="bi bi-people"></i> 9.2. Matriz de Controle de Acesso (RBAC)</h3>
                    <ul>
                        <li><strong><code>super_admin</code>:</strong> Acesso total irrestrito (Dashboard, Atrativos, Eventos, Roteiros, Prestadores, Alertas, Auditoria, Relatórios, Documentação e Swagger).</li>
                        <li><strong><code>prefeito</code>:</strong> Visão executiva (Dashboard, Alertas de Defesa Civil e Exportação de Relatórios CSV).</li>
                        <li><strong><code>secretario</code>:</strong> Gestão turística completa (Dashboard, Atrativos, Eventos, Roteiros, Validação de Prestadores, Alertas e Relatórios).</li>
                        <li><strong><code>gestor_conteudo</code>:</strong> Gestão de conteúdo turístico (Dashboard, Atrativos, Eventos e Roteiros).</li>
                        <li><strong><code>gestor_cadastros</code>:</strong> Fila de validação de parceiros locais e emissão de selo oficial.</li>
                        <li><strong><code>atendente</code>:</strong> Consulta informativa e suporte ao turista.</li>
                        <li><strong><code>empreendedor</code>:</strong> Acesso exclusivo ao Painel do Parceiro (<code>/parceiro/painel</code>).</li>
                        <li><strong><code>turista</code>:</strong> Acesso ao aplicativo PWA público.</li>
                    </ul>
                </div>

                <div class="doc-section-card">
                    <h3><i class="bi bi-shield-check"></i> 9.3. Conformidade com a LGPD (Lei 13.709/2018)</h3>
                    <ul>
                        <li><strong>Portabilidade (Art. 18):</strong> Endpoint <code>POST /api/v1/lgpd/exportar</code> entrega JSON estruturado com todos os dados pessoais do titular.</li>
                        <li><strong>Direito ao Esquecimento (Art. 18):</strong> Endpoint <code>POST /api/v1/lgpd/excluir</code> realiza soft delete da conta e anonimiza avaliações para preservação estatística.</li>
                        <li><strong>Sanitização IA:</strong> Filtro em <code>IAService.php</code> que substitui e-mails por <code>[EMAIL]</code> antes de chamar o modelo.</li>
                        <li><strong>Headers de Segurança:</strong> Middleware <code>SecurityHeaders.php</code> injetando <code>Content-Security-Policy</code>, <code>X-Frame-Options</code> e <code>X-XSS-Protection</code>.</li>
                    </ul>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<!-- Mermaid.js para renderização sob demanda dos diagramas -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.min.js"></script>
<script>
    mermaid.initialize({
        startOnLoad: false,
        theme: 'dark',
        themeVariables: {
            primaryColor: '#00d4aa',
            primaryTextColor: '#f8fafc',
            primaryBorderColor: '#00d4aa',
            lineColor: '#94a3b8',
            secondaryColor: '#0a9396',
            tertiaryColor: 'rgba(0, 212, 170, 0.1)',
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const docSidebar = document.getElementById('docSidebar');
        const btnToggleDocSidebar = document.getElementById('btnToggleDocSidebar');
        const textToggleDocSidebar = document.getElementById('textToggleDocSidebar');
        const iconToggleDocSidebar = document.getElementById('iconToggleDocSidebar');
        const tabBtns = document.querySelectorAll('.doc-tab-btn');
        const miniTabBtns = document.querySelectorAll('.doc-mini-tab-btn');
        const tabPanes = document.querySelectorAll('.doc-tab-pane');
        const searchInput = document.getElementById('docSearchInput');
        let renderedMermaid = false;

        // Carregar estado salvo do índice interno
        const isDocSidebarCollapsed = localStorage.getItem('doc_sidebar_collapsed') === 'true';
        if (isDocSidebarCollapsed && docSidebar) {
            docSidebar.classList.add('collapsed');
            if (textToggleDocSidebar) textToggleDocSidebar.textContent = 'Expandir Índice';
        }

        // Toggle do índice interno
        btnToggleDocSidebar?.addEventListener('click', function() {
            if (!docSidebar) return;
            docSidebar.classList.toggle('collapsed');
            const collapsed = docSidebar.classList.contains('collapsed');
            localStorage.setItem('doc_sidebar_collapsed', collapsed);
            if (textToggleDocSidebar) {
                textToggleDocSidebar.textContent = collapsed ? 'Expandir Índice' : 'Recolher Índice';
            }
        });

        // Função para ativar aba
        function switchTab(tabId, updateHash = true) {
            tabBtns.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            miniTabBtns.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            tabPanes.forEach(pane => {
                if (pane.id === tabId) {
                    pane.classList.remove('d-none');
                } else {
                    pane.classList.add('d-none');
                }
            });

            // Se for a aba 3 (diagramas) e ainda não renderizou Mermaid
            if (tabId === 'tab3' && !renderedMermaid) {
                mermaid.run();
                renderedMermaid = true;
            }

            if (updateHash) {
                history.replaceState(null, null, '#' + tabId);
            }

            window.scrollTo({ top: 0, behavior: 'instant' });
        }

        // Eventos nos botões da sidebar e da barra compacta
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                switchTab(this.getAttribute('data-tab'));
            });
        });

        miniTabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                switchTab(this.getAttribute('data-tab'));
            });
        });

        // Suporte a Hash na URL (ex: #tab3 ou #sec5)
        function checkHash() {
            const hash = window.location.hash.replace('#', '');
            if (hash) {
                if (document.getElementById(hash) && hash.startsWith('tab')) {
                    switchTab(hash, false);
                } else if (hash.startsWith('sec')) {
                    const tabNum = hash.replace('sec', '');
                    const targetTab = 'tab' + tabNum;
                    if (document.getElementById(targetTab)) {
                        switchTab(targetTab, false);
                    }
                }
            }
        }
        checkHash();
        window.addEventListener('hashchange', checkHash);

        // Busca rápida instantânea
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                if (!query) {
                    document.querySelectorAll('.doc-section-card').forEach(card => card.style.display = '');
                    return;
                }

                tabPanes.forEach(pane => {
                    const cards = pane.querySelectorAll('.doc-section-card');
                    let hasMatchInPane = false;

                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        if (text.includes(query)) {
                            card.style.display = '';
                            hasMatchInPane = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (hasMatchInPane && pane.classList.contains('d-none')) {
                        switchTab(pane.id);
                    }
                });
            });
        }
    });
</script>
@endpush
