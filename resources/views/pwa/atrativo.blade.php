@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<!-- Header flutuante over the entire page -->
<div class="position-fixed top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center z-3" style="pointer-events: none;">
    <!-- Back Button -->
    <a href="{{ route('pwa.explorar') }}" class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; pointer-events: auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
        <i class="bi bi-chevron-left text-dark fs-5"></i>
    </a>
    
    <!-- Action buttons -->
    <div class="d-flex gap-2" style="pointer-events: auto;">
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-heart text-danger fs-5"></i>
        </button>
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-share text-dark fs-5"></i>
        </button>
    </div>
</div>

<div class="container-fluid px-3 pt-5 pb-5 mt-2 mb-5">
    
    <!-- Main Hero Card (Not edge-to-edge) -->
    <div class="position-relative w-100 rounded-5 overflow-hidden shadow-sm mb-4" style="height: 320px; background-color: #f8f9fa;">
        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="w-100 h-100 object-fit-cover" alt="Rio Sucuri">
        
        <!-- Subtle inner gradient for contrast at bottom -->
        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 100%);">
            <span class="badge rounded-pill px-3 py-2 fw-bold" style="backdrop-filter: blur(5px); background-color: rgba(0, 95, 115, 0.8) !important; color: white;">
                Rios e Nascentes
            </span>
        </div>
    </div>

    <!-- Title & Rating -->
    <div class="d-flex justify-content-between align-items-end mb-4 px-1">
        <div>
            <h1 class="fw-bolder text-dark mb-1" style="font-size: 2rem; letter-spacing: -0.03em; line-height: 1.1;">Flutuação no <br>Rio Sucuri</h1>
            <p class="text-secondary small mb-0 d-flex align-items-center gap-1 mt-2">
                <i class="bi bi-geo-alt-fill text-primary"></i> Fazenda São Geraldo
            </p>
        </div>
        <div class="bg-warning text-dark rounded-4 p-2 d-flex flex-column align-items-center justify-content-center shadow-sm" style="min-width: 60px; height: 60px;">
            <i class="bi bi-star-fill small"></i>
            <span class="fw-bolder fs-5 lh-1 mt-1">4.9</span>
        </div>
    </div>

    <!-- About Section -->
    <div class="mb-4 px-1">
        <p class="text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
            Uma das águas mais cristalinas do mundo. Flutuação tranquila em meio a muita vida subaquática e vegetação exuberante.
        </p>
    </div>

    <!-- BENTO GRID -->
    <div class="row g-3 mb-5 pb-5">
        <!-- Duration / Price / Info stack -->
        <div class="col-6 d-flex flex-column gap-3">
            <!-- Duration -->
            <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm h-50">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(10, 147, 150, 0.15); color: #0a9396;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase; letter-spacing: 0.05em;">Duração</div>
                    <div class="fw-bolder text-dark" style="font-size: 0.9rem;">120 min</div>
                </div>
            </div>
            
            <!-- Price -->
            <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm h-50">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(238, 155, 0, 0.15); color: #ee9b00;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase; letter-spacing: 0.05em;">Preço (R$)</div>
                    <div class="fw-bolder text-dark" style="font-size: 0.9rem;">290,00</div>
                </div>
            </div>
        </div>

        <!-- Map / Location block -->
        <div class="col-6">
            <div id="map-container" class="rounded-4 overflow-hidden position-relative border shadow-sm h-100" style="min-height: 150px; background-color: #e9ecef; cursor: pointer;">
                <div id="map" class="w-100 h-100 position-absolute top-0 start-0"></div>
                
                <div class="position-absolute top-50 start-50 translate-middle text-center w-100" style="pointer-events: none; z-index: 1000;">
                    <span class="badge bg-white text-dark shadow-sm border px-3 py-2 rounded-pill" style="font-size: 0.7rem;">Abrir Maps</span>
                </div>
            </div>
        </div>

        <!-- Full width accessibility -->
        <div class="col-12">
            <div class="bg-light rounded-4 p-3 border shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-universal-access-circle text-primary fs-4"></i>
                    <span class="fw-bold text-dark fs-6">Acessibilidade PNE</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill fw-medium d-flex align-items-center gap-1 px-3 py-2 border text-dark bg-white shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-person-wheelchair text-primary"></i> Parcial
                    </span>
                    <span class="badge rounded-pill fw-medium d-flex align-items-center gap-1 px-3 py-2 border text-dark bg-white shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-ear text-secondary"></i> Libras
                    </span>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Floating Action Bar (FAB) -->
<div class="position-fixed start-50 translate-middle-x w-100 px-4 z-3 d-flex justify-content-center" style="bottom: 80px; max-width: 400px; pointer-events: none;">
    <button class="btn fw-bold py-3 px-4 rounded-pill shadow-lg d-flex align-items-center justify-content-center gap-2 text-white w-100" style="background-color: rgba(0, 95, 115, 0.95); font-size: 1.1rem; backdrop-filter: blur(10px); pointer-events: auto; border: 2px solid rgba(255,255,255,0.1);">
        <i class="bi bi-plus-circle-fill fs-4 text-warning"></i>
        <span>Adicionar ao Roteiro</span>
    </button>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Rio Sucuri coordinates
        var lat = -21.2588;
        var lng = -56.5518;
        var map = L.map('map', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false
        }).setView([lat, lng], 13);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        var icon = L.divIcon({
            html: '<div style="margin-top:-15px; margin-left:-7px;"><i class="bi bi-geo-alt-fill text-danger fs-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i></div>',
            className: 'custom-div-icon',
            iconSize: [30, 30]
        });
        
        L.marker([lat, lng], {icon: icon}).addTo(map);
        
        // click to open google maps
        document.getElementById('map-container').addEventListener('click', function() {
            window.open('https://maps.google.com/?q=' + lat + ',' + lng, '_blank');
        });
    });
</script>
@endpush
