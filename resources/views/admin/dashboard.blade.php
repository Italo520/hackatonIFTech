@extends('layouts.admin')

@section('title', 'Dashboard KPIs')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Atrativos Ativos</div>
                <div class="h1 mb-3">{{ $kpi['atrativos_ativos'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Eventos Ativos</div>
                <div class="h1 mb-3">{{ $kpi['eventos_ativos'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Interações com IA</div>
                <div class="h1 mb-3">{{ $kpi['ia_interacoes'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Eventos Analytics</div>
                <div class="h1 mb-3">{{ $kpi['analytics_eventos'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Mapa de Calor (Interesse Turístico)</h3></div>
            <div class="card-body" style="height: 400px; padding: 0;">
                <div id="heatmap" style="height: 100%; width: 100%;"></div>
            </div>
            <div class="card-footer text-muted">
                * Suprimimos células com < 5 indivíduos para garantir a privacidade (LGPD).
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('heatmap').setView([-14.235, -51.925], 4);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        fetch('/admin/heatmap-data')
            .then(res => res.json())
            .then(data => {
                var heat = L.heatLayer(data, {radius: 25, blur: 15}).addTo(map);
            });
    });
</script>
@endpush
