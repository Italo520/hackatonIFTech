@extends('layouts.pwa')

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Header da Página -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark fs-3 mb-1" style="letter-spacing: -0.02em;">Utilidade Pública & App</h1>
        <p class="text-secondary small mt-1">Serviços essenciais, emergência e configurações do app.</p>
    </div>

    <!-- Card de Instalação do PWA -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #005f73, #0a9396);">
        <div class="position-absolute opacity-10" style="right: -10px; bottom: -10px;">
            <i class="bi bi-phone" style="font-size: 6rem;"></i>
        </div>
        <div class="d-flex align-items-center gap-3 position-relative z-1 mb-3">
            <div class="rounded-4 bg-white p-2 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <img src="/icons/icon-192x192.png" alt="Logo App" style="width: 36px; height: 36px;" class="rounded-3">
            </div>
            <div>
                <h2 class="fs-6 fw-bold mb-0 text-white">Aplicativo no seu Celular</h2>
                <span class="small text-white-50">Funciona offline e sem ocupar memória</span>
            </div>
        </div>
        <button type="button" class="btn btn-light text-primary fw-bold w-100 rounded-pill py-2 shadow-sm btn-trigger-pwa-install position-relative z-1">
            <i class="bi bi-download me-1"></i> Instalar Aplicativo Agora
        </button>
    </div>

    <!-- Telefones de Emergência -->
    <div class="mb-4">
        <h2 class="fs-6 fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Telefones Úteis e Emergência</h2>
        <div class="d-flex flex-column gap-2">
            <a href="tel:190" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(186, 26, 26, 0.1); color: #ba1a1a;">
                        <i class="bi bi-shield-shaded fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Polícia Militar</div>
                        <div class="text-secondary small">Emergências policiais e segurança</div>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">190</span>
            </a>

            <a href="tel:192" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                        <i class="bi bi-hospital fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">SAMU</div>
                        <div class="text-secondary small">Ambulância e resgate médico</div>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">192</span>
            </a>

            <a href="tel:193" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(238, 155, 0, 0.1); color: #ee9b00;">
                        <i class="bi bi-fire fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Corpo de Bombeiros</div>
                        <div class="text-secondary small">Afogamentos, resgates e incêndios</div>
                    </div>
                </div>
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6 fw-bold">193</span>
            </a>

            <a href="tel:199" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(10, 147, 150, 0.1); color: var(--bs-secondary);">
                        <i class="bi bi-shield-check fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Defesa Civil</div>
                        <div class="text-secondary small">Alertas climáticos e enchentes</div>
                    </div>
                </div>
                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">199</span>
            </a>
        </div>
    </div>
</div>
@endsection

