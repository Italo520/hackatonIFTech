@extends('layouts.pwa')

@section('content')
<div class="container-fluid px-3 py-4 mb-5" style="max-width: 650px;">
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pwa.utilidade') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-chevron-left text-dark fs-5"></i>
        </a>
        <div>
            <h1 class="fw-bold text-dark fs-4 mb-0">Privacidade & LGPD</h1>
            <span class="text-muted small">Transparência e controle sobre seus dados pessoais</span>
        </div>
    </div>

    <!-- Termos e Minimização de Dados -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
        <div class="d-flex align-items-center gap-2 mb-2 text-primary">
            <i class="bi bi-shield-check fs-4"></i>
            <h5 class="fw-bold mb-0">Privacidade por Design (LGPD)</h5>
        </div>
        <p class="small text-secondary mb-3">
            O aplicativo <strong>Destino Inteligente</strong> foi projetado seguindo o princípio da minimização de dados da Lei Geral de Proteção de Dados (Lei nº 13.709/2018). Você pode navegar livremente sem criar conta.
        </p>
        <div class="p-3 bg-light rounded-3 small text-muted">
            <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Mapas de calor e estatísticas:</strong> Todos os dados de engajamento são anonimizados e agregados (com supressão estatística para grupos menores que 5 pessoas).
        </div>
    </div>

    <!-- Gerenciamento de Consentimentos Granulares -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
        <h5 class="fw-bold text-dark mb-3">Seus Consentimentos</h5>
        
        <div class="d-flex flex-column gap-3">
            <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                <div>
                    <div class="fw-semibold small text-dark">Geolocalização (GPS)</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Para calcular distâncias e sugerir atrações próximas</div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="consent-gps" checked>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                <div>
                    <div class="fw-semibold small text-dark">Alertas da Defesa Civil & Clima</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Para receber notificações sobre marés e tempestades</div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="consent-alerts" checked>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold small text-dark">Métricas de Uso e IA</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Para aprimoramento contínuo das rotas geradas</div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="consent-analytics" checked>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary btn-sm rounded-pill mt-3 w-100 fw-semibold shadow-sm" id="btn-salvar-consentimentos" onclick="saveConsents()">
            <i class="bi bi-check2-circle me-1"></i> Salvar Preferências
        </button>
        <div id="consent-feedback" class="mt-2 text-center text-success small fw-medium d-none">
            <i class="bi bi-shield-fill-check"></i> Suas preferências foram salvas com sucesso no sistema.
        </div>
    </div>

    <!-- Direitos do Titular (Exportação e Exclusão) -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
        <h5 class="fw-bold text-dark mb-3">Direitos do Titular (LGPD)</h5>
        
        <div class="d-flex flex-column gap-2">
            <button class="btn btn-light border rounded-3 p-3 d-flex align-items-center justify-content-between text-start" onclick="exportUserData()">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-download text-primary fs-4"></i>
                    <div>
                        <div class="fw-bold small text-dark">Exportar Meus Dados (JSON)</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Baixe uma cópia dos seus favoritos, preferências e histórico</div>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted small"></i>
            </button>

            <button class="btn btn-light border border-danger-subtle rounded-3 p-3 d-flex align-items-center justify-content-between text-start text-danger" onclick="clearUserData()">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-trash text-danger fs-4"></i>
                    <div>
                        <div class="fw-bold small text-danger">Excluir e Anonimizar Dados</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Limpa cache, favoritos salvos e redefinição de identificadores</div>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted small"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function saveConsents() {
    const btn = document.getElementById('btn-salvar-consentimentos');
    const feedback = document.getElementById('consent-feedback');
    const payload = {
        gps: document.getElementById('consent-gps').checked,
        alertas: document.getElementById('consent-alerts').checked,
        metricas: document.getElementById('consent-analytics').checked
    };

    localStorage.setItem('turismo_lgpd_consents', JSON.stringify(payload));
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Salvando...';

    try {
        await fetch('/api/v1/lgpd/consentimentos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });
        feedback.classList.remove('d-none');
        setTimeout(() => feedback.classList.add('d-none'), 4000);
    } catch(err) {
        console.error('Erro ao sincronizar consentimentos:', err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Salvar Preferências';
    }
}

function exportUserData() {
    const data = {
        app: "Destino Inteligente",
        export_date: new Date().toISOString(),
        location_preferences: JSON.parse(localStorage.getItem('turismo_user_location') || '{}'),
        offline_roteiros: JSON.parse(localStorage.getItem('saved_offline_roteiros') || '{}'),
        lgpd_consents: {
            gps: document.getElementById('consent-gps').checked,
            alerts: document.getElementById('consent-alerts').checked,
            analytics: document.getElementById('consent-analytics').checked
        }
    };
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `dados_turista_${new Date().getTime()}.json`;
    a.click();
}

function clearUserData() {
    if (confirm('Tem certeza que deseja limpar seus dados locais e anonimizar seu uso?')) {
        localStorage.removeItem('saved_offline_roteiros');
        localStorage.removeItem('turismo_user_location');
        localStorage.removeItem('turismo_lgpd_consents');
        localStorage.removeItem('meus_roteiros_ia');
        alert('Todos os dados locais foram excluídos e anonimizados.');
        window.location.reload();
    }
}
</script>
@endpush
@endsection
