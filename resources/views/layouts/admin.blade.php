<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('title', 'Painel de Gestão') - Destino Inteligente</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800|work-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    @stack('styles')

    <style>
        :root {
            --bs-primary: #005f73;
            --bs-secondary: #0a9396;
            --bs-font-sans-serif: 'Work Sans', sans-serif;
            --bs-heading-font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: var(--bs-font-sans-serif);
            background-color: #f4f6f8;
            color: #2b2d42;
        }
        h1, h2, h3, h4, h5, h6, .brand-title {
            font-family: var(--bs-heading-font-family);
        }
        .admin-sidebar {
            width: 260px;
            background: #003844;
            color: #ffffff;
            min-height: 100vh;
            transition: all 0.3s ease;
            z-index: 1040;
        }
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            margin: 0.2rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }
        .admin-sidebar .nav-link i {
            font-size: 1.15rem;
        }
        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }
        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: var(--bs-secondary);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(10, 147, 150, 0.3);
        }
        .admin-sidebar .sidebar-heading {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255, 255, 255, 0.4);
            padding: 0.75rem 1.25rem 0.25rem;
            font-weight: 700;
        }
        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            height: 64px;
        }
        .card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover {
            background-color: #004b5c;
            border-color: #004b5c;
        }
        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: -260px;
            }
            .admin-sidebar.show {
                left: 0;
            }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1030;
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>
<body class="d-flex">
    <!-- Backdrop for mobile sidebar -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar d-flex flex-column flex-shrink-0" id="adminSidebar">
        <!-- Logo -->
        <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-white border-opacity-10" style="height: 64px;">
            <a href="{{ url('/admin') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-shield-check fs-5 text-warning"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-6 brand-title lh-1">Destino Inteligente</span>
                    <span class="text-white-50 small" style="font-size: 0.68rem;">Painel de Gestão</span>
                </div>
            </a>
            <button type="button" class="btn btn-sm text-white d-lg-none p-0" id="btnToggleSidebarClose">
                <i class="bi bi-x fs-4"></i>
            </button>
        </div>

        <!-- Links de Navegação -->
        <div class="flex-grow-1 overflow-auto py-2">
            <div class="sidebar-heading">Visão Geral</div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="{{ url('/admin') }}" class="nav-link {{ request()->is('admin') || request()->is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard & KPIs</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading mt-2">Gestão Turística</div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.atrativos.index') }}" class="nav-link {{ request()->is('admin/atrativos*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt-fill text-info"></i>
                        <span>Atrativos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.eventos.index') }}" class="nav-link {{ request()->is('admin/eventos*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event text-warning"></i>
                        <span>Eventos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.roteiros.index') }}" class="nav-link {{ request()->is('admin/roteiros*') ? 'active' : '' }}">
                        <i class="bi bi-map-fill text-success"></i>
                        <span>Roteiros</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/prestadores') }}" class="nav-link {{ request()->is('admin/prestadores*') ? 'active' : '' }}">
                        <i class="bi bi-shop text-primary-subtle"></i>
                        <span>Validação Parceiros</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading mt-2">Segurança & Operação</div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.alertas.index') }}" class="nav-link {{ request()->is('admin/alertas*') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                        <span>Alertas & Defesa Civil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.auditoria.index') }}" class="nav-link {{ request()->is('admin/auditoria*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Auditoria & Logs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.relatorios.export') }}" class="nav-link">
                        <i class="bi bi-file-earmark-arrow-down text-light"></i>
                        <span>Exportar Relatórios</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Rodapé do Sidebar -->
        <div class="p-3 border-top border-white border-opacity-10 d-flex flex-column gap-2">
            <a href="{{ route('pwa.home') }}" class="btn btn-outline-light btn-sm w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" target="_blank">
                <i class="bi bi-phone"></i>
                <span>Ver App do Turista</span>
            </a>
            <a href="{{ route('admin.documentacao') }}" class="btn btn-outline-info btn-sm w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 {{ request()->is('admin/documentacao*') ? 'active bg-info text-white border-info' : '' }}">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Documentação</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-100 m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill py-1.5 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sair do Painel</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="d-flex flex-column flex-grow-1 w-100 min-vh-100">
        <!-- Topbar -->
        <header class="admin-topbar d-flex align-items-center justify-content-between px-3 px-lg-4 sticky-top">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-light d-lg-none rounded-circle p-2" id="btnToggleSidebarOpen">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark d-none d-sm-block">@yield('title', 'Painel de Gestão')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Status da Cidade -->
                <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2 fw-semibold d-none d-md-inline-flex align-items-center gap-1">
                    <span class="rounded-circle bg-success d-inline-block" style="width: 6px; height: 6px;"></span>
                    Sistema Online
                </span>

                <!-- Dropdown do Usuário -->
                <div class="dropdown">
                    <button class="btn btn-light border-0 rounded-pill px-3 py-1.5 d-flex align-items-center gap-2 bg-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold small" style="width: 32px; height: 32px;">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="d-none d-sm-flex flex-column text-start">
                            <span class="fw-bold small text-dark lh-1">{{ auth()->user()?->name ?? 'Gestor Público' }}</span>
                            <span class="text-muted" style="font-size: 0.68rem;">{{ auth()->user()?->role ?? 'Administrador' }}</span>
                        </div>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                        <li>
                            <div class="px-3 py-2 border-bottom">
                                <div class="fw-bold small">{{ auth()->user()?->name ?? 'Gestor' }}</div>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ auth()->user()?->email ?? 'gestor@demo.com' }}</div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 small" href="{{ route('pwa.home') }}">
                                <i class="bi bi-phone text-primary"></i> App do Turista
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger small w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i> Sair do Painel
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow-1 p-3 p-lg-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-muted small d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span>Destino Inteligente &copy; {{ date('Y') }} — Plataforma de Gestão e Inteligência Turística Municipal</span>
            <a href="{{ route('admin.documentacao') }}" class="text-decoration-none text-primary fw-semibold d-inline-flex align-items-center gap-1">
                <i class="bi bi-journal-bookmark-fill"></i> Documentação do Projeto
            </a>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const btnOpen = document.getElementById('btnToggleSidebarOpen');
            const btnClose = document.getElementById('btnToggleSidebarClose');

            function toggleSidebar(open) {
                if (open) {
                    sidebar.classList.add('show');
                    backdrop.classList.add('show');
                } else {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                }
            }

            btnOpen?.addEventListener('click', () => toggleSidebar(true));
            btnClose?.addEventListener('click', () => toggleSidebar(false));
            backdrop?.addEventListener('click', () => toggleSidebar(false));
        });
    </script>

    <!-- Geo Autocomplete Handler for Admin Modals -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('input', function(e) {
                if (!e.target.classList.contains('geo-search-input')) return;

                const input = e.target;
                const wrapper = input.closest('.geo-autocomplete-wrapper');
                const dropdown = wrapper?.querySelector('.geo-results-dropdown');
                const form = input.closest('form');
                if (!wrapper || !dropdown || !form) return;

                clearTimeout(input._debounceTimer);
                const query = input.value.trim();

                if (query.length < 3) {
                    dropdown.classList.add('d-none');
                    dropdown.innerHTML = '';
                    return;
                }

                input._debounceTimer = setTimeout(async () => {
                    try {
                        dropdown.innerHTML = '<div class="p-3 text-center text-muted small"><span class="spinner-border spinner-border-sm me-2"></span>Buscando no OpenStreetMap...</div>';
                        dropdown.classList.remove('d-none');

                        const res = await fetch(`/api/v1/location/search?q=${encodeURIComponent(query)}`);
                        if (!res.ok) throw new Error('Falha na busca');
                        const results = await res.json();

                        if (!Array.isArray(results) || results.length === 0) {
                            dropdown.innerHTML = '<div class="p-3 text-center text-muted small"><i class="bi bi-geo-alt me-1"></i>Nenhum local encontrado para esta busca.</div>';
                            return;
                        }

                        dropdown.innerHTML = results.map(item => `
                            <button type="button" class="list-group-item list-group-item-action p-2.5 px-3 border-0 border-bottom text-start d-flex align-items-center gap-2.5 geo-result-item" 
                                data-lat="${item.lat}" 
                                data-lng="${item.lng}" 
                                data-display="${item.display || item.city}" 
                                data-city="${item.city || ''}"
                                data-state="${item.state || ''}"
                                data-full="${item.display_name || item.display}">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark small text-truncate">${item.display}</div>
                                    <div class="text-muted text-truncate" style="font-size: 0.72rem;">${item.display_name || (item.city + ' - ' + item.state)}</div>
                                </div>
                            </button>
                        `).join('');

                        dropdown.querySelectorAll('.geo-result-item').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const lat = this.dataset.lat;
                                const lng = this.dataset.lng;
                                const full = this.dataset.full;

                                const latInput = form.querySelector('input[name="lat"]');
                                const lngInput = form.querySelector('input[name="lng"]');
                                const enderecoInput = form.querySelector('input[name="endereco"], input[name="local"]');
                                const nomeInput = form.querySelector('input[name="nome"]');

                                if (latInput) latInput.value = lat;
                                if (lngInput) lngInput.value = lng;
                                if (enderecoInput) enderecoInput.value = full;
                                if (nomeInput && !nomeInput.value) {
                                    nomeInput.value = this.dataset.display;
                                }

                                input.value = this.dataset.display;
                                dropdown.classList.add('d-none');
                            });
                        });

                    } catch (err) {
                        dropdown.innerHTML = '<div class="p-3 text-center text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>Erro ao consultar API de mapas.</div>';
                    }
                }, 350);
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.geo-autocomplete-wrapper')) {
                    document.querySelectorAll('.geo-results-dropdown').forEach(d => d.classList.add('d-none'));
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
