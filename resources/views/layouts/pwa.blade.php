<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#005f73">
    <title>Turismo Inteligente</title>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Turismo PWA">
    <meta name="application-name" content="Turismo PWA">
    
    <!-- PWA Manifest & Icons -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800|work-sans:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')

    <!-- Vite JS only (disabled CSS to avoid Tailwind conflict) -->
    @vite(['resources/js/app.js'])

    
    <style>
        :root {
            --bs-primary: #005f73;
            --bs-secondary: #0a9396;
            --bs-font-sans-serif: 'Work Sans', sans-serif;
            --bs-heading-font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body { font-family: var(--bs-font-sans-serif); -webkit-tap-highlight-color: transparent; }
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: var(--bs-heading-font-family); }
        .safe-area-pt { padding-top: env(safe-area-inset-top); }
        .safe-area-pb { padding-bottom: calc(env(safe-area-inset-bottom) + 5rem); }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Glassmorphism for Top/Bottom Nav */
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        [data-bs-theme="dark"] .glass-nav {
            background: rgba(33, 37, 41, 0.85);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Bottom Nav Item styling */
        .bottom-nav-item {
            min-width: 44px;
            min-height: 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--bs-secondary-color);
            text-decoration: none;
            font-size: 0.75rem;
            transition: color 0.2s ease-in-out;
            padding: 0.5rem 0;
            flex: 1;
        }
        .bottom-nav-item i { font-size: 1.25rem; margin-bottom: 2px; }
        .bottom-nav-item.active { color: var(--bs-primary); font-weight: 600; }
        
        .floating-action-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 95, 115, 0.4);
            transform: translateY(-20px);
            border: 4px solid var(--bs-body-bg);
            transition: transform 0.2s;
        }
        .floating-action-button:hover {
            color: white;
            transform: translateY(-22px) scale(1.05);
        }
        .flex-col { flex-direction: column; }
    </style>
