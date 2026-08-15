<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Restrito — Turismo Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 480px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body class="p-3">
    <div class="card error-card border-0 bg-white p-4 p-md-5 text-center">
        <div class="rounded-circle d-inline-flex p-3 mx-auto mb-3 text-danger" style="background: rgba(220, 53, 69, 0.1); width: 64px; height: 64px; align-items: center; justify-content: center;">
            <i class="bi bi-shield-slash fs-2"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2">Acesso Restrito</h3>
        <p class="text-secondary small mb-4">
            Seu perfil de usuário não possui permissão para acessar esta área administrativa ou governamental.
        </p>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('pwa.home') }}" class="btn btn-primary rounded-pill py-2.5 fw-bold">
                <i class="bi bi-compass me-1"></i> Voltar ao Portal do Turista
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill py-2 small">
                <i class="bi bi-person-circle me-1"></i> Alternar Conta de Usuário
            </a>
        </div>
    </div>
</body>
</html>
