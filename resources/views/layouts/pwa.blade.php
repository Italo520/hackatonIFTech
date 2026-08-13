<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#005f73">
    <title>{{ config('app.name', 'Bonito MS') }}</title>

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
            <a href="{{ route('pwa.home') }}" class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2 m-0 fs-5">
                <i class="bi bi-geo-alt-fill"></i> Bonito MS
            </a>
            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 44px; height: 44px; border: none; background: rgba(0,0,0,0.05);">
                <i class="bi bi-bell fs-5 text-secondary"></i>
            </button>
        </div>
    </header>

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
    
    @stack('scripts')
</body>
</html>
