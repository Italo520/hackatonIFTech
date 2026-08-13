@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ALL_PLACES = [
            // João Pessoa - PB
            {
                id: 101,
                nome: 'Praia de Tambaú',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'João Pessoa',
                lat: -7.1147,
                lng: -34.8239,
                color: '#0077b6',
                icon: 'bi-water',
                img: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            },
            {
                id: 102,
                nome: 'Farol do Cabo Branco',
                cat: 'Monumentos & Natureza',
                catKey: 'natureza',
                cidade: 'João Pessoa',
                lat: -7.1477,
                lng: -34.7963,
                color: '#ee9b00',
                icon: 'bi-geo-alt-fill',
                img: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
                rating: 4.8
            },
            {
                id: 103,
                nome: 'Piscinas Naturais dos Seixas',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'João Pessoa',
                lat: -7.1597,
                lng: -34.7877,
                color: '#0077b6',
                icon: 'bi-water',
                img: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            },
            {
                id: 104,
                nome: 'Centro Cultural São Francisco',
                cat: 'Cultura & História',
                catKey: 'cultura',
                cidade: 'João Pessoa',
                lat: -7.1155,
                lng: -34.8864,
                color: '#9b2226',
                icon: 'bi-bank',
                img: 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            },
            {
                id: 105,
                nome: 'Mangai João Pessoa',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'João Pessoa',
                lat: -7.1067,
                lng: -34.8315,
                color: '#2a9d8f',
                icon: 'bi-cup-hot',
                img: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            },
            {
                id: 106,
                nome: 'Nau Frutos do Mar',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'João Pessoa',
                lat: -7.1189,
                lng: -34.8302,
                color: '#2a9d8f',
                icon: 'bi-cup-hot',
                img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80',
                rating: 4.8
            },
            // Bonito - MS
            {
                id: 1,
                nome: 'Flutuação no Rio Sucuri',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'Bonito',
                lat: -21.2642,
                lng: -56.5516,
                color: '#0077b6',
                icon: 'bi-water',
                img: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            },
            {
                id: 2,
                nome: 'Gruta do Lago Azul',
                cat: 'Monumentos & Natureza',
                catKey: 'natureza',
                cidade: 'Bonito',
                lat: -21.1469,
                lng: -56.5861,
                color: '#ee9b00',
                icon: 'bi-geo-alt-fill',
                img: 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=400&q=80',
                rating: 4.8
            },
            {
                id: 3,
                nome: 'Bóia Cross no Rio Formoso',
                cat: 'Aventura & Trilhas',
                catKey: 'natureza',
                cidade: 'Bonito',
                lat: -21.1895,
                lng: -56.4523,
                color: '#0a9396',
                icon: 'bi-bicycle',
                img: 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=400&q=80',
                rating: 4.7
            },
            {
                id: 4,
                nome: 'Casa do João',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'Bonito',
                lat: -21.1275,
                lng: -56.4831,
                color: '#2a9d8f',
                icon: 'bi-cup-hot',
                img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80',
                rating: 4.9
            }
        ];

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


