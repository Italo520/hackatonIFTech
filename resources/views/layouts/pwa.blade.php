<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Destino Turístico Inteligente</title>
    <!-- Placeholder for Manifest and Service Worker -->
    <link rel="manifest" href="/manifest.json">
    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function() {
                navigator.serviceWorker.register("/sw.js").then(function(registration) {
                    console.log("ServiceWorker registration successful with scope: ", registration.scope);
                }, function(err) {
                    console.log("ServiceWorker registration failed: ", err);
                });
            });
        }
    </script>
    <!-- Bootstrap 5 CSS via CDN for quick hackathon setup -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { padding-bottom: 70px; } /* Space for bottom nav */
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: #fff; border-top: 1px solid #ddd; z-index: 1000; }
        .nav-item { text-align: center; font-size: 0.8rem; padding: 10px 0; }
        .map-container { height: calc(100vh - 126px); width: 100%; } /* Minus header and bottom nav */
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-light bg-light sticky-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Guia Turístico</span>
        </div>
    </nav>

    <div class="container mt-3">
        @yield('content')
    </div>

    <div class="bottom-nav d-flex justify-content-around">
        <a href="{{ route('home') }}" class="nav-item text-decoration-none text-dark flex-fill">Início</a>
        <a href="{{ route('mapa') }}" class="nav-item text-decoration-none text-dark flex-fill">Mapa</a>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
