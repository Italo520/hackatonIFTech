@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .custom-marker { border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
    .custom-marker-start { border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
</style>
@endpush

@section('content')
<div class="container pb-5 h-100 d-flex flex-column pt-4">
    <div class="text-center mb-4">
        <div class="rounded-circle d-inline-flex p-3 mb-3" style="background: rgba(155, 34, 38, 0.1); color: var(--bs-danger);">
            <i class="bi bi-wifi-off" style="font-size: 2.5rem;"></i>
        </div>
        
        <h2 class="fw-bold mb-2">Modo Offline</h2>
        <p class="text-secondary small px-3">Você está sem internet, mas sua viagem não para. Acesse seus roteiros salvos e serviços de emergência abaixo.</p>
        
        <button onclick="window.location.reload()" class="btn btn-outline-primary rounded-pill px-4 py-1.5 fw-bold shadow-sm mt-2" style="font-size: 0.85rem;">
            <i class="bi bi-arrow-clockwise me-1"></i> Tentar Reconectar
        </button>
    </div>

    <!-- Seção de Roteiros Salvos -->
    <div class="mb-4 w-100 mx-auto" style="max-width: 500px;">
        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 px-1">
            <i class="bi bi-cloud-check-fill text-success"></i> Meus Roteiros Salvos
        </h6>
        
        <div id="offline-roteiros-container" class="d-flex flex-column gap-3">
            <div class="text-center p-4 bg-light rounded-4 border border-dashed">
                <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                <p class="small text-muted mb-0">Carregando roteiros offline...</p>
            </div>
        </div>
    </div>

    <!-- Seção de Telefones Úteis -->
    <div class="card border-0 rounded-4 shadow-sm text-start mx-auto w-100 mb-5" style="max-width: 500px; background: rgba(255, 255, 255, 0.9);">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h6 class="fw-bold m-0 d-flex align-items-center gap-2">
                <i class="bi bi-telephone-fill text-danger"></i> Telefones de Emergência
            </h6>
            <p class="small text-muted mb-3">Estes números funcionam sem dados móveis</p>
        </div>
        <div class="card-body pt-0 pb-4">
            <div class="d-flex flex-column gap-2">
                <a href="tel:190" class="btn btn-light border rounded-3 py-2 d-flex justify-content-between align-items-center text-dark text-decoration-none">
                    <span class="fw-semibold small"><i class="bi bi-shield-lock text-secondary me-2"></i> Polícia Militar</span>
                    <span class="badge bg-danger rounded-pill">190</span>
                </a>
                <a href="tel:192" class="btn btn-light border rounded-3 py-2 d-flex justify-content-between align-items-center text-dark text-decoration-none">
                    <span class="fw-semibold small"><i class="bi bi-heart-pulse text-secondary me-2"></i> SAMU</span>
                    <span class="badge bg-danger rounded-pill">192</span>
                </a>
                <a href="tel:193" class="btn btn-light border rounded-3 py-2 d-flex justify-content-between align-items-center text-dark text-decoration-none">
                    <span class="fw-semibold small"><i class="bi bi-fire text-secondary me-2"></i> Bombeiros</span>
                    <span class="badge bg-danger rounded-pill">193</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para exibir Roteiro Offline -->
<div class="modal fade" id="modalRoteiroOffline" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold" id="modalRoteiroTitle">Detalhes do Roteiro</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modalRoteiroContent" class="px-3 pb-4"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let offlineMap = null;
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('offline-roteiros-container');
    
    function loadOfflineRoteiros() {
        try {
            const saved = JSON.parse(localStorage.getItem('saved_offline_roteiros') || '{}');
            const roteirosIds = Object.keys(saved);
            
            if (roteirosIds.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-4 bg-light rounded-4 border border-dashed">
                        <i class="bi bi-inbox text-secondary fs-3 mb-2 d-block"></i>
                        <p class="small text-muted mb-0">Você não tem nenhum roteiro baixado para acessar offline.</p>
                        <p class="small text-muted mt-1" style="font-size: 0.7rem;">Quando tiver internet, clique em "Salvar Offline" na página de um roteiro.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            roteirosIds.forEach(id => {
                const rot = saved[id];
                const card = document.createElement('div');
                card.className = 'card border-0 rounded-4 shadow-sm overflow-hidden bg-white cursor-pointer';
                card.innerHTML = `
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="${rot.imagem}" class="img-fluid rounded-start-4 h-100 object-fit-cover" alt="${rot.titulo}" onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23e9ecef%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20fill%3D%22%23adb5bd%22%20font-family%3D%22sans-serif%22%20font-size%3D%2214%22%20font-weight%3D%22bold%22%20text-anchor%3D%22middle%22%20dy%3D%22.3em%22%3ESem%20Imagem%3C%2Ftext%3E%3C%2Fsvg%3E'">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5 mb-1" style="font-size: 0.65rem;">${rot.cidade}</span>
                                <h6 class="card-title fw-bold mb-1 lh-sm" style="font-size: 0.9rem;">${rot.titulo}</h6>
                                <p class="card-text text-secondary small mb-2 text-truncate" style="font-size: 0.75rem;">${rot.duracao} • ${rot.paradas ? rot.paradas.length : 0} paradas</p>
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold w-100 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.75rem;" onclick='openRoteiroModal(${JSON.stringify(rot)})'>
                                    <i class="bi bi-eye"></i> Ver Itinerário
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        } catch(e) {
            console.error('Erro ao ler roteiros offline', e);
            container.innerHTML = '<div class="alert alert-danger small">Erro ao carregar roteiros offline salvos.</div>';
        }
    }

    loadOfflineRoteiros();
});

function openRoteiroModal(rot) {
    const modalTitle = document.getElementById('modalRoteiroTitle');
    const modalContent = document.getElementById('modalRoteiroContent');
    
    modalTitle.textContent = rot.titulo;
    
    let paradasHtml = '';
    if (rot.paradas && rot.paradas.length > 0) {
        paradasHtml = rot.paradas.map(p => `
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-light mb-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        ${p.ordem}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-primary border rounded-pill px-2 py-0.5 small" style="font-size: 0.65rem;">${p.tipo}</span>
                            <span class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i> ${p.tempo}</span>
                        </div>
                        <h6 class="fw-bold text-dark mt-1 mb-1" style="font-size: 0.85rem;">${p.nome}</h6>
                        <p class="small text-secondary mb-0" style="font-size: 0.75rem;">${p.descricao}</p>
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        paradasHtml = '<p class="text-muted small">Nenhuma parada cadastrada.</p>';
    }

    modalContent.innerHTML = `
        <div class="mb-3 mt-2">
            <span class="fw-bold d-block text-dark small mb-1">Informações Básicas</span>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1"><i class="bi bi-clock-history me-1"></i> ${rot.duracao}</span>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1"><i class="bi bi-wallet2 me-1"></i> ${rot.orcamento || 'Não estimado'}</span>
            </div>
            <p class="text-muted small mb-0">${rot.descricao}</p>
        </div>
        
        <div id="offline-map-container" class="rounded-4 overflow-hidden mb-3 d-none shadow-sm border" style="height: 220px; width: 100%;"></div>
        
        <hr>
        <span class="fw-bold d-block text-dark small mb-3">Itinerário Salvo</span>
        ${paradasHtml}
    `;

    const myModal = new bootstrap.Modal(document.getElementById('modalRoteiroOffline'));
    myModal.show();
    
    // Configurar o Mapa Offline após o modal abrir para garantir que a div tenha tamanho
    const mapContainer = document.getElementById('offline-map-container');
    
    // Limpar mapa anterior se existir
    if (offlineMap) {
        offlineMap.remove();
        offlineMap = null;
    }
    
    if (rot.geojson || (rot.paradas && rot.paradas.length > 0)) {
        mapContainer.classList.remove('d-none');
        
        // Timeout para garantir que a animação do modal termine antes de renderizar o mapa
        setTimeout(() => {
            const startLat = rot.paradas && rot.paradas.length > 0 ? rot.paradas[0].lat : -7.1153;
            const startLng = rot.paradas && rot.paradas.length > 0 ? rot.paradas[0].lng : -34.8641;
            
            offlineMap = L.map('offline-map-container', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([startLat, startLng], 13);
            
            // O cache do Service Worker garantirá que os tiles carreguem offline (se o usuário visualizou antes)
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OSM'
            }).addTo(offlineMap);
            
            const allPoints = [];
            
            if (rot.paradas && rot.paradas.length > 0) {
                rot.paradas.forEach((p) => {
                    const pos = [p.lat, p.lng];
                    allPoints.push(pos);

                    const customIcon = L.divIcon({
                        className: 'custom-marker bg-primary text-white fw-bold d-flex align-items-center justify-content-center',
                        html: `<span style="font-size: 0.8rem;">${p.ordem}</span>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 14]
                    });

                    L.marker(pos, { icon: customIcon })
                     .addTo(offlineMap)
                     .bindPopup(`<strong>${p.ordem}. ${p.nome}</strong>`);
                });
            }
            
            // Renderiza a linha exata se tivermos o geojson
            if (rot.geojson) {
                const routeLayer = L.geoJSON(rot.geojson, {
                    style: {
                        color: '#0a9396',
                        weight: 5,
                        opacity: 0.88,
                        lineJoin: 'round'
                    }
                }).addTo(offlineMap);
                offlineMap.fitBounds(routeLayer.getBounds(), { padding: [20, 20] });
            } else if (allPoints.length > 1) {
                // Fallback para linha reta se não tiver o geojson real salvo
                L.polyline(allPoints, {
                    color: '#005f73',
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '6, 6'
                }).addTo(offlineMap);
                offlineMap.fitBounds(L.latLngBounds(allPoints), { padding: [20, 20] });
            }
        }, 300);
    }
}
</script>
@endpush
