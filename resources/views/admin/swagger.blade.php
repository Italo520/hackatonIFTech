<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swagger UI - Documentação Interativa da API | Destino Inteligente</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bs-primary: #005f73;
            --bs-secondary: #0a9396;
            --doc-bg: #0f172a;
        }
        body {
            margin: 0;
            padding: 0;
            background: #fafbfc;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .swagger-topbar-custom {
            background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 95, 115, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .swagger-topbar-custom .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
        }
        .swagger-topbar-custom .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .swagger-topbar-custom .brand-title {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        .swagger-topbar-custom .brand-subtitle {
            font-size: 0.75rem;
            opacity: 0.85;
        }
        .swagger-topbar-custom .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .swagger-topbar-custom .btn-nav {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            padding: 6px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .swagger-topbar-custom .btn-nav:hover {
            background: #ffffff;
            color: var(--bs-primary);
        }
        /* Ajustes no Swagger UI */
        .swagger-ui .topbar {
            display: none !important;
        }
        .swagger-ui .info {
            margin: 24px 0 20px !important;
        }
        .swagger-ui .info .title {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }
        .swagger-ui .scheme-container {
            background: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
            border-radius: 12px !important;
            padding: 16px 20px !important;
            margin: 16px 0 24px !important;
        }
        .swagger-ui .opblock {
            border-radius: 10px !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;
            margin-bottom: 12px !important;
        }
        .swagger-ui .opblock-tag {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 1.15rem !important;
            border-bottom: 2px solid #e2e8f0 !important;
            padding-bottom: 8px !important;
            margin-top: 24px !important;
        }
    </style>
</head>
<body>
    <!-- Topbar Customizada -->
    <header class="swagger-topbar-custom">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <div class="brand-logo">
                <i class="bi bi-code-square"></i>
            </div>
            <div>
                <div class="brand-title">Destino Inteligente</div>
                <div class="brand-subtitle">Swagger / OpenAPI 3.0 Explorer</div>
            </div>
        </a>
        <div class="actions">
            <a href="{{ route('admin.documentacao') }}#sec5" class="btn-nav">
                <i class="bi bi-journal-text"></i> Dossiê de Documentação
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn-nav">
                <i class="bi bi-speedometer2"></i> Dashboard Admin
            </a>
        </div>
    </header>

    <!-- Container do Swagger UI -->
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "{{ asset('docs/openapi.json') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout",
                defaultModelsExpandDepth: 1,
                defaultModelExpandDepth: 1,
                docExpansion: "list",
                filter: true,
                showExtensions: true,
                showCommonExtensions: true,
                tryItOutEnabled: true
            });
        };
    </script>
</body>
</html>
