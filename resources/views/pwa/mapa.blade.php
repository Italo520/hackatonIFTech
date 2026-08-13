@extends('layouts.pwa')

@section('content')
<div class="row">
    <div class="col-12 px-0">
        <div id="map" class="map-container"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize map centered at a default location
        var map = L.map('map').setView([-14.235, -51.925], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        fetch('/api/v1/atrativos')
            .then(res => res.json())
            .then(data => {
                if(data.data) {
                    data.data.forEach(item => {
                        // Place markers logic would go here
                    });
                }
            });

        map.locate({setView: true, maxZoom: 14});
        map.on('locationfound', function(e){
            L.marker(e.latlng).addTo(map).bindPopup("Você está aqui!").openPopup();
        });
    });
</script>
@endpush
