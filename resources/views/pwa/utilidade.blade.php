@extends('layouts.pwa')

@section('content')
<div class="container-fluid px-3 py-4 mb-5">
    <!-- Header da Página -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark fs-3 mb-1" style="letter-spacing: -0.02em;">Utilidade & Acessibilidade</h1>
        <p class="text-secondary small mt-1">Serviços de emergência, suporte ao turista e personalização do app.</p>
    </div>

    <!-- Painel de Acessibilidade (WCAG 2.1 AA) -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 bg-white">
        <h2 class="fs-6 fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">
            <i class="bi bi-universal-access-circle text-primary me-1"></i> Acessibilidade & Inclusão
        </h2>
        <div class="row g-2">
            <!-- Tamanho da Fonte -->
            <div class="col-6">
                <div class="p-3 bg-light rounded-4 text-center">
                    <span class="small text-muted d-block mb-2">Tamanho do Texto</span>
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-white border btn-sm fw-bold" id="btn-font-dec">A-</button>
                        <button type="button" class="btn btn-white border btn-sm fw-bold" id="btn-font-reset">Normal</button>
                        <button type="button" class="btn btn-white border btn-sm fw-bold" id="btn-font-inc">A+</button>
                    </div>
                </div>
            </div>

            <!-- Alto Contraste -->
            <div class="col-6">
                <div class="p-3 bg-light rounded-4 text-center h-100 d-flex flex-column justify-content-between">
                    <span class="small text-muted d-block mb-1">Alto Contraste</span>
                    <button type="button" class="btn btn-white border btn-sm fw-bold w-100 rounded-pill py-1.5" id="btn-toggle-contrast">
                        <i class="bi bi-circle-half me-1 text-primary"></i> Alternar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Centros de Atendimento ao Turista (CATs) -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fs-6 fw-bold text-dark mb-0 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">
                <i class="bi bi-info-circle-fill text-info me-1"></i> Centros de Atendimento ao Turista (CAT)
            </h2>
            <span class="badge bg-light text-primary border rounded-pill small">Oficial</span>
        </div>
        <div class="d-flex flex-column gap-2" id="cats-container">
            <div class="p-3 bg-light rounded-4">
                <div class="fw-bold text-dark small">CAT Tambaú (João Pessoa - PB)</div>
                <div class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-geo-alt me-1"></i> Av. Almirante Tamandaré, s/n - Orla de Tambaú</div>
                <div class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-clock me-1"></i> Diariamente das 08h às 20h</div>
                <a href="tel:8332147000" class="btn btn-sm btn-outline-primary rounded-pill mt-2 py-1 px-3 fw-semibold" style="font-size: 0.75rem;">
                    <i class="bi bi-telephone-fill me-1"></i> (83) 3214-7000
                </a>
            </div>
            <div class="p-3 bg-light rounded-4">
                <div class="fw-bold text-dark small">CAT Centro (Bonito - MS)</div>
                <div class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-geo-alt me-1"></i> Praça da Liberdade, Centro</div>
                <div class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-clock me-1"></i> Seg a Sáb das 07h às 18h</div>
                <a href="tel:6732551850" class="btn btn-sm btn-outline-primary rounded-pill mt-2 py-1 px-3 fw-semibold" style="font-size: 0.75rem;">
                    <i class="bi bi-telephone-fill me-1"></i> (67) 3255-1850
                </a>
            </div>
        </div>
    </div>

    <!-- Telefones de Emergência (Sempre Offline) -->
    <div class="mb-4">
        <h2 class="fs-6 fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">
            <i class="bi bi-telephone-fill text-danger me-1"></i> Telefones de Emergência
        </h2>
        <div class="d-flex flex-column gap-2">
            <a href="tel:190" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(186, 26, 26, 0.1); color: #ba1a1a;">
                        <i class="bi bi-shield-shaded fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Polícia Militar</div>
                        <div class="text-secondary small">Emergências e segurança pública</div>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">190</span>
            </a>

            <a href="tel:192" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                        <i class="bi bi-hospital fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">SAMU</div>
                        <div class="text-secondary small">Resgate médico e ambulância</div>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">192</span>
            </a>

            <a href="tel:193" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(238, 155, 0, 0.1); color: #ee9b00;">
                        <i class="bi bi-fire fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Corpo de Bombeiros</div>
                        <div class="text-secondary small">Afogamentos, resgates e salvamento</div>
                    </div>
                </div>
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6 fw-bold">193</span>
            </a>

            <a href="tel:199" class="card border-0 rounded-4 p-3 shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(10, 147, 150, 0.1); color: var(--bs-secondary);">
                        <i class="bi bi-shield-check fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">Defesa Civil</div>
                        <div class="text-secondary small">Alertas de maré e chuvas fortes</div>
                    </div>
                </div>
                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">199</span>
            </a>
        </div>
    </div>

    <!-- Privacidade e LGPD -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Privacidade & LGPD</h6>
                    <span class="text-muted small">Gerencie seus consentimentos e dados</span>
                </div>
            </div>
            <a href="/privacidade" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                Acessar <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let fontScale = 1.0;
    const body = document.body;

    document.getElementById('btn-font-inc')?.addEventListener('click', function() {
        if (fontScale < 1.3) fontScale += 0.1;
        body.style.fontSize = fontScale + 'rem';
    });

    document.getElementById('btn-font-dec')?.addEventListener('click', function() {
        if (fontScale > 0.85) fontScale -= 0.1;
        body.style.fontSize = fontScale + 'rem';
    });

    document.getElementById('btn-font-reset')?.addEventListener('click', function() {
        fontScale = 1.0;
        body.style.fontSize = '1rem';
    });

    document.getElementById('btn-toggle-contrast')?.addEventListener('click', function() {
        body.classList.toggle('high-contrast');
        if (body.classList.contains('high-contrast')) {
            body.style.filter = 'contrast(125%)';
        } else {
            body.style.filter = 'none';
        }
    });
});
</script>
@endpush
@endsection
