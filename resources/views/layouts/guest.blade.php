<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Destino Inteligente') }} - Acesso Administrativo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800|work-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-primary: #005f73;
            --bs-secondary: #0a9396;
            --bs-font-sans-serif: 'Work Sans', sans-serif;
            --bs-heading-font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: var(--bs-font-sans-serif);
            background: linear-gradient(135deg, #005f73 0%, #0a9396 50%, #94d2bd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--bs-heading-font-family); }
        .auth-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
        }
        .form-control:focus {
            border-color: var(--bs-secondary);
            box-shadow: 0 0 0 0.25rem rgba(10, 147, 150, 0.25);
        }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover {
            background-color: #004b5c;
            border-color: #004b5c;
        }
    </style>
</head>
<body>
    <div class="auth-card p-4 p-sm-5 my-3">
        <!-- Logo e Cabeçalho -->
        <div class="text-center mb-4">
            <a href="/" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                    <i class="bi bi-geo-alt-fill fs-3"></i>
                </div>
            </a>
            <h4 class="fw-bold text-dark mb-1">Destino Inteligente</h4>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold small">Painel de Gestão</span>
        </div>

        {{ $slot }}

        <div class="text-center mt-4 pt-3 border-top">
            <a href="/" class="small text-decoration-none text-secondary d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Voltar ao App do Turista (PWA)
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
