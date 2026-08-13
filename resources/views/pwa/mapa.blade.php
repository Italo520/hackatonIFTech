@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #pwa-map {
        width: 100%;
        height: calc(100vh - 120px);
        min-height: 400px;
        z-index: 1;
    }
    .user-pulse-marker {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #005f73;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 0 rgba(0, 95, 115, 0.7);
        animation: pulse-ring 1.8s infinite cubic-bezier(0.455, 0.03, 0.515, 0.955);
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 95, 115, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 16px rgba(0, 95, 115, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 95, 115, 0); }
    }
</style>
@endpush

@section('content')
<div class="position-relative w-100 h-100" style="margin-top: -1rem;">
    <!-- Container do Mapa OpenStreetMap -->
    <div id="pwa-map"></div>

    <!-- Card Flutuante de Localização Atual -->
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 px-3 pt-3" style="max-width: 440px; z-index: 1000; pointer-events: none;">
        <div class="bg-white rounded-4 shadow-lg p-3 border d-flex justify-content-between align-items-center" style="pointer-events: auto; backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.92) !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: rgba(0, 95, 115, 0.12); color: var(--bs-primary);">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Local Atual (OpenStreetMap)</div>
                    <div class="fw-bold text-dark fs-6 current-location-display" id="map-location-title">Detectando...</div>
                </div>
            </div>
            <button type="button" id="btn-map-recenter" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm" style="width: 40px; height: 40px;" title="Recentralizar no meu GPS">
                <i class="bi bi-crosshair fs-5"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Posição inicial (João Pessoa como padrão fallback se não houver)
        var defaultLat = -7.1153;
        var defaultLng = -34.8641;
        var defaultZoom = 13;

        var saved = null;
        if (window.LocationService) {
            saved = window.LocationService.getSavedLocation();
        }

        var currentLat = (saved && saved.lat) ? parseFloat(saved.lat) : defaultLat;
        var currentLng = (saved && saved.lng) ? parseFloat(saved.lng) : defaultLng;

        var map = L.map('pwa-map', {
            zoomControl: false
        }).setView([currentLat, currentLng], defaultZoom);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Camada do OpenStreetMap Oficial
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marcador do Usuário
        var userIcon = L.divIcon({
            html: '<div class="user-pulse-marker"></div>',
            className: 'custom-user-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var userMarker = L.marker([currentLat, currentLng], { icon: userIcon }).addTo(map);
        userMarker.bindPopup('<b>Você está aqui</b><br><span id="popup-city-name">' + ((saved && saved.display) ? saved.display : 'Sua localização') + '</span>');

        function updateMapLocation(lat, lng, display) {
            userMarker.setLatLng([lat, lng]);
            map.flyTo([lat, lng], 14, { duration: 1.2 });
            userMarker.setPopupContent('<b>Você está aqui</b><br>' + display);
            var titleEl = document.getElementById('map-location-title');
            if (titleEl) titleEl.textContent = display;
        }

        if (saved && saved.display) {
            var titleEl = document.getElementById('map-location-title');
            if (titleEl) titleEl.textContent = saved.display;
        }

        // Listener para mudanças de localização em tempo real
        window.addEventListener('turismo:location-changed', function(e) {
            var data = e.detail;
            if (data && data.lat && data.lng) {
                updateMapLocation(parseFloat(data.lat), parseFloat(data.lng), data.display || data.city);
            }
        });

        // Botão de recentralizar
        var recenterBtn = document.getElementById('btn-map-recenter');
        if (recenterBtn) {
            recenterBtn.addEventListener('click', function() {
                if (window.LocationService) {
                    recenterBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    window.LocationService.detectGPS({
                        showLoading: false,
                        onSuccess: function(data) {
                            recenterBtn.innerHTML = '<i class="bi bi-crosshair fs-5"></i>';
                            updateMapLocation(parseFloat(data.lat), parseFloat(data.lng), data.display);
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
    });
</script>
@endpush

