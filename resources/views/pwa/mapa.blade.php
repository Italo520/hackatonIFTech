@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #pwa-map {
        width: 100%;
        height: calc(100vh - 125px);
        min-height: 450px;
        z-index: 1;
    }
    .user-pulse-marker {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #0077b6;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 0 rgba(0, 119, 182, 0.7);
        animation: pulse-ring 1.8s infinite cubic-bezier(0.455, 0.03, 0.515, 0.955);
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 119, 182, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 16px rgba(0, 119, 182, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 119, 182, 0); }
    }
    .atrativo-pin {
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
        border: 2px solid #ffffff;
    }
    .atrativo-pin i {
        transform: rotate(45deg);
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1.25rem !important;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.3;
    }
</style>
@endpush

@section('content')
<div class="position-relative w-100 h-100" style="margin-top: -1rem;">
    <!-- Container do Mapa Leaflet -->
    <div id="pwa-map"></div>

    <!-- Controles Flutuantes Superiores -->
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 px-3 pt-3" style="max-width: 480px; z-index: 1000; pointer-events: none;">
        <!-- Card de Localização Atual -->
        <div class="bg-white rounded-4 shadow-lg p-3 border d-flex justify-content-between align-items-center mb-2" style="pointer-events: auto; backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.94) !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(0, 95, 115, 0.12); color: var(--bs-primary);">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Sua Localização Real</div>
                    <div class="fw-bold text-dark fs-6 current-location-display" id="map-location-title">Detectando GPS...</div>
                </div>
            </div>
            <button type="button" id="btn-map-recenter" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm" style="width: 40px; height: 40px;" title="Recentralizar no meu GPS">
                <i class="bi bi-crosshair fs-5"></i>
            </button>
        </div>

        @if(isset($alertasDefesaCivil) && $alertasDefesaCivil->count() > 0)
            @php $topAlerta = $alertasDefesaCivil->first(); @endphp
            <div class="alert alert-warning border-0 rounded-4 shadow-sm py-2 px-3 mb-2 d-flex align-items-center justify-content-between gap-2" style="pointer-events: auto;">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <i class="bi bi-shield-exclamation text-danger fs-5 flex-shrink-0"></i>
                    <div class="text-truncate small fw-bold text-dark" style="font-size: 0.78rem;">
                        {{ $topAlerta->titulo }}
                    </div>
                </div>
                <a href="{{ route('pwa.home') }}" class="btn btn-sm btn-dark rounded-pill px-2.5 py-0.5 fw-bold flex-shrink-0" style="font-size: 0.7rem;">
                    Ver
                </a>
            </div>
        @endif

        <!-- Filtros Rápidos de Categoria no Mapa -->
        <div class="d-flex gap-2 overflow-auto no-scrollbar pb-1" style="pointer-events: auto;">
            <button class="btn btn-sm btn-dark rounded-pill px-3 fw-medium flex-shrink-0 map-filter-btn active" data-cat="all">Todos</button>
            <button class="btn btn-sm btn-white bg-white border rounded-pill px-3 fw-medium flex-shrink-0 map-filter-btn text-primary" data-cat="praia">
                <i class="bi bi-water me-1"></i> Praias & Rios
            </button>
            <button class="btn btn-sm btn-white bg-white border rounded-pill px-3 fw-medium flex-shrink-0 map-filter-btn text-success" data-cat="gastronomia">
                <i class="bi bi-cup-hot me-1"></i> Gastronomia
            </button>
            <button class="btn btn-sm btn-white bg-white border rounded-pill px-3 fw-medium flex-shrink-0 map-filter-btn text-warning" data-cat="natureza">
                <i class="bi bi-tree me-1"></i> Natureza
            </button>
            <button class="btn btn-sm btn-white bg-white border rounded-pill px-3 fw-medium flex-shrink-0 map-filter-btn text-danger" data-cat="cultura">
                <i class="bi bi-bank me-1"></i> Cultura
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ALL_PLACES = (window.LocationService ? window.LocationService.getAllPlaces() : (window.PLACES_DATA || []));

        let saved = window.LocationService ? window.LocationService.getSavedLocation() : null;
        let currentLat = (saved && saved.lat) ? parseFloat(saved.lat) : -7.1153;
        let currentLng = (saved && saved.lng) ? parseFloat(saved.lng) : -34.8641;

        const map = L.map('pwa-map', {
            zoomControl: false
        }).setView([currentLat, currentLng], 13);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // OpenStreetMap Layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // User Marker
        const userIcon = L.divIcon({
            html: '<div class="user-pulse-marker"></div>',
            className: 'custom-user-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const userMarker = L.marker([currentLat, currentLng], { icon: userIcon }).addTo(map);
        userMarker.bindPopup('<b>Você está aqui</b><br>' + (saved?.display || 'Sua localização atual'));

        let markersLayer = L.layerGroup().addTo(map);
        let currentFilter = 'all';

        function renderMarkers() {
            markersLayer.clearLayers();
            const savedLoc = window.LocationService ? window.LocationService.getSavedLocation() : null;
            const uLat = savedLoc?.lat ? parseFloat(savedLoc.lat) : null;
            const uLng = savedLoc?.lng ? parseFloat(savedLoc.lng) : null;

            ALL_PLACES.forEach(place => {
                if (currentFilter !== 'all' && place.catKey !== currentFilter) {
                    return;
                }

                let distFormatted = '';
                if (uLat && uLng && window.LocationService) {
                    const distKm = window.LocationService.calculateDistanceKm(uLat, uLng, place.lat, place.lng);
                    distFormatted = window.LocationService.formatDistance(distKm);
                }

                const pinIcon = L.divIcon({
                    html: `<div class="atrativo-pin" style="background-color: ${place.color};"><i class="bi ${place.icon}"></i></div>`,
                    className: 'custom-place-marker',
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    popupAnchor: [0, -36]
                });

                const popupHtml = `
                    <div style="width: 220px; font-family: inherit;">
                        <img src="${place.img}" style="width: 100%; height: 110px; object-fit: cover;" alt="${place.nome}">
                        <div style="padding: 10px 12px;">
                            <span style="font-size: 0.65rem; text-transform: uppercase; font-weight: bold; color: ${place.color};">${place.cat}</span>
                            <h6 style="margin: 2px 0 4px; font-weight: bold; font-size: 0.95rem;">${place.nome}</h6>
                            ${distFormatted ? `
                                <div style="font-size: 0.75rem; color: #555; margin-bottom: 8px;">
                                    <i class="bi bi-geo-alt-fill text-warning"></i> <strong>${distFormatted}</strong> de você
                                </div>
                            ` : ''}
                            <div style="display: flex; gap: 6px;">
                                <a href="/atrativo/${place.id}" class="btn btn-primary btn-sm rounded-pill w-100 py-1" style="font-size: 0.75rem;">Detalhes</a>
                                <button onclick="window.LocationService.openDirections(${place.lat}, ${place.lng}, '${place.nome}')" class="btn btn-outline-secondary btn-sm rounded-pill w-100 py-1" style="font-size: 0.75rem;">Rota</button>
                            </div>
                        </div>
                    </div>
                `;

                const marker = L.marker([place.lat, place.lng], { icon: pinIcon });
                marker.bindPopup(popupHtml);
                markersLayer.addLayer(marker);
            });
        }

        renderMarkers();

        // Atualização de localização
        function updateLocation(lat, lng, display) {
            userMarker.setLatLng([lat, lng]);
            userMarker.setPopupContent('<b>Você está aqui</b><br>' + display);
            map.flyTo([lat, lng], 13, { duration: 1.2 });
            const titleEl = document.getElementById('map-location-title');
            if (titleEl) titleEl.textContent = display;
            renderMarkers();
        }

        if (saved && saved.display) {
            const titleEl = document.getElementById('map-location-title');
            if (titleEl) titleEl.textContent = saved.display;
        }

        window.addEventListener('turismo:location-changed', function(e) {
            const data = e.detail;
            if (data && data.lat && data.lng) {
                updateLocation(parseFloat(data.lat), parseFloat(data.lng), data.display || data.city);
            }
        });

        // Botão de recentralizar
        const recenterBtn = document.getElementById('btn-map-recenter');
        if (recenterBtn) {
            recenterBtn.addEventListener('click', function() {
                if (window.LocationService) {
                    recenterBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    window.LocationService.detectGPS({
                        showLoading: false,
                        onSuccess: function(data) {
                            recenterBtn.innerHTML = '<i class="bi bi-crosshair fs-5"></i>';
                            updateLocation(parseFloat(data.lat), parseFloat(data.lng), data.display);
                        },
                        onError: function() {
                            recenterBtn.innerHTML = '<i class="bi bi-crosshair fs-5"></i>';
                            map.flyTo(userMarker.getLatLng(), 14);
                        }
                    });
                } else {
                    map.flyTo(userMarker.getLatLng(), 14);
                }
            });
        }

        // Filtros de Categoria
        document.querySelectorAll('.map-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.map-filter-btn').forEach(b => {
                    b.classList.remove('btn-dark', 'active');
                    b.classList.add('btn-white', 'bg-white');
                });
                this.classList.remove('btn-white', 'bg-white');
                this.classList.add('btn-dark', 'active');
                currentFilter = this.getAttribute('data-cat');
                renderMarkers();
            });
        });
    });
</script>
@endpush


