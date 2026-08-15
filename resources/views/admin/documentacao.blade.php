@extends('layouts.admin')

@section('title', 'Documentação do Projeto')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════════════════════
       DOSSIÊ DE ENTREGA — Design System
       Dark-mode glassmorphism documentation page
       ═══════════════════════════════════════════════════════════════ */

    :root {
        --doc-bg-start: #0a0e1a;
        --doc-bg-end: #111832;
        --doc-accent: #00d4aa;
        --doc-accent-dim: rgba(0, 212, 170, 0.15);
        --doc-glass-bg: rgba(255, 255, 255, 0.04);
        --doc-glass-border: rgba(255, 255, 255, 0.08);
        --doc-glass-hover: rgba(255, 255, 255, 0.08);
        --doc-text-primary: #f0f4f8;
        --doc-text-secondary: rgba(240, 244, 248, 0.6);
        --doc-text-muted: rgba(240, 244, 248, 0.35);
        --doc-section-gap: 80px;
        --doc-card-radius: 20px;
        --doc-glow: 0 0 60px rgba(0, 212, 170, 0.08);
    }

    /* Override admin layout background for this page */
    .doc-page-wrapper {
        background: linear-gradient(165deg, var(--doc-bg-start) 0%, var(--doc-bg-end) 100%);
        margin: -1rem -1rem 0;
        padding: 0;
        min-height: calc(100vh - 64px);
        position: relative;
        overflow: hidden;
    }

    @media (min-width: 992px) {
        .doc-page-wrapper {
            margin: -1.5rem -1.5rem 0;
        }
    }

    /* Background grid pattern */
    .doc-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
        z-index: 0;
    }

    /* Floating orbs (background decoration) */
    .doc-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        z-index: 0;
    }
    .doc-orb--1 {
        width: 400px; height: 400px;
        background: rgba(0, 212, 170, 0.08);
        top: -100px; right: -100px;
    }
    .doc-orb--2 {
        width: 300px; height: 300px;
        background: rgba(10, 147, 150, 0.06);
        bottom: 30%; left: -80px;
    }
    .doc-orb--3 {
        width: 250px; height: 250px;
        background: rgba(0, 95, 115, 0.08);
        top: 40%; right: -50px;
    }

    /* Content wrapper */
    .doc-content {
        position: relative;
        z-index: 1;
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 24px 80px;
    }

    /* ─── Hero ─── */
    .doc-hero {
        padding: 72px 0 56px;
        text-align: center;
        position: relative;
    }
    .doc-hero__badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--doc-accent-dim);
        color: var(--doc-accent);
        border: 1px solid rgba(0, 212, 170, 0.25);
        border-radius: 50px;
        padding: 6px 18px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 24px;
    }
    .doc-hero__badge-dot {
        width: 6px; height: 6px;
        background: var(--doc-accent);
        border-radius: 50%;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }
    .doc-hero__title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: var(--doc-text-primary);
        margin-bottom: 16px;
        line-height: 1.15;
    }
    .doc-hero__title span {
        background: linear-gradient(135deg, var(--doc-accent), #0a9396);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .doc-hero__subtitle {
        font-size: 1.05rem;
        color: var(--doc-text-secondary);
        max-width: 600px;
        margin: 0 auto 32px;
        line-height: 1.6;
    }
    .doc-hero__meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    .doc-hero__meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--doc-text-muted);
        font-size: 0.82rem;
        font-weight: 500;
    }
    .doc-hero__meta-item i {
        color: var(--doc-accent);
        font-size: 0.9rem;
    }

    /* Stats row in hero */
    .doc-stats {
        display: flex;
        justify-content: center;
        gap: 48px;
        margin-top: 40px;
        flex-wrap: wrap;
    }
    .doc-stat {
        text-align: center;
    }
    .doc-stat__number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: var(--doc-accent);
        line-height: 1;
    }
    .doc-stat__label {
        font-size: 0.72rem;
        color: var(--doc-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        margin-top: 6px;
    }

    /* ─── Divider ─── */
    .doc-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--doc-glass-border), transparent);
        margin: 0 0 var(--doc-section-gap);
    }

    /* ─── TOC (Table of Contents) ─── */
    .doc-toc {
        margin-bottom: var(--doc-section-gap);
    }
    .doc-toc__title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--doc-text-primary);
        margin-bottom: 8px;
    }
    .doc-toc__subtitle {
        color: var(--doc-text-secondary);
        font-size: 0.9rem;
        margin-bottom: 32px;
    }
    .doc-toc__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }
    .doc-toc__card {
        background: var(--doc-glass-bg);
        border: 1px solid var(--doc-glass-border);
        border-radius: 16px;
        padding: 20px 24px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .doc-toc__card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, var(--doc-accent), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .doc-toc__card:hover {
        background: var(--doc-glass-hover);
        border-color: rgba(0, 212, 170, 0.2);
        transform: translateY(-2px);
        box-shadow: var(--doc-glow);
    }
    .doc-toc__card:hover::before {
        opacity: 1;
    }
    .doc-toc__card-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: var(--doc-accent-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--doc-accent);
        font-size: 1.2rem;
    }
    .doc-toc__card-content {
        flex: 1;
        min-width: 0;
    }
    .doc-toc__card-number {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--doc-accent);
        margin-bottom: 4px;
    }
    .doc-toc__card-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--doc-text-primary);
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .doc-toc__card-desc {
        font-size: 0.78rem;
        color: var(--doc-text-muted);
        line-height: 1.4;
    }
    .doc-toc__card-chevron {
        color: var(--doc-text-muted);
        font-size: 0.85rem;
        margin-top: 2px;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    .doc-toc__card:hover .doc-toc__card-chevron {
        transform: translateX(4px);
        color: var(--doc-accent);
    }

    /* ─── Section ─── */
    .doc-section {
        margin-bottom: var(--doc-section-gap);
        scroll-margin-top: 80px;
    }
    .doc-section__header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 12px;
    }
    .doc-section__number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: rgba(0, 212, 170, 0.15);
        line-height: 1;
        flex-shrink: 0;
        min-width: 56px;
    }
    .doc-section__title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--doc-text-primary);
        line-height: 1.25;
    }
    .doc-section__desc {
        color: var(--doc-text-secondary);
        font-size: 0.92rem;
        font-style: italic;
        margin-bottom: 28px;
        padding-left: 72px;
        line-height: 1.6;
    }
    @media (max-width: 575.98px) {
        .doc-section__desc {
            padding-left: 0;
        }
    }

    /* Sub-section cards */
    .doc-subsection {
        background: var(--doc-glass-bg);
        border: 1px solid var(--doc-glass-border);
        border-radius: var(--doc-card-radius);
        padding: 28px 28px 24px;
        margin-bottom: 16px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: border-color 0.3s ease;
    }
    .doc-subsection:hover {
        border-color: rgba(0, 212, 170, 0.15);
    }
    .doc-subsection__header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .doc-subsection__icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--doc-accent-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--doc-accent);
        font-size: 1rem;
        flex-shrink: 0;
    }
    .doc-subsection__title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--doc-text-primary);
        margin: 0;
    }
    .doc-subsection__body {
        color: var(--doc-text-secondary);
        font-size: 0.88rem;
        line-height: 1.7;
    }
    .doc-subsection__body ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .doc-subsection__body ul li {
        position: relative;
        padding-left: 20px;
        margin-bottom: 8px;
    }
    .doc-subsection__body ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 9px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--doc-accent);
        opacity: 0.6;
    }
    .doc-subsection__body strong {
        color: var(--doc-text-primary);
        font-weight: 600;
    }

    /* Nested list for deeper items */
    .doc-subsection__body ul ul {
        margin-top: 8px;
        margin-left: 8px;
    }
    .doc-subsection__body ul ul li::before {
        width: 4px;
        height: 4px;
        background: var(--doc-accent);
        opacity: 0.35;
        border-radius: 1px;
    }

    /* ─── Back to Top Button ─── */
    .doc-back-to-top {
        position: fixed;
        bottom: 32px;
        right: 32px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--doc-accent);
        color: var(--doc-bg-start);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 8px 32px rgba(0, 212, 170, 0.3);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
        z-index: 1050;
    }
    .doc-back-to-top.visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .doc-back-to-top:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 40px rgba(0, 212, 170, 0.45);
    }

    /* ─── Utility ─── */
    /* .doc-reveal: estado inicial é VISÍVEL (progressive enhancement).
       O GSAP aplica opacity:0 via JS — se o CDN falhar, conteúdo aparece normalmente. */
    .doc-reveal {
        /* Sem opacity:0 aqui — definido pelo GSAP via gsap.set() */
    }

    /* Progress indicator for TOC cards */
    .doc-toc__progress {
        height: 3px;
        background: rgba(255,255,255,0.06);
        border-radius: 2px;
        margin-top: 12px;
        overflow: hidden;
    }
    .doc-toc__progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--doc-accent), #0a9396);
        border-radius: 2px;
        width: 0%;
        transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .doc-hero {
            padding: 48px 0 40px;
        }
        .doc-hero__title {
            font-size: 1.8rem;
        }
        .doc-stats {
            gap: 32px;
        }
        .doc-toc__grid {
            grid-template-columns: 1fr;
        }
        .doc-section__number {
            font-size: 2rem;
            min-width: 40px;
        }
        .doc-section__title {
            font-size: 1.3rem;
        }
        .doc-subsection {
            padding: 20px;
        }
        .doc-content {
            padding: 0 16px 60px;
        }
        .doc-back-to-top {
            bottom: 20px;
            right: 20px;
            width: 42px;
            height: 42px;
        }
    }