</head>
<body class="bg-light text-dark vh-100 overflow-hidden d-flex flex-col">
    
    <!-- Topbar -->
    <header class="glass-nav fixed-top w-100 z-3 safe-area-pt">
        <div class="container-fluid px-3 py-2 d-flex justify-content-between align-items-center" style="min-height: 56px;">
            <!-- Dropdown / Toggle de Seleção de Cidade -->
            <div class="dropdown">
                <button type="button" class="btn p-0 border-0 bg-transparent navbar-brand fw-bold text-primary d-flex align-items-center gap-2 m-0 fs-5 text-start shadow-none" id="cityDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false" title="Trocar cidade ou localização">
                    <i class="bi bi-geo-alt-fill text-primary" id="location-pin-icon"></i>
                    <span id="current-location-display">João Pessoa PB</span>
                    <span id="location-spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status" style="width: 0.85rem; height: 0.85rem;"></span>
                    <i class="bi bi-chevron-down text-muted" style="font-size: 0.75rem;"></i>
                </button>

                <div class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="cityDropdownToggle" style="min-width: 290px; z-index: 1060; border: 1px solid rgba(0,0,0,0.08) !important;">
                    <div class="d-flex justify-content-between align-items-center px-3 pt-2 pb-2 border-bottom">
                        <span class="text-uppercase fw-bold text-muted small" style="font-size: 0.68rem; letter-spacing: 0.5px;">Escolher Destino</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Mapa Integrado</span>
                    </div>
                    
                    <div class="py-1" id="dropdown-cities-list">
                        <!-- João Pessoa -->
                        <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center justify-content-between btn-select-city" data-city="João Pessoa" data-uf="PB" data-lat="-7.1153" data-lng="-34.8641">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-primary d-inline-block" style="width: 8px; height: 8px;"></span>
                                <div>
                                    <div class="fw-bold text-dark small">João Pessoa</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Paraíba • 6 atrações</div>
                                </div>
                            </div>
                            <i class="bi bi-check2 text-primary fs-5 active-check"></i>
                        </button>
                        
                        <!-- Bonito -->
                        <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center justify-content-between btn-select-city" data-city="Bonito" data-uf="MS" data-lat="-21.1275" data-lng="-56.4831">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-success d-inline-block" style="width: 8px; height: 8px;"></span>
                                <div>
                                    <div class="fw-bold text-dark small">Bonito</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Mato Grosso do Sul • 4 atrações</div>
                                </div>
                            </div>
                            <i class="bi bi-check2 text-primary fs-5 d-none active-check"></i>
                        </button>
                        
                        <!-- Recife -->
                        <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center justify-content-between btn-select-city" data-city="Recife" data-uf="PE" data-lat="-8.0476" data-lng="-34.8770">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-warning d-inline-block" style="width: 8px; height: 8px;"></span>
                                <div>
                                    <div class="fw-bold text-dark small">Recife</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Pernambuco • 3 atrações</div>
                                </div>
                            </div>
                            <i class="bi bi-check2 text-primary fs-5 d-none active-check"></i>
                        </button>

                        <!-- Natal -->
                        <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center justify-content-between btn-select-city" data-city="Natal" data-uf="RN" data-lat="-5.7945" data-lng="-35.2110">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-info d-inline-block" style="width: 8px; height: 8px;"></span>
                                <div>
                                    <div class="fw-bold text-dark small">Natal</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Rio Grande do Norte • 3 atrações</div>
                                </div>
                            </div>
                            <i class="bi bi-check2 text-primary fs-5 d-none active-check"></i>
                        </button>

                        <!-- São Paulo -->
                        <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center justify-content-between btn-select-city" data-city="São Paulo" data-uf="SP" data-lat="-23.5505" data-lng="-46.6333">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-danger d-inline-block" style="width: 8px; height: 8px;"></span>
                                <div>
                                    <div class="fw-bold text-dark small">São Paulo</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">São Paulo • 3 atrações</div>
                                </div>
                            </div>
                            <i class="bi bi-check2 text-primary fs-5 d-none active-check"></i>
                        </button>
                    </div>

                    <div class="dropdown-divider my-2"></div>

                    <!-- Botão GPS no dropdown -->
                    <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-primary fw-bold" id="btn-dropdown-gps">
                        <i class="bi bi-crosshair fs-6"></i>
                        <span class="small">Detectar Meu GPS Real</span>
                    </button>

                    <!-- Botão Buscar com OpenStreetMap no modal -->
                    <button type="button" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-secondary" data-bs-toggle="modal" data-bs-target="#locationModal">
                        <i class="bi bi-search fs-6"></i>
                        <span class="small">Buscar outra cidade...</span>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button id="btn-header-install" type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold d-none d-flex align-items-center gap-1 shadow-sm btn-trigger-pwa-install" title="Instalar Aplicativo" style="font-size: 0.8rem; min-height: 36px;">
                    <i class="bi bi-download"></i>
                    <span>Instalar</span>
                </button>
                <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 38px; height: 38px; border: none; background: rgba(0,0,0,0.05);" data-bs-toggle="modal" data-bs-target="#locationModal" title="Ver localização e busca avançada">
                    <i class="bi bi-compass fs-5 text-primary"></i>
                </button>

                <!-- Profile / Access Component -->
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow-sm text-decoration-none" id="btn-header-login" style="font-size: 0.82rem; min-height: 38px;">
                        <i class="bi bi-box-arrow-in-right fs-6"></i>
                        <span>Entrar</span>
                    </a>
                @endguest

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill p-1 ps-2 pe-3 d-flex align-items-center gap-2 shadow-sm border dropdown-toggle" type="button" id="authUserDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="min-height: 38px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 28px; height: 28px; font-size: 0.75rem; background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="text-start d-none d-sm-block" style="line-height: 1.1;">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 100px; font-size: 0.78rem;">{{ explode(' ', auth()->user()->name)[0] }}</div>
                                <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">
                                    @if(auth()->user()->role === 'super_admin') Admin
                                    @elseif(str_starts_with(auth()->user()->role, 'gestor') || in_array(auth()->user()->role, ['secretario', 'prefeito'])) Gestor
                                    @elseif(auth()->user()->role === 'empreendedor') Parceiro
                                    @else Turista @endif
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="authUserDropdown" style="min-width: 260px; border: 1px solid rgba(0,0,0,0.08) !important;">
                            <li class="px-3 pt-2 pb-2 border-bottom">
                                <div class="fw-bold text-dark small text-truncate">{{ auth()->user()->name }}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.72rem;">{{ auth()->user()->email }}</div>
                                <div class="mt-1">
                                    @if(auth()->user()->role === 'super_admin')
                                        <span class="badge bg-primary rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Super Administrador</span>
                                    @elseif(str_starts_with(auth()->user()->role, 'gestor') || in_array(auth()->user()->role, ['secretario', 'prefeito']))
                                        <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Gestor Municipal</span>
                                    @elseif(auth()->user()->role === 'empreendedor')
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Empreendedor Credenciado</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info-emphasis border rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Turista Oficial</span>
                                    @endif
                                </div>
                            </li>

                            @if(in_array(auth()->user()->role, ['super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 'gestor_cadastros', 'atendente']))
                                <li class="pt-2">
                                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 bg-primary text-white fw-bold shadow-sm" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 fs-5"></i>
                                        <div>
                                            <div class="small">Painel de Gestão</div>
                                            <div class="text-white-50" style="font-size: 0.68rem;">Administração do Município</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-dark mt-1" href="{{ route('admin.prestadores.index') }}">
                                        <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                        <span class="small fw-semibold">Fila de Homologação</span>
                                    </a>
                                </li>
                            @elseif(auth()->user()->role === 'empreendedor')
                                <li class="pt-2">
                                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 bg-warning-subtle text-dark fw-bold border border-warning" href="{{ route('empreendedor.dashboard') }}">
                                        <i class="bi bi-shop fs-5 text-warning-emphasis"></i>
                                        <div>
                                            <div class="small">Painel do Parceiro</div>
                                            <div class="text-muted" style="font-size: 0.68rem;">Gerenciar Estabelecimento</div>
                                        </div>
                                    </a>
                                </li>
                            @else
                                <li class="pt-2">
                                    <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-primary fw-semibold" href="{{ route('pwa.ia') }}">
                                        <i class="bi bi-robot fs-5"></i>
                                        <div>
                                            <div class="small">Assistente de Viagem IA</div>
                                            <div class="text-muted" style="font-size: 0.68rem;">Acesso liberado</div>
                                        </div>
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2 text-danger fw-semibold">
                                        <i class="bi bi-box-arrow-right fs-5"></i>
                                        <span class="small">Sair da Conta (Logout)</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Modal de Seleção de Localização (OpenStreetMap) -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold m-0" id="locationModalLabel">Sua Localização</h5>
                            <span class="text-secondary small">Alimentado por OpenStreetMap</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Localização Atual Ativa -->
                    <div class="p-3 rounded-4 mb-3 border d-flex justify-content-between align-items-center bg-light">
                        <div>
                            <div class="text-muted fw-semibold small" style="font-size: 0.7rem; text-transform: uppercase;">Cidade Atual</div>
                            <div class="fw-bold text-dark fs-6" id="modal-current-location-name">Carregando...</div>
                            <div class="text-muted small" id="modal-current-coords" style="font-size: 0.75rem;"></div>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold">Ativo</span>
                    </div>

                    <!-- Botão GPS em tempo real -->
                    <button type="button" id="btn-modal-detect-gps" class="btn btn-primary w-100 rounded-4 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-4 shadow-sm">
                        <i class="bi bi-crosshair fs-5"></i>
                        <span>Detectar Meu GPS Real</span>
                    </button>

                    <!-- Busca com OpenStreetMap -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Buscar outra cidade</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="bi bi-search text-secondary"></i>
                            </span>
                            <input type="text" id="osm-city-search-input" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Ex: João Pessoa, Recife, Bonito..." autocomplete="off">
                            <button class="btn btn-outline-secondary border-0 bg-light" type="button" id="osm-search-btn">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <!-- Lista de sugestões da busca OSM -->
                        <div id="osm-search-results" class="list-group mt-2 border-0 shadow-sm rounded-3 d-none" style="max-height: 180px; overflow-y: auto;"></div>
                    </div>

                    <!-- Destinos Populares / Atalhos rápidos -->
                    <div>
                        <div class="text-muted fw-semibold small mb-2" style="font-size: 0.75rem; text-transform: uppercase;">Cidades em Destaque</div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-semibold btn-quick-location" data-city="João Pessoa" data-uf="PB" data-lat="-7.1153" data-lng="-34.8641">
                                📍 João Pessoa - PB
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold btn-quick-location" data-city="Bonito" data-uf="MS" data-lat="-21.1275" data-lng="-56.4831">
                                Bonito - MS
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold btn-quick-location" data-city="Recife" data-uf="PE" data-lat="-8.0476" data-lng="-34.8770">
                                Recife - PE
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold btn-quick-location" data-city="Natal" data-uf="RN" data-lat="-5.7945" data-lng="-35.2110">
                                Natal - RN
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold btn-quick-location" data-city="São Paulo" data-uf="SP" data-lat="-23.5505" data-lng="-46.6333">
                                São Paulo - SP
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating PWA Install Prompt Banner -->
    <div id="pwa-install-banner" class="position-fixed start-50 translate-middle-x w-100 px-3 z-3 d-none" style="bottom: 76px; max-width: 460px; z-index: 1050;">
        <div class="card border-0 rounded-4 shadow-lg p-3 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #005f73, #0a9396); border: 1px solid rgba(255,255,255,0.2) !important; backdrop-filter: blur(10px);">
            <button type="button" id="btn-dismiss-install-banner" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none" style="font-size: 0.75rem;" aria-label="Dispensar"></button>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-4 bg-white p-2 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                    <img src="/icons/icon-192x192.png" alt="App Icon" style="width: 34px; height: 34px;" class="rounded-3">
                </div>
                <div class="flex-grow-1 pe-4">
                    <div class="fw-bold fs-6 mb-0 text-white">Instalar Turismo App</div>
                    <div class="text-white-50 small" style="font-size: 0.75rem;">Acesso rápido com mapas e roteiros offline</div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 pt-2 border-top border-white border-opacity-15">
                <button type="button" class="btn btn-light text-primary fw-bold rounded-pill flex-grow-1 btn-trigger-pwa-install py-2 shadow-sm" id="btn-install-banner" style="font-size: 0.9rem;">
                    <i class="bi bi-download me-1"></i> Instalar Aplicativo
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Instruções iOS Safari -->
    <div class="modal fade" id="pwaIosInstallModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Instalar no iPhone / iPad</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <div class="rounded-circle d-inline-flex p-3 bg-light text-primary mb-3">
                        <i class="bi bi-apple fs-1"></i>
                    </div>
                    <p class="text-secondary small mb-4">Siga estes passos no Safari para ter o app na tela inicial:</p>
                    <div class="text-start bg-light rounded-3 p-3 small d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle">1</span>
                            <span>Toque no botão <strong>Compartilhar</strong> <i class="bi bi-box-arrow-up text-primary fs-6"></i> no Safari.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle">2</span>
                            <span>Role para baixo e selecione <strong>"Adicionar à Tela de Início"</strong> <i class="bi bi-plus-square text-primary fs-6"></i>.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle">3</span>
                            <span>Toque em <strong>Adicionar</strong> no topo direito.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary w-100 rounded-pill py-2 fw-bold" data-bs-dismiss="modal">Entendi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Instalação Manual (Desktop / Outros Navegadores) -->
    <div class="modal fade" id="pwaManualInstallModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Instalar o Aplicativo</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <div class="rounded-4 bg-light d-inline-flex p-3 text-primary mb-3">
                        <i class="bi bi-phone fs-1"></i>
                    </div>
                    <p class="text-secondary small mb-3">Instale diretamente pelo menu do seu navegador:</p>
                    <div class="text-start bg-light rounded-3 p-3 small d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-three-dots-vertical text-primary fs-5"></i>
                            <span>Clique no menu do navegador (três pontinhos no topo).</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-download text-primary fs-5"></i>
                            <span>Selecione <strong>"Instalar Aplicativo"</strong> ou <strong>"Adicionar à tela inicial"</strong>.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary w-100 rounded-pill py-2 fw-bold" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow-1 overflow-auto no-scrollbar pt-5 pb-5 mt-3 safe-area-pb">
        @yield('content')
    </main>

    <!-- Bottom Navigation Bar -->
    <nav class="glass-nav fixed-bottom w-100 z-3 pb-safe border-top">
        <div class="d-flex justify-content-around align-items-center" style="height: 64px;">
            <a href="{{ route('pwa.home') }}" class="bottom-nav-item {{ request()->routeIs('pwa.home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Início</span>
            </a>
            
            <a href="{{ route('pwa.explorar') }}" class="bottom-nav-item {{ request()->routeIs('pwa.explorar') ? 'active' : '' }}">
                <i class="bi bi-compass-fill"></i>
                <span>Explorar</span>
            </a>

            <a href="{{ route('pwa.ia') }}" class="bottom-nav-item position-relative text-decoration-none">
                <div class="floating-action-button">
                    <i class="bi bi-stars"></i>
                </div>
                <span class="position-absolute bottom-0 mb-1 {{ request()->routeIs('pwa.ia') ? 'text-primary fw-bold' : 'text-secondary' }}" style="font-size: 0.75rem;">Assistente</span>
            </a>

            <a href="{{ route('pwa.roteiros') }}" class="bottom-nav-item {{ request()->routeIs('pwa.roteiros') ? 'active' : '' }}">
                <i class="bi bi-map-fill"></i>
                <span>Roteiros</span>
            </a>

            <a href="{{ route('pwa.utilidade') }}" class="bottom-nav-item {{ request()->routeIs('pwa.utilidade') ? 'active' : '' }}">
                <i class="bi bi-shield-fill-check"></i>
                <span>Útil</span>
            </a>
        </div>
    </nav>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <!-- Location Modal & Controller Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detectGpsBtn = document.getElementById('btn-modal-detect-gps');
            const searchInput = document.getElementById('osm-city-search-input');
            const searchBtn = document.getElementById('osm-search-btn');
            const searchResults = document.getElementById('osm-search-results');
            const quickButtons = document.querySelectorAll('.btn-quick-location');
            const locationModalEl = document.getElementById('locationModal');

            function getModalInstance() {
                if (typeof bootstrap !== 'undefined' && locationModalEl) {
                    return bootstrap.Modal.getInstance(locationModalEl) || new bootstrap.Modal(locationModalEl);
                }
                return null;
            }

            // GPS Click Handler
            if (detectGpsBtn) {
                detectGpsBtn.addEventListener('click', function() {
                    const originalHtml = detectGpsBtn.innerHTML;
                    detectGpsBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Detectando no OpenStreetMap...';
                    detectGpsBtn.disabled = true;

                    if (window.LocationService) {
                        window.LocationService.detectGPS({
                            showLoading: true,
                            onSuccess: function(data) {
                                detectGpsBtn.innerHTML = '<i class="bi bi-check2-circle fs-5"></i> Localização Atualizada!';
                                setTimeout(() => {
                                    detectGpsBtn.innerHTML = originalHtml;
                                    detectGpsBtn.disabled = false;
                                    const modal = getModalInstance();
                                    if (modal) modal.hide();
                                }, 800);
                            },
                            onError: function(err) {
                                detectGpsBtn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> ' + (err.message || 'Erro ao obter GPS');
                                setTimeout(() => {
                                    detectGpsBtn.innerHTML = originalHtml;
                                    detectGpsBtn.disabled = false;
                                }, 3000);
                            }
                        });
                    }
                });
            }

            // Select city buttons (no dropdown e no modal)
            document.querySelectorAll('.btn-select-city, .btn-quick-location').forEach(btn => {
                btn.addEventListener('click', function() {
                    const city = this.getAttribute('data-city');
                    const uf = this.getAttribute('data-uf');
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));

                    if (window.LocationService) {
                        window.LocationService.setLocationManual(city, uf, lat, lng);
                    }
                    
                    const modal = getModalInstance();
                    if (modal) modal.hide();
                });
            });

            // Dropdown GPS Button
            const dropdownGpsBtn = document.getElementById('btn-dropdown-gps');
            if (dropdownGpsBtn) {
                dropdownGpsBtn.addEventListener('click', function() {
                    if (window.LocationService) {
                        window.LocationService.detectGPS({ showLoading: true });
                    }
                });
            }

            // OpenStreetMap Search Handler
            let searchTimeout = null;
            async function performSearch() {
                const q = (searchInput?.value || '').trim();
                if (q.length < 2) {
                    if (searchResults) searchResults.classList.add('d-none');
                    return;
                }

                if (searchResults) {
                    searchResults.innerHTML = '<div class="p-3 text-center text-muted small"><span class="spinner-border spinner-border-sm me-2"></span>Buscando no OpenStreetMap...</div>';
                    searchResults.classList.remove('d-none');
                }

                if (window.LocationService) {
                    const results = await window.LocationService.searchCities(q);
                    if (!searchResults) return;

                    if (results && results.length > 0) {
                        searchResults.innerHTML = results.map(item => `
                            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 osm-result-item" 
                                data-city="${item.city}" data-uf="${item.uf || ''}" data-lat="${item.lat}" data-lng="${item.lng}">
                                <div>
                                    <div class="fw-bold small">${item.display}</div>
                                    <div class="text-muted text-truncate" style="font-size: 0.68rem; max-width: 260px;">${item.display_name || ''}</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </button>
                        `).join('');

                        searchResults.querySelectorAll('.osm-result-item').forEach(itemBtn => {
                            itemBtn.addEventListener('click', function() {
                                const city = this.getAttribute('data-city');
                                const uf = this.getAttribute('data-uf');
                                const lat = parseFloat(this.getAttribute('data-lat'));
                                const lng = parseFloat(this.getAttribute('data-lng'));

                                if (window.LocationService) {
                                    window.LocationService.setLocationManual(city, uf, lat, lng);
                                }
                                searchResults.classList.add('d-none');
                                if (searchInput) searchInput.value = '';
                                const modal = getModalInstance();
                                if (modal) modal.hide();
                            });
                        });
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-center text-muted small">Nenhuma cidade encontrada no OpenStreetMap.</div>';
                    }
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 400);
                });
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch();
                    }
                });
            }

            if (searchBtn) {
                searchBtn.addEventListener('click', performSearch);
            }
        });
    </script>

    
    @stack('scripts')
</body>
</html>