</style>
@endpush

@section('content')
<div class="doc-page-wrapper" id="docTop">
    <!-- Background Orbs -->
    <div class="doc-orb doc-orb--1"></div>
    <div class="doc-orb doc-orb--2"></div>
    <div class="doc-orb doc-orb--3"></div>

    <div class="doc-content">

        <!-- ═══ HERO ═══ -->
        <section class="doc-hero">
            <div class="doc-hero__badge doc-reveal">
                <span class="doc-hero__badge-dot"></span>
                Handover Documentation
            </div>
            <h1 class="doc-hero__title doc-reveal">
                Dossiê de <span>Entrega</span>
            </h1>
            <p class="doc-hero__subtitle doc-reveal">
                Documentação completa do projeto <strong style="color: var(--doc-text-primary);">Destino Inteligente</strong> — Plataforma de Gestão e Inteligência Turística Municipal.
            </p>
            <div class="doc-hero__meta doc-reveal">
                <span class="doc-hero__meta-item">
                    <i class="bi bi-calendar3"></i>
                    {{ date('d/m/Y') }}
                </span>
                <span class="doc-hero__meta-item">
                    <i class="bi bi-tag"></i>
                    v1.0.0
                </span>
                <span class="doc-hero__meta-item">
                    <i class="bi bi-file-earmark-text"></i>
                    9 Seções
                </span>
                <span class="doc-hero__meta-item">
                    <i class="bi bi-diagram-3"></i>
                    40+ Tópicos
                </span>
            </div>

            <div class="doc-stats doc-reveal">
                <div class="doc-stat">
                    <div class="doc-stat__number" data-counter="9">0</div>
                    <div class="doc-stat__label">Seções</div>
                </div>
                <div class="doc-stat">
                    <div class="doc-stat__number" data-counter="40">0</div>
                    <div class="doc-stat__label">Tópicos</div>
                </div>
                <div class="doc-stat">
                    <div class="doc-stat__number" data-counter="3">0</div>
                    <div class="doc-stat__label">Diagramas</div>
                </div>
                <div class="doc-stat">
                    <div class="doc-stat__number" data-counter="9">0</div>
                    <div class="doc-stat__label">Checklists</div>
                </div>
            </div>
        </section>

        <div class="doc-divider"></div>

        <!-- ═══ TABLE OF CONTENTS ═══ -->
        <section class="doc-toc" id="docToc">
            <h2 class="doc-toc__title doc-reveal">Sumário</h2>
            <p class="doc-toc__subtitle doc-reveal">Navegue pelas seções do dossiê de entrega</p>

            <div class="doc-toc__grid">
                @php
                $tocItems = [
                    ['num' => '01', 'icon' => 'bi-briefcase', 'title' => 'Visão Geral e Negócios', 'desc' => 'Valor do produto, licenciamento e roadmap', 'target' => 'sec1', 'items' => 4],
                    ['num' => '02', 'icon' => 'bi-people', 'title' => 'Documentação Funcional', 'desc' => 'Casos de uso, manuais e regras de negócio', 'target' => 'sec2', 'items' => 4],
                    ['num' => '03', 'icon' => 'bi-diagram-3', 'title' => 'Arquitetura e Design', 'desc' => 'Diagramas C4, ADRs e modelagem de dados', 'target' => 'sec3', 'items' => 4],
                    ['num' => '04', 'icon' => 'bi-code-slash', 'title' => 'Engenharia e Código', 'desc' => 'Stack, repositórios e guia de setup', 'target' => 'sec4', 'items' => 4],
                    ['num' => '05', 'icon' => 'bi-plug', 'title' => 'Integrações e APIs', 'desc' => 'Swagger, autenticação e webhooks', 'target' => 'sec5', 'items' => 3],
                    ['num' => '06', 'icon' => 'bi-cloud-arrow-up', 'title' => 'Infraestrutura e DevOps', 'desc' => 'Topologia, containers e CI/CD', 'target' => 'sec6', 'items' => 5],
                    ['num' => '07', 'icon' => 'bi-heart-pulse', 'title' => 'Suporte e Observabilidade', 'desc' => 'Monitoramento, logs e recovery', 'target' => 'sec7', 'items' => 4],
                    ['num' => '08', 'icon' => 'bi-check2-square', 'title' => 'Qualidade e Testes', 'desc' => 'Estratégia, cobertura e carga', 'target' => 'sec8', 'items' => 3],
                    ['num' => '09', 'icon' => 'bi-shield-lock', 'title' => 'Segurança e Conformidade', 'desc' => 'Vulnerabilidades, RBAC e LGPD', 'target' => 'sec9', 'items' => 3],
                ];
                @endphp

                @foreach($tocItems as $toc)
                <a href="#{{ $toc['target'] }}" class="doc-toc__card doc-reveal" data-toc-link>
                    <div class="doc-toc__card-icon">
                        <i class="bi {{ $toc['icon'] }}"></i>
                    </div>
                    <div class="doc-toc__card-content">
                        <div class="doc-toc__card-number">Seção {{ $toc['num'] }}</div>
                        <div class="doc-toc__card-title">{{ $toc['title'] }}</div>
                        <div class="doc-toc__card-desc">{{ $toc['desc'] }}</div>
                        <div class="doc-toc__progress">
                            <div class="doc-toc__progress-bar" data-width="{{ $toc['items'] * 25 }}"></div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right doc-toc__card-chevron"></i>
                </a>
                @endforeach
            </div>
        </section>

        <div class="doc-divider"></div>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 1 — Visão Geral e Negócios
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec1">
            <div class="doc-section__header">
                <span class="doc-section__number">01</span>
                <h2 class="doc-section__title">Visão Geral e Negócios</h2>
            </div>
            <p class="doc-section__desc">Esta seção prova o valor do produto e define exatamente o que está sendo transferido.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-bullseye"></i></div>
                    <h3 class="doc-subsection__title">1.1. Resumo Executivo</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>O que o software faz, qual problema resolve e quem é o público-alvo.</p>
                    <ul>
                        <li><strong>Produto:</strong> Destino Inteligente — Plataforma SaaS de gestão e inteligência turística para municípios brasileiros.</li>
                        <li><strong>Problema:</strong> Falta de centralização e inteligência na gestão do turismo municipal, levando a decisões sem dados e experiência fragmentada do turista.</li>
                        <li><strong>Público-alvo:</strong> Gestores públicos municipais (prefeituras, secretarias de turismo), empreendedores locais e turistas visitantes.</li>
                        <li><strong>Proposta de valor:</strong> PWA para turistas com IA conversacional + painel administrativo completo para gestores com KPIs, heatmaps e relatórios.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-file-earmark-lock"></i></div>
                    <h3 class="doc-subsection__title">1.2. Propriedade Intelectual e Licenciamento</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Relação de licenças de código aberto utilizadas para garantir que não há infração de <em>copyright</em>.</p>
                    <ul>
                        <li><strong>Laravel Framework:</strong> MIT License</li>
                        <li><strong>Bootstrap 5:</strong> MIT License</li>
                        <li><strong>Leaflet:</strong> BSD 2-Clause License</li>
                        <li><strong>Alpine.js:</strong> MIT License</li>
                        <li><strong>GSAP (uso não-comercial):</strong> Standard License / verificar para uso comercial</li>
                        <li><strong>Bootstrap Icons:</strong> MIT License</li>
                        <li>Todas as dependências listadas em <code>composer.json</code> e <code>package.json</code> seguem licenças permissivas (MIT/Apache/BSD).</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-credit-card-2-back"></i></div>
                    <h3 class="doc-subsection__title">1.3. Contratos de Terceiros e SaaS</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Lista de todas as dependências pagas ou externas e seus respectivos custos mensais.</p>
                    <ul>
                        <li><strong>Hospedagem / Infraestrutura:</strong> Coolify (self-hosted) ou provedor cloud (AWS/DigitalOcean)</li>
                        <li><strong>OpenStreetMap / Nominatim:</strong> API gratuita de geocodificação (sem custo)</li>
                        <li><strong>Leaflet Maps:</strong> Biblioteca open-source (sem custo de licença)</li>
                        <li><strong>API de IA (Assistente):</strong> Verificar provedor contratado (OpenAI / Gemini) — custo por token/requisição</li>
                        <li><strong>Serviço de E-mail:</strong> Verificar configuração SMTP (Mailgun, SES, ou similar)</li>
                        <li><strong>Domínio e SSL:</strong> Registro anual de domínio + certificado (Let's Encrypt gratuito ou pago)</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-kanban"></i></div>
                    <h3 class="doc-subsection__title">1.4. Roadmap e Backlog</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Funcionalidades planejadas, dívidas técnicas conhecidas e bugs mapeados no momento da entrega.</p>
                    <ul>
                        <li><strong>Funcionalidades planejadas:</strong> Sistema de avaliações de atrativos, gamificação com badges, painel de analytics avançado com BI.</li>
                        <li><strong>Dívidas técnicas:</strong> Refatoração de queries N+1 em listagens, melhoria na cobertura de testes, otimização de imagens no upload.</li>
                        <li><strong>Bugs conhecidos:</strong> Verificar issue tracker do repositório para lista atualizada.</li>
                        <li><strong>Priorização:</strong> Backlog gerenciado via GitHub Issues / Projects.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 2 — Documentação Funcional
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec2">
            <div class="doc-section__header">
                <span class="doc-section__number">02</span>
                <h2 class="doc-section__title">Documentação Funcional</h2>
            </div>
            <p class="doc-section__desc">Como o sistema funciona na perspectiva de quem o utiliza.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-person-walking"></i></div>
                    <h3 class="doc-subsection__title">2.1. Casos de Uso e Histórias de Usuário</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Descrição detalhada das principais jornadas do usuário dentro da aplicação.</p>
                    <ul>
                        <li><strong>Turista:</strong> Explorar atrativos → Visualizar detalhes → Consultar IA → Seguir roteiro → Navegar no mapa.</li>
                        <li><strong>Empreendedor:</strong> Cadastrar-se como parceiro → Submeter atrativo/negócio → Aguardar validação → Gerenciar listagem.</li>
                        <li><strong>Gestor Público:</strong> Login → Dashboard KPIs → Gerenciar atrativos/eventos/roteiros → Emitir alertas → Validar prestadores → Exportar relatórios.</li>
                        <li><strong>Administrador:</strong> Todas as funções do gestor + gerenciamento de usuários + auditoria de logs + configurações do sistema.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-book"></i></div>
                    <h3 class="doc-subsection__title">2.2. Manuais do Usuário Final</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Guias de utilização (podem ser em formato de texto, FAQs ou vídeos).</p>
                    <ul>
                        <li><strong>Guia de Exploração:</strong> Como buscar e filtrar atrativos turísticos por categoria, localização e acessibilidade.</li>
                        <li><strong>Assistente IA:</strong> Como utilizar o chat de IA para obter recomendações personalizadas de roteiros e pontos turísticos.</li>
                        <li><strong>Mapa Interativo:</strong> Navegação, zoom, seleção de pontos e visualização de informações em tempo real.</li>
                        <li><strong>PWA (Progressive Web App):</strong> Como instalar no dispositivo mobile para acesso offline parcial.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-gear-wide-connected"></i></div>
                    <h3 class="doc-subsection__title">2.3. Manuais do Administrador / Backoffice</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Como operar os painéis internos, gerir usuários, emitir relatórios e configurar parâmetros do sistema.</p>
                    <ul>
                        <li><strong>Dashboard de KPIs:</strong> Interpretação dos indicadores de atrativos ativos, eventos, interações IA e prestadores.</li>
                        <li><strong>Gestão de Atrativos:</strong> CRUD completo — criar, editar, ativar/desativar e excluir pontos turísticos com geolocalização.</li>
                        <li><strong>Gestão de Eventos:</strong> Cadastro de eventos culturais com datas, localização e descrições.</li>
                        <li><strong>Gestão de Roteiros:</strong> Criação de roteiros compostos por múltiplos atrativos com ordenação.</li>
                        <li><strong>Alertas e Defesa Civil:</strong> Emissão de alertas de segurança para turistas com níveis de severidade.</li>
                        <li><strong>Validação de Parceiros:</strong> Aprovação/rejeição de cadastros de empreendedores locais.</li>
                        <li><strong>Auditoria de Logs:</strong> Rastreamento de ações administrativas para compliance.</li>
                        <li><strong>Exportação de Relatórios:</strong> Geração de CSV com dados filtrados.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-journal-text"></i></div>
                    <h3 class="doc-subsection__title">2.4. Dicionário de Regras de Negócio</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Documentação de cálculos específicos, validações, fluxos e políticas restritivas embutidas no código.</p>
                    <ul>
                        <li><strong>Validação de Prestadores:</strong> Fluxo pendente → aprovado/rejeitado, com registro de auditoria obrigatório.</li>
                        <li><strong>Controle de Acesso (RBAC):</strong> Roles <code>super_admin</code>, <code>prefeito</code>, <code>secretario</code>, <code>gestor_conteudo</code>, <code>gestor_cadastros</code>, <code>empreendedor</code>.</li>
                        <li><strong>Geolocalização:</strong> Validação de coordenadas via API Nominatim/OpenStreetMap antes de persistir.</li>
                        <li><strong>Cálculo de KPIs:</strong> Contagem ativa de atrativos, eventos vigentes, interações de IA e prestadores validados.</li>
                        <li><strong>Heatmap de Fluxo:</strong> Agregação de dados de analytics por coordenada para visualização de calor no mapa.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 3 — Arquitetura e Design de Software
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec3">
            <div class="doc-section__header">
                <span class="doc-section__number">03</span>
                <h2 class="doc-section__title">Arquitetura e Design de Software</h2>
            </div>
            <p class="doc-section__desc">O mapa mental de como o sistema foi projetado e as decisões por trás dele.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-layers"></i></div>
                    <h3 class="doc-subsection__title">3.1. Visão Geral Arquitetural</h3>
                </div>
                <div class="doc-subsection__body">
                    <ul>
                        <li><strong>Padrão Arquitetural:</strong> Monolito Modular (Laravel MVC) com API REST separada para o PWA.</li>
                        <li><strong>Frontend PWA:</strong> Blade templates com vanilla JavaScript + Leaflet + Alpine.js — sem SPA framework.</li>
                        <li><strong>Painel Admin:</strong> Server-side rendering com Blade + Bootstrap 5 — otimizado para produtividade do gestor.</li>
                        <li><strong>API Layer:</strong> RESTful JSON sob <code>/api/v1/</code> com autenticação Sanctum para mobile/PWA.</li>
                        <li><strong>Banco de Dados:</strong> PostgreSQL como SGBD principal com suporte a extensões geoespaciais.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-diagram-2"></i></div>
                    <h3 class="doc-subsection__title">3.2. Diagramas de Arquitetura (Modelo C4)</h3>
                </div>
                <div class="doc-subsection__body">
                    <ul>
                        <li><strong>Nível 1 (Contexto):</strong> Como o sistema interage com usuários e sistemas externos.
                            <ul>
                                <li>Turistas acessam o PWA via navegador mobile</li>
                                <li>Gestores acessam o Painel Admin via navegador desktop/tablet</li>
                                <li>Integrações externas: OpenStreetMap (geocoding), API de IA (chatbot), SMTP (emails)</li>
                            </ul>
                        </li>
                        <li><strong>Nível 2 (Containers):</strong> Aplicações web, APIs, bancos de dados e filas de mensagens.
                            <ul>
                                <li>Container Web (Laravel): Serve PWA + Admin + API</li>
                                <li>Container DB: PostgreSQL</li>
                                <li>Container Nginx: Reverse proxy + serving de assets estáticos</li>
                                <li>Container Queue Worker: Jobs assíncronos (Laravel Queue)</li>
                            </ul>
                        </li>
                        <li><strong>Nível 3 (Componentes):</strong> Estrutura interna das APIs e interfaces.
                            <ul>
                                <li>Controllers: Web (admin/pwa) + API (v1)</li>
                                <li>Services: IA, Geocoding, Analytics, QRCode</li>
                                <li>Models: Atrativo, Evento, Roteiro, Prestador, Alerta, User</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-clock-history"></i></div>
                    <h3 class="doc-subsection__title">3.3. Registros de Decisão Arquitetural (ADRs)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Um log histórico explicando o porquê de certas tecnologias ou padrões terem sido escolhidos.</p>
                    <ul>
                        <li><strong>ADR-001:</strong> Laravel escolhido por maturidade do ecossistema PHP, produtividade para MVPs e disponibilidade de mão de obra no mercado brasileiro.</li>
                        <li><strong>ADR-002:</strong> Bootstrap 5 para admin por rapidez de desenvolvimento e familiaridade da equipe, sem necessidade de design system customizado.</li>
                        <li><strong>ADR-003:</strong> PostgreSQL em vez de MySQL pela melhor suporte a JSON, extensões geoespaciais (PostGIS futuro) e performance em queries complexas.</li>
                        <li><strong>ADR-004:</strong> PWA em vez de app nativo para eliminar barreiras de instalação em lojas de apps e reduzir custo de desenvolvimento.</li>
                        <li><strong>ADR-005:</strong> Monolito modular em vez de microsserviços por escala do projeto — complexidade de micro não se justifica na fase atual.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-database"></i></div>
                    <h3 class="doc-subsection__title">3.4. Modelagem de Dados</h3>
                </div>
                <div class="doc-subsection__body">
                    <ul>
                        <li><strong>Diagrama Entidade-Relacionamento (DER):</strong> Disponível via ferramenta de DB ou gerado a partir das migrations Laravel.</li>
                        <li><strong>Tabelas Principais:</strong>
                            <ul>
                                <li><code>users</code> — Usuários com role-based access</li>
                                <li><code>atrativos</code> — Pontos turísticos com lat/lng e metadados</li>
                                <li><code>eventos</code> — Agenda cultural com datas e localização</li>
                                <li><code>roteiros</code> / <code>roteiro_items</code> — Roteiros compostos</li>
                                <li><code>prestadores</code> — Empreendedores parceiros com status de validação</li>
                                <li><code>alertas</code> — Alertas de defesa civil</li>
                                <li><code>analytic_events</code> — Rastreamento de interações</li>
                                <li><code>assistant_logs</code> — Logs de interação com IA</li>
                            </ul>
                        </li>
                        <li><strong>Versionamento de schema:</strong> Laravel Migrations (diretório <code>database/migrations/</code>).</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 4 — Engenharia e Código-Fonte
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec4">
            <div class="doc-section__header">
                <span class="doc-section__number">04</span>
                <h2 class="doc-section__title">Engenharia e Código-Fonte</h2>
            </div>
            <p class="doc-section__desc">O guia de sobrevivência para a nova equipe de desenvolvedores.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-stack"></i></div>
                    <h3 class="doc-subsection__title">4.1. Stack Tecnológico</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Matriz completa com linguagens, frameworks, bibliotecas principais e suas respectivas versões.</p>
                    <ul>
                        <li><strong>Backend:</strong> PHP 8.2+ / Laravel 11.x</li>
                        <li><strong>Frontend Admin:</strong> Bootstrap 5.3.3 / Bootstrap Icons 1.11.3</li>
                        <li><strong>Frontend PWA:</strong> HTML5 / Vanilla JS / Alpine.js 3.x</li>
                        <li><strong>Mapas:</strong> Leaflet 1.9.4 / OpenStreetMap tiles</li>
                        <li><strong>Banco de Dados:</strong> PostgreSQL 15+</li>
                        <li><strong>Build Tools:</strong> Vite 6.x / Laravel Vite Plugin</li>
                        <li><strong>CSS:</strong> TailwindCSS 3.x (PWA) + Bootstrap 5 (Admin) — build via PostCSS</li>
                        <li><strong>Tipografia:</strong> Plus Jakarta Sans + Work Sans (Google Fonts via Bunny)</li>
                        <li><strong>Containerização:</strong> Docker + Docker Compose</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-folder2-open"></i></div>
                    <h3 class="doc-subsection__title">4.2. Estrutura de Repositórios</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Mapa de onde cada parte do código vive.</p>
                    <ul>
                        <li><strong>Repositório único (monorepo):</strong> Todo o código frontend e backend no mesmo repositório.</li>
                        <li><code>app/</code> — Lógica de aplicação (Controllers, Models, Services, Providers)</li>
                        <li><code>resources/views/</code> — Templates Blade (admin/, pwa/, layouts/, components/)</li>
                        <li><code>resources/css/</code> — Estilos (Tailwind para PWA)</li>
                        <li><code>resources/js/</code> — JavaScript compilável via Vite</li>
                        <li><code>routes/</code> — Definições de rotas (web.php, api.php, auth.php)</li>
                        <li><code>database/migrations/</code> — Migrations do banco de dados</li>
                        <li><code>config/</code> — Configurações do Laravel</li>
                        <li><code>docker/</code> — Arquivos de configuração Docker</li>
                        <li><code>public/</code> — Assets públicos, manifest PWA, service worker</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-terminal"></i></div>
                    <h3 class="doc-subsection__title">4.3. Guia de Configuração Local (Setup)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Passo a passo para um novo desenvolvedor clonar, instalar e rodar o projeto localmente.</p>
                    <ul>
                        <li><strong>1.</strong> Clonar o repositório: <code>git clone &lt;repo-url&gt;</code></li>
                        <li><strong>2.</strong> Copiar variáveis de ambiente: <code>cp .env.example .env</code></li>
                        <li><strong>3.</strong> Instalar dependências PHP: <code>composer install</code></li>
                        <li><strong>4.</strong> Instalar dependências Node: <code>npm install</code></li>
                        <li><strong>5.</strong> Gerar chave da aplicação: <code>php artisan key:generate</code></li>
                        <li><strong>6.</strong> Configurar banco de dados no <code>.env</code> (PostgreSQL)</li>
                        <li><strong>7.</strong> Executar migrations: <code>php artisan migrate --seed</code></li>
                        <li><strong>8.</strong> Compilar assets: <code>npm run dev</code> (desenvolvimento) ou <code>npm run build</code> (produção)</li>
                        <li><strong>9.</strong> Iniciar servidor: <code>php artisan serve</code></li>
                        <li><strong>Alternativa Docker:</strong> <code>docker-compose up -d</code></li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-braces"></i></div>
                    <h3 class="doc-subsection__title">4.4. Padrões de Código e Guias de Estilo</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Regras de linting, formatação, convenções de nomenclatura e fluxo de versionamento.</p>
                    <ul>
                        <li><strong>PHP:</strong> PSR-12 (coding style) via EditorConfig — configuração em <code>.editorconfig</code>.</li>
                        <li><strong>JavaScript:</strong> Vanilla JS com ES6+ modules.</li>
                        <li><strong>Git Flow:</strong> Branch <code>main</code> (produção), <code>develop</code> (staging), <code>feature/*</code> para novas funcionalidades.</li>
                        <li><strong>Commits:</strong> Conventional Commits recomendado (<code>feat:</code>, <code>fix:</code>, <code>docs:</code>, <code>refactor:</code>).</li>
                        <li><strong>Nomenclatura:</strong> Controllers em PascalCase, routes em kebab-case, variáveis em camelCase (PHP/JS).</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 5 — Integrações e APIs
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec5">
            <div class="doc-section__header">
                <span class="doc-section__number">05</span>
                <h2 class="doc-section__title">Integrações e APIs</h2>
            </div>
            <p class="doc-section__desc">Como o sistema se comunica com o mundo exterior.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-arrow-left-right"></i></div>
                    <h3 class="doc-subsection__title">5.1. Documentação de APIs Internas e Públicas</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Especificações no padrão OpenAPI/Swagger com detalhamento de endpoints.</p>
                    <ul>
                        <li><strong>Base URL:</strong> <code>/api/v1/</code></li>
                        <li><strong>Atrativos:</strong> <code>GET /api/v1/atrativos</code> — Lista paginada com filtros; <code>GET /api/v1/atrativos/{id}</code> — Detalhes.</li>
                        <li><strong>Eventos:</strong> <code>GET /api/v1/eventos</code> — Eventos ativos com filtro de data.</li>
                        <li><strong>Roteiros:</strong> <code>GET /api/v1/roteiros</code> — Roteiros com itens relacionados.</li>
                        <li><strong>IA/Chat:</strong> <code>POST /api/v1/assistant</code> — Envio de mensagem ao assistente IA.</li>
                        <li><strong>Geocoding:</strong> <code>GET /api/v1/location/search</code> — Proxy para OpenStreetMap Nominatim.</li>
                        <li><strong>Analytics:</strong> <code>POST /api/v1/analytics/event</code> — Registro de evento de analytics.</li>
                        <li><strong>Documentação Scribe:</strong> Disponível em <code>/docs</code> (gerada via Laravel Scribe).</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-key"></i></div>
                    <h3 class="doc-subsection__title">5.2. Fluxo de Autenticação / Autorização</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Como os tokens são gerados, validados e renovados.</p>
                    <ul>
                        <li><strong>Web (Admin/PWA):</strong> Autenticação session-based via Laravel Breeze (cookies + CSRF).</li>
                        <li><strong>API:</strong> Laravel Sanctum com tokens de API para consumo mobile/SPA.</li>
                        <li><strong>Roles/Permissions:</strong> Middleware customizado <code>role:</code> verificando a coluna <code>role</code> na tabela <code>users</code>.</li>
                        <li><strong>Fluxo de Login:</strong> POST <code>/login</code> → validação de credenciais → criação de sessão → redirect para dashboard.</li>
                        <li><strong>Logout:</strong> POST <code>/logout</code> → invalidação de sessão/token.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-broadcast"></i></div>
                    <h3 class="doc-subsection__title">5.3. Webhooks e Eventos</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Catálogo de eventos emitidos e consumidos pelo sistema.</p>
                    <ul>
                        <li><strong>Events internos (Laravel Events):</strong> Disparados em ações como criação de alertas, validação de prestadores e interações com IA.</li>
                        <li><strong>Analytics Events:</strong> Rastreados via tabela <code>analytic_events</code> — page_view, atrativo_view, ia_interaction, map_interaction.</li>
                        <li><strong>Webhooks externos:</strong> Atualmente não há integração webhook ativa — preparado para futura integração com sistemas municipais.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 6 — Infraestrutura, Deploy e DevOps
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec6">
            <div class="doc-section__header">
                <span class="doc-section__number">06</span>
                <h2 class="doc-section__title">Infraestrutura, Deploy e DevOps</h2>
            </div>
            <p class="doc-section__desc">Como o sistema ganha vida em produção e se mantém no ar.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-hdd-network"></i></div>
                    <h3 class="doc-subsection__title">6.1. Topologia de Infraestrutura</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Diagrama de rede mostrando servidores, balanceadores de carga, firewalls, gateways e bancos de dados gerenciados.</p>
                    <ul>
                        <li><strong>Servidor de Aplicação:</strong> Container Docker com PHP-FPM + Nginx.</li>
                        <li><strong>Banco de Dados:</strong> Container PostgreSQL (ou instância gerenciada em produção).</li>
                        <li><strong>Reverse Proxy:</strong> Nginx ou Caddy (via Coolify) com SSL automático (Let's Encrypt).</li>
                        <li><strong>Orquestração:</strong> Coolify para gerenciamento simplificado (alternativa ao Kubernetes para projetos menores).</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-file-earmark-code"></i></div>
                    <h3 class="doc-subsection__title">6.2. Infraestrutura como Código (IaC)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Documentação dos scripts de provisionamento.</p>
                    <ul>
                        <li><strong>Dockerfile:</strong> Build da aplicação Laravel em container otimizado (multi-stage build).</li>
                        <li><strong>Dockerfile.postgres:</strong> Configuração customizada do PostgreSQL com extensões necessárias.</li>
                        <li><strong>Docker Compose:</strong> Orquestração local com serviços app, db, nginx — arquivo <code>docker-compose.yml</code>.</li>
                        <li><strong>Observação:</strong> Não há Terraform/Ansible no momento — infraestrutura provisionada manualmente via Coolify ou console do cloud provider.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-box-seam"></i></div>
                    <h3 class="doc-subsection__title">6.3. Containerização e Orquestração</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Especificação de imagens Docker, composição e orquestração de rede.</p>
                    <ul>
                        <li><strong>Imagem Base:</strong> PHP 8.2-FPM Alpine + extensões (pdo_pgsql, gd, bcmath, etc).</li>
                        <li><strong>Volumes:</strong> <code>storage/</code> para uploads e cache; <code>database/</code> para persistência do PostgreSQL.</li>
                        <li><strong>Rede:</strong> Network interna Docker entre containers app, db e nginx.</li>
                        <li><strong>Deploy:</strong> Coolify (interface web) para gerenciamento visual de deploy, rollback e logs.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-arrow-repeat"></i></div>
                    <h3 class="doc-subsection__title">6.4. Pipelines de CI/CD</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Desenho do fluxo de integração e entrega contínua.</p>
                    <ul>
                        <li><strong>Trigger:</strong> Push para branch <code>main</code> ou merge de Pull Request.</li>
                        <li><strong>Pipeline:</strong> GitHub Actions — install → lint → test → build Docker image → deploy via Coolify webhook.</li>
                        <li><strong>Testes Automatizados:</strong> PHPUnit rodando no pipeline antes do deploy.</li>
                        <li><strong>Rollback:</strong> Via Coolify (deploy da imagem anterior) ou <code>git revert</code> + re-deploy.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-lock"></i></div>
                    <h3 class="doc-subsection__title">6.5. Gestão de Variáveis e Segredos</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Onde e como as chaves de API, senhas e certificados estão armazenados de forma segura.</p>
                    <ul>
                        <li><strong>Desenvolvimento:</strong> Arquivo <code>.env</code> (nunca versionado — listado no <code>.gitignore</code>).</li>
                        <li><strong>Produção:</strong> Variáveis de ambiente configuradas no Coolify ou no orchestrator do cloud.</li>
                        <li><strong>Referência:</strong> <code>.env.example</code> documenta todas as variáveis necessárias com valores placeholder.</li>
                        <li><strong>Chaves críticas:</strong> <code>APP_KEY</code>, <code>DB_PASSWORD</code>, API keys de IA, SMTP credentials.</li>
                        <li><strong>Rotação:</strong> Recomendado rotacionar API keys e DB passwords a cada 90 dias.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 7 — Suporte, Manutenção e Observabilidade
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec7">
            <div class="doc-section__header">
                <span class="doc-section__number">07</span>
                <h2 class="doc-section__title">Suporte, Manutenção e Observabilidade</h2>
            </div>
            <p class="doc-section__desc">O plano de ação para quando os problemas acontecerem.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-activity"></i></div>
                    <h3 class="doc-subsection__title">7.1. Monitoramento e Alertas</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Quais ferramentas monitoram a saúde da aplicação e quem é notificado.</p>
                    <ul>
                        <li><strong>Uptime Monitoring:</strong> Configurar via Coolify health checks ou serviço externo (UptimeRobot, BetterStack).</li>
                        <li><strong>Métricas de Servidor:</strong> CPU, RAM e disco via painel do Coolify ou monitoring do cloud provider.</li>
                        <li><strong>Alertas:</strong> Notificações por e-mail/Slack em caso de downtime ou uso excessivo de recursos.</li>
                        <li><strong>Application Health:</strong> Endpoint <code>/api/health</code> retorna status da aplicação e conexão com DB.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-terminal-fill"></i></div>
                    <h3 class="doc-subsection__title">7.2. Rastreamento de Logs</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Onde os logs da aplicação são agregados e como consultá-los.</p>
                    <ul>
                        <li><strong>Logs da Aplicação:</strong> <code>storage/logs/laravel.log</code> — rotacionado diariamente.</li>
                        <li><strong>Logs de Acesso:</strong> Nginx access/error logs nos containers.</li>
                        <li><strong>Logs de Container:</strong> <code>docker logs &lt;container&gt;</code> ou via interface Coolify.</li>
                        <li><strong>Log de Auditoria:</strong> Tabela <code>analytic_events</code> para rastreamento de ações do sistema.</li>
                        <li><strong>Futuro:</strong> Recomendado integrar com serviço centralizado (Sentry, Papertrail ou equivalente).</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-journal-medical"></i></div>
                    <h3 class="doc-subsection__title">7.3. Runbooks (Procedimentos de Recuperação)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Instruções passo a passo para resolver incidentes comuns.</p>
                    <ul>
                        <li><strong>Banco de dados travou:</strong> 1) Verificar logs do container PostgreSQL; 2) Reiniciar container: <code>docker restart postgres</code>; 3) Verificar espaço em disco; 4) Checar conexões ativas.</li>
                        <li><strong>Aplicação retornando 500:</strong> 1) Verificar <code>storage/logs/laravel.log</code>; 2) Limpar cache: <code>php artisan cache:clear && php artisan config:clear</code>; 3) Reiniciar PHP-FPM.</li>
                        <li><strong>Queue workers parados:</strong> 1) Verificar status: <code>php artisan queue:retry all</code>; 2) Reiniciar workers: <code>php artisan queue:restart</code>.</li>
                        <li><strong>SSL expirado:</strong> 1) Renovar via Coolify (automático) ou <code>certbot renew</code>; 2) Reiniciar Nginx.</li>
                        <li><strong>Espaço em disco cheio:</strong> 1) Limpar logs antigos; 2) Remover Docker images não utilizadas: <code>docker system prune</code>; 3) Rotacionar backups.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                    <h3 class="doc-subsection__title">7.4. Plano de Backup e DRP (Disaster Recovery)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Rotinas de backup de banco de dados e arquivos estáticos, tempo de retenção e processo de restauração.</p>
                    <ul>
                        <li><strong>Backup do Banco:</strong> <code>pg_dump</code> automatizado via cron (diário) + upload para storage externo (S3/equivalente).</li>
                        <li><strong>Backup de Arquivos:</strong> Diretório <code>storage/app/</code> (uploads) — sincronizado periodicamente.</li>
                        <li><strong>Retenção:</strong> 30 dias de backups diários + 12 meses de backups mensais (recomendado).</li>
                        <li><strong>RPO (Recovery Point Objective):</strong> Máximo 24 horas de perda de dados aceitável.</li>
                        <li><strong>RTO (Recovery Time Objective):</strong> Sistema restaurado em até 4 horas.</li>
                        <li><strong>Processo de Restauração:</strong> 1) Provisionar nova instância; 2) Restaurar backup do DB: <code>pg_restore</code>; 3) Deploy da última imagem Docker; 4) Restaurar uploads; 5) Validar funcionamento.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 8 — Qualidade e Testes
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec8">
            <div class="doc-section__header">
                <span class="doc-section__number">08</span>
                <h2 class="doc-section__title">Qualidade e Testes</h2>
            </div>
            <p class="doc-section__desc">A garantia de que o sistema é estável.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-clipboard2-check"></i></div>
                    <h3 class="doc-subsection__title">8.1. Estratégia de Testes</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Como o projeto divide testes unitários, de integração e de ponta a ponta (E2E).</p>
                    <ul>
                        <li><strong>Framework:</strong> PHPUnit (configuração em <code>phpunit.xml</code>).</li>
                        <li><strong>Testes Unitários:</strong> Models, Services e helpers com mocks de dependências externas.</li>
                        <li><strong>Testes de Integração:</strong> Feature tests com banco de dados em memória (SQLite) para validar fluxos completos de request/response.</li>
                        <li><strong>Testes E2E:</strong> Não implementados na fase atual — recomendado para expansão futura (Laravel Dusk ou Playwright).</li>
                        <li><strong>Execução:</strong> <code>php artisan test</code> ou <code>vendor/bin/phpunit</code>.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-bar-chart-line"></i></div>
                    <h3 class="doc-subsection__title">8.2. Relatórios de Cobertura</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Relatório atual das métricas de cobertura de código.</p>
                    <ul>
                        <li><strong>Geração:</strong> <code>php artisan test --coverage</code> (requer Xdebug ou PCOV).</li>
                        <li><strong>Meta de Cobertura:</strong> Mínimo 60% para camadas de Service e Controller (recomendado 80%+).</li>
                        <li><strong>Formato:</strong> HTML em <code>storage/coverage/</code> ou text output no terminal.</li>
                        <li><strong>Arquivo de cache:</strong> <code>.phpunit.result.cache</code> para execução incremental mais rápida.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-speedometer"></i></div>
                    <h3 class="doc-subsection__title">8.3. Instruções de Teste de Carga</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Como simular picos de acesso para testar os limites da infraestrutura.</p>
                    <ul>
                        <li><strong>Ferramenta recomendada:</strong> k6 (Grafana) ou Apache JMeter para simulação de carga.</li>
                        <li><strong>Cenários a testar:</strong> Listagem de atrativos (GET), busca com filtros, interação com IA (POST), mapa com heatmap.</li>
                        <li><strong>Métricas-alvo:</strong> Tempo de resposta &lt; 500ms para P95, suporte a 100+ requisições concorrentes.</li>
                        <li><strong>Ambiente:</strong> Executar em ambiente de staging — nunca em produção.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════
             SEÇÃO 9 — Segurança e Conformidade
             ═══════════════════════════════════════════════════════ -->
        <section class="doc-section doc-reveal" id="sec9">
            <div class="doc-section__header">
                <span class="doc-section__number">09</span>
                <h2 class="doc-section__title">Segurança e Conformidade</h2>
            </div>
            <p class="doc-section__desc">Proteção jurídica e técnica da aplicação.</p>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-bug"></i></div>
                    <h3 class="doc-subsection__title">9.1. Análise de Vulnerabilidades</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Últimos relatórios de varredura de segurança em dependências e containers.</p>
                    <ul>
                        <li><strong>PHP (Composer):</strong> <code>composer audit</code> — verificar vulnerabilidades conhecidas em pacotes.</li>
                        <li><strong>Node (npm):</strong> <code>npm audit</code> — verificar CVEs em dependências JavaScript.</li>
                        <li><strong>Docker Images:</strong> Recomendado usar <code>docker scout</code> ou Trivy para varredura de imagens.</li>
                        <li><strong>OWASP Top 10:</strong> Laravel provê proteção nativa contra CSRF, XSS (Blade escaping), SQL Injection (Eloquent ORM). Verificar headers de segurança (CSP, X-Frame-Options).</li>
                        <li><strong>Periodicidade:</strong> Audit de dependências a cada deploy; varredura de imagens semanalmente.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-people-fill"></i></div>
                    <h3 class="doc-subsection__title">9.2. Matriz de Permissões</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Mapeamento de perfis de usuário (RBAC) e o que cada nível tem permissão para acessar.</p>
                    <ul>
                        <li><strong><code>super_admin</code>:</strong> Acesso total — dashboard, CRUD de atrativos/eventos/roteiros, alertas, auditoria, validação de prestadores, relatórios, documentação.</li>
                        <li><strong><code>prefeito</code>:</strong> Acesso a dashboard, relatórios, alertas e validação de prestadores.</li>
                        <li><strong><code>secretario</code>:</strong> Acesso a dashboard, gestão completa e validação de prestadores.</li>
                        <li><strong><code>gestor_conteudo</code>:</strong> CRUD de atrativos, eventos e roteiros.</li>
                        <li><strong><code>gestor_cadastros</code>:</strong> Validação de prestadores e gerenciamento de cadastros.</li>
                        <li><strong><code>empreendedor</code>:</strong> Acesso ao painel do parceiro — cadastro e gerenciamento de seus próprios atrativos/negócios.</li>
                        <li><strong>Middleware:</strong> <code>role:super_admin,prefeito,secretario,...</code> aplicado nas rotas do <code>web.php</code>.</li>
                    </ul>
                </div>
            </div>

            <div class="doc-subsection doc-reveal">
                <div class="doc-subsection__header">
                    <div class="doc-subsection__icon"><i class="bi bi-shield-check"></i></div>
                    <h3 class="doc-subsection__title">9.3. Conformidade de Dados (LGPD/GDPR)</h3>
                </div>
                <div class="doc-subsection__body">
                    <p>Mapeamento de onde dados sensíveis e PII são armazenados e como são tratados.</p>
                    <ul>
                        <li><strong>Dados Pessoais Coletados:</strong> Nome, e-mail, senha (hash bcrypt), telefone (se informado), coordenadas de acesso.</li>
                        <li><strong>Armazenamento:</strong> Tabela <code>users</code> e <code>prestadores</code> no PostgreSQL — dados em repouso sem criptografia adicional (recomendado implementar encryption-at-rest).</li>
                        <li><strong>Senhas:</strong> Hash bcrypt com cost 12 (padrão Laravel) — nunca armazenadas em texto plano.</li>
                        <li><strong>Direito de Exclusão:</strong> Funcionalidade de deleção de conta disponível via <code>/profile</code> (ProfileController@destroy).</li>
                        <li><strong>Política de Privacidade:</strong> Página pública em <code>/privacidade</code> — revisar para adequação à LGPD.</li>
                        <li><strong>Cookies:</strong> Session cookies (necessários) — verificar necessidade de banner de consentimento para analytics.</li>
                        <li><strong>Logs de Analytics:</strong> <code>analytic_events</code> podem conter IP e user-agent — considerar anonimização após período de retenção.</li>
                    </ul>
                </div>
            </div>
        </section>

    </div><!-- /.doc-content -->

    <!-- Back to Top Button -->
    <button class="doc-back-to-top" id="backToTop" aria-label="Voltar ao topo">
        <i class="bi bi-arrow-up"></i>
    </button>
</div><!-- /.doc-page-wrapper -->
@endsection

@push('scripts')
<!-- GSAP Core + Plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ─── Respect Reduced Motion ───
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    // Find scrollable container (admin layout main content area)
    const scroller = document.querySelector('.doc-page-wrapper')?.closest('.d-flex.flex-column.flex-grow-1');

    if (prefersReducedMotion) {
        // Show everything immediately without animation
        initNavigation();
        initBackToTop();
        initProgressBars();
        document.querySelectorAll('[data-counter]').forEach(el => {
            el.textContent = el.dataset.counter;
        });
        return;
    }

    // ─── Set initial hidden state via GSAP (progressive enhancement) ───
    // If GSAP loaded, hide elements first then animate them in.
    // If GSAP never loaded, elements stay visible (CSS has no opacity:0).
    gsap.set('.doc-hero__badge, .doc-hero__title, .doc-hero__subtitle, .doc-hero__meta', {
        opacity: 0, y: 20
    });
    gsap.set('.doc-stat', { opacity: 0, y: 20, scale: 0.9 });
    gsap.set('.doc-toc__card', { opacity: 0, y: 30 });
    gsap.set('.doc-toc__title, .doc-toc__subtitle', { opacity: 0, y: 20 });

    // ─── Hero Animations ───
    const heroTimeline = gsap.timeline({ delay: 0.2 });

    heroTimeline
        .to('.doc-hero__badge', {
            opacity: 1,
            y: 0,
            duration: 0.6,
            ease: 'power3.out'
        })
        .to('.doc-hero__title', {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power3.out'
        }, '-=0.3')
        .to('.doc-hero__subtitle', {
            opacity: 1,
            y: 0,
            duration: 0.6,
            ease: 'power3.out'
        }, '-=0.4')
        .to('.doc-hero__meta', {
            opacity: 1,
            y: 0,
            duration: 0.5,
            ease: 'power3.out'
        }, '-=0.3')
        .to('.doc-stat', {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.5,
            stagger: 0.1,
            ease: 'back.out(1.7)'
        }, '-=0.2');

    // ─── Counter Animation ───
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.dataset.counter);
        gsap.to(el, {
            textContent: target,
            duration: 1.5,
            delay: 0.8,
            ease: 'power2.out',
            snap: { textContent: 1 },
            scrollTrigger: {
                trigger: el,
                start: 'top 90%',
                once: true
            }
        });
    });

    // ─── TOC Title Animation ───
    gsap.to('.doc-toc__title, .doc-toc__subtitle', {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.1,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '#docToc',
            start: 'top 90%',
            once: true
        }
    });

    // ─── TOC Cards Stagger Animation ───
    gsap.to('.doc-toc__card', {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.06,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.doc-toc__grid',
            start: 'top 85%',
            once: true
        }
    });

    // ─── Section Reveal Animations ───
    document.querySelectorAll('.doc-section').forEach(section => {
        const header = section.querySelector('.doc-section__header');
        const desc = section.querySelector('.doc-section__desc');
        const subsections = section.querySelectorAll('.doc-subsection');

        // Set initial states via GSAP
        if (header) gsap.set(header, { opacity: 0, x: -30 });
        if (desc) gsap.set(desc, { opacity: 0, y: 15 });
        if (subsections.length) gsap.set(subsections, { opacity: 0, y: 25 });

        // Section header
        if (header) {
            gsap.to(header, {
                opacity: 1,
                x: 0,
                duration: 0.6,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    once: true
                }
            });
        }

        // Section description
        if (desc) {
            gsap.to(desc, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                delay: 0.15,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    once: true
                }
            });
        }

        // Subsection cards staggered
        if (subsections.length) {
            gsap.to(subsections, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    once: true
                }
            });
        }
    });

    // ─── Parallax on Background Orbs ───
    gsap.to('.doc-orb--1', {
        y: -80,
        ease: 'none',
        scrollTrigger: {
            trigger: '.doc-page-wrapper',
            start: 'top top',
            end: 'bottom top',
            scrub: 1
        }
    });
    gsap.to('.doc-orb--2', {
        y: -120,
        ease: 'none',
        scrollTrigger: {
            trigger: '.doc-page-wrapper',
            start: 'top top',
            end: 'bottom top',
            scrub: 1.5
        }
    });

    // ─── Progress Bars Animation ───
    initProgressBars();

    // ─── Navigation ───
    initNavigation();

    // ─── Back to Top ───
    initBackToTop();

    // ─── Hover Effects on Cards ───
    document.querySelectorAll('.doc-toc__card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                scale: 1.02,
                duration: 0.25,
                ease: 'power2.out'
            });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                scale: 1,
                duration: 0.25,
                ease: 'power2.out'
            });
        });
    });

    // ─── Functions ───
    function initProgressBars() {
        document.querySelectorAll('.doc-toc__progress-bar').forEach(bar => {
            const width = bar.dataset.width || 50;
            if (prefersReducedMotion) {
                bar.style.width = Math.min(width, 100) + '%';
            } else {
                gsap.to(bar, {
                    width: Math.min(width, 100) + '%',
                    duration: 1.2,
                    delay: 0.3,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: bar,
                        start: 'top 95%',
                        once: true
                    }
                });
            }
        });
    }

    function initNavigation() {
        document.querySelectorAll('[data-toc-link]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('href');
                const targetEl = document.querySelector(target);
                if (targetEl) {
                    gsap.to(window, {
                        scrollTo: {
                            y: targetEl,
                            offsetY: 80
                        },
                        duration: prefersReducedMotion ? 0 : 0.8,
                        ease: 'power2.inOut'
                    });
                }
            });
        });
    }

    function initBackToTop() {
        const btn = document.getElementById('backToTop');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 600) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        }, { passive: true });

        btn.addEventListener('click', () => {
            gsap.to(window, {
                scrollTo: { y: 0 },
                duration: prefersReducedMotion ? 0 : 1,
                ease: 'power2.inOut'
            });
        });
    }
});
</script>
@endpush
