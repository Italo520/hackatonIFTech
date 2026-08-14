@extends('layouts.pwa')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@php
    $roteiros = [
        101 => [
            'id' => 101,
            'titulo' => 'Orla, Piscinas do Seixas & Farol',
            'cidade' => 'João Pessoa - PB',
            'duracao' => '1 Dia (6 a 8 horas)',
            'descricao' => 'Um roteiro litorâneo perfeito que combina o melhor da orla de Tambaú, mergulho nas piscinas de corais dos Seixas e pôr do sol no Farol do Cabo Branco.',
            'imagem' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'orcamento' => 'R$ 80 - 150 por pessoa',
            'paradas' => [
                [
                    'ordem' => 1,
                    'atrativo_id' => 101,
                    'nome' => 'Praia de Tambaú',
                    'tipo' => 'Manhã',
                    'tempo' => '08:30 - 10:30',
                    'descricao' => 'Caminhada matinal no calçadão, banho de mar em águas mornas e embarque para as piscinas.',
                    'lat' => -7.1147,
                    'lng' => -34.8239
                ],
                [
                    'ordem' => 2,
                    'atrativo_id' => 103,
                    'nome' => 'Piscinas Naturais dos Seixas',
                    'tipo' => 'Meio-dia',
                    'tempo' => '11:00 - 13:30',
                    'descricao' => 'Passeio de catamarã na maré baixa com flutuação entre peixes coloridos e recifes.',
                    'lat' => -7.1597,
                    'lng' => -34.7877
                ],
                [
                    'ordem' => 3,
                    'atrativo_id' => 102,
                    'nome' => 'Farol do Cabo Branco',
                    'tipo' => 'Tarde / Pôr do Sol',
                    'tempo' => '15:30 - 17:30',
                    'descricao' => 'Vista panorâmica do ponto mais oriental das Américas, falésias e brisa do Atlântico.',
                    'lat' => -7.1477,
                    'lng' => -34.7963
                ]
            ]
        ],
        102 => [
            'id' => 102,
            'titulo' => 'História Barroca & Culinária Regional',
            'cidade' => 'João Pessoa - PB',
            'duracao' => '4 Horas',
            'descricao' => 'Mergulhe no patrimônio histórico da terceira capital mais antiga do Brasil e saboreie a autêntica gastronomia paraibana.',
            'imagem' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1000&q=80',
            'orcamento' => 'R$ 60 - 120 por pessoa',
            'paradas' => [
                [
                    'ordem' => 1,
                    'atrativo_id' => 104,
                    'nome' => 'Centro Cultural São Francisco',
                    'tipo' => 'História',
                    'tempo' => '09:30 - 11:30',
                    'descricao' => 'Visita guiada pelo complexo arquitetônico barroco, azulejaria e pátio franciscano.',
                    'lat' => -7.1155,
                    'lng' => -34.8864
                ],
                [
                    'ordem' => 2,
                    'atrativo_id' => 105,
                    'nome' => 'Mangai João Pessoa',
                    'tipo' => 'Almoço Gastronômico',
                    'tempo' => '12:00 - 13:30',
                    'descricao' => 'Almoço farto com carne de sol na nata, baião de dois, tapiocas e sucos de frutas regionais.',
                    'lat' => -7.1067,
                    'lng' => -34.8315
                ]
            ]
        ],
        1 => [
            'id' => 1,
            'titulo' => 'Águas Cristalinas & Cavernas em Bonito',
            'cidade' => 'Bonito - MS',
            'duracao' => '1 a 2 Dias',
            'descricao' => 'O clássico de Bonito: flutuação nas águas azuis do Rio Sucuri e a lendária Gruta do Lago Azul.',
            'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
            'orcamento' => 'R$ 250 - 450 por pessoa (passeios guiados)',
            'paradas' => [
                [
                    'ordem' => 1,
                    'atrativo_id' => 1,
                    'nome' => 'Flutuação no Rio Sucuri',
                    'tipo' => 'Manhã',
                    'tempo' => '08:30 - 11:30',
                    'descricao' => 'Flutuação tranquila com colete e snorkel em uma das águas mais translúcidas do planeta.',
                    'lat' => -21.2642,
                    'lng' => -56.5516
                ],
                [
                    'ordem' => 2,
                    'atrativo_id' => 2,
                    'nome' => 'Gruta do Lago Azul',
                    'tipo' => 'Tarde',
                    'tempo' => '13:30 - 15:30',
                    'descricao' => 'Descida à caverna geológica para contemplar o monumento natural tombado pelo IPHAN.',
                    'lat' => -21.1469,
                    'lng' => -56.5861
                ],
                [
                    'ordem' => 3,
                    'atrativo_id' => 4,
                    'nome' => 'Casa do João',
                    'tipo' => 'Jantar',
                    'tempo' => '19:00 - 21:00',
                    'descricao' => 'Jantar com peixes típicos do Pantanal e ambiente arborizado com acessibilidade.',
                    'lat' => -21.1275,
                    'lng' => -56.4831
                ]
            ]
        ]
    ];

    $item = $roteiros[(int)($id ?? 101)] ?? $roteiros[101];
@endphp

@section('content')
<!-- Header flutuante com botão voltar e download offline -->
<div class="position-fixed top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center z-3" style="pointer-events: none;">
    <a href="{{ route('pwa.roteiros') }}" class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; pointer-events: auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
        <i class="bi bi-chevron-left text-dark fs-5"></i>
    </a>
    <button type="button" id="btn-save-offline-roteiro" class="btn btn-light rounded-pill shadow-sm d-flex align-items-center gap-1 px-3 py-2 fw-semibold small" style="pointer-events: auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
        <i class="bi bi-cloud-arrow-down-fill text-primary"></i> <span id="label-offline-btn">Salvar Offline</span>
    </button>
</div>

<div class="container-fluid px-3 pt-5 pb-5 mt-2 mb-5">
    <!-- Hero Image -->
    <div class="position-relative w-100 rounded-5 overflow-hidden shadow-sm mb-4" style="height: 260px; background-color: #f8f9fa;">
        <img src="{{ $item['imagem'] }}" class="w-100 h-100 object-fit-cover" alt="{{ $item['titulo'] }}">
        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);">
            <span class="badge bg-primary rounded-pill px-3 py-1 text-white fw-bold">{{ $item['cidade'] }}</span>
            <h1 class="text-white fw-bold fs-4 mb-0 mt-2">{{ $item['titulo'] }}</h1>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small d-block">Duração Sugerida</span>
                <strong class="text-dark"><i class="bi bi-clock-history text-warning me-1"></i> {{ $item['duracao'] }}</strong>
            </div>
            <div class="text-end">
                <span class="text-muted small d-block">Paradas Conectadas</span>
                <strong class="text-primary"><i class="bi bi-flag-fill me-1"></i> {{ count($item['paradas']) }} pontos</strong>
            </div>
        </div>
        <hr class="my-2">
        <p class="small text-secondary mb-2">{{ $item['descricao'] }}</p>
        <div class="small text-muted">
            <i class="bi bi-wallet2 text-success me-1"></i> <strong>Estimativa:</strong> {{ $item['orcamento'] ?? 'Entrada gratuita / Consumo livre' }}
        </div>
    </div>

    <!-- Mapa Interativo do Roteiro -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-0 pt-3 px-3 pb-1 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-map-fill text-primary me-1"></i> Trajeto no Mapa</h6>
            <span class="badge bg-light text-secondary border rounded-pill small">{{ count($item['paradas']) }} paradas</span>
        </div>
        <div class="p-3">
            <div id="mapa-roteiro" class="rounded-4 overflow-hidden" style="height: 240px; width: 100%;"></div>
        </div>
    </div>

    <!-- Timeline das Paradas -->
    <h2 class="fs-6 fw-bold mb-3"><i class="bi bi-list-ol text-primary me-1"></i> Itinerário Passo a Passo</h2>
    <div class="d-flex flex-column gap-3 mb-4">
        @foreach($item['paradas'] as $index => $p)
        <div class="card border-0 rounded-4 shadow-sm p-3 bg-white position-relative">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    {{ $p['ordem'] }}
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-primary border rounded-pill px-2 py-1 small" style="font-size: 0.68rem;">{{ $p['tipo'] }}</span>
                        <span class="text-muted small" style="font-size: 0.72rem;"><i class="bi bi-clock me-1"></i> {{ $p['tempo'] }}</span>
                    </div>
                    <h3 class="fs-6 fw-bold text-dark mt-1 mb-1">{{ $p['nome'] }}</h3>
                    <p class="small text-secondary mb-2" style="font-size: 0.82rem;">{{ $p['descricao'] }}</p>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('pwa.atrativo', $p['atrativo_id']) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-medium" style="font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i> Detalhes
                        </a>
                        <button onclick="window.LocationService ? window.LocationService.openDirections({{ $p['lat'] }}, {{ $p['lng'] }}, '{{ addslashes($p['nome']) }}') : window.open('https://maps.google.com/?q={{ $p['lat'] }},{{ $p['lng'] }}', '_blank')" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium" style="font-size: 0.75rem;">
                            <i class="bi bi-cursor-fill me-1"></i> Traçar Rota
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paradas = @json($item['paradas']);
    const roteiroId = {{ $item['id'] }};
    const roteiroData = @json($item);

    // 1. Inicializa o Mapa Leaflet
    const mapEl = document.getElementById('mapa-roteiro');
    if (mapEl && paradas.length > 0) {
        const map = L.map('mapa-roteiro', {
            zoomControl: false,
            scrollWheelZoom: false
        }).setView([paradas[0].lat, paradas[0].lng], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OSM'
        }).addTo(map);

        const latlngs = [];
        paradas.forEach((p, idx) => {
            const pos = [p.lat, p.lng];
            latlngs.push(pos);

            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center shadow" style="width: 28px; height: 28px; font-size: 0.8rem; border: 2px solid #fff;">${p.ordem}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            L.marker(pos, { icon: customIcon })
             .addTo(map)
             .bindPopup(`<strong>${p.ordem}. ${p.nome}</strong><br><small>${p.tempo}</small>`);
        });

        // Linha conectando as paradas
        if (latlngs.length > 1) {
            L.polyline(latlngs, {
                color: '#005f73',
                weight: 4,
                opacity: 0.8,
                dashArray: '6, 6'
            }).addTo(map);

            map.fitBounds(L.latLngBounds(latlngs), { padding: [30, 30] });
        }
    }

    // 2. Modo Viagem Offline (Download do Roteiro)
    const btnOffline = document.getElementById('btn-save-offline-roteiro');
    const labelOffline = document.getElementById('label-offline-btn');

    // Verifica se já está salvo
    try {
        const saved = JSON.parse(localStorage.getItem('saved_offline_roteiros') || '{}');
        if (saved[roteiroId]) {
            btnOffline.classList.add('btn-success', 'text-white');
            btnOffline.classList.remove('btn-light');
            btnOffline.innerHTML = '<i class="bi bi-check2-circle"></i> Disponível Offline';
        }
    } catch(e) {}

    btnOffline?.addEventListener('click', function() {
        try {
            const saved = JSON.parse(localStorage.getItem('saved_offline_roteiros') || '{}');
            if (saved[roteiroId]) {
                delete saved[roteiroId];
                localStorage.setItem('saved_offline_roteiros', JSON.stringify(saved));
                btnOffline.classList.remove('btn-success', 'text-white');
                btnOffline.classList.add('btn-light');
                btnOffline.innerHTML = '<i class="bi bi-cloud-arrow-down-fill text-primary"></i> Salvar Offline';
                alert('Roteiro removido do armazenamento offline.');
            } else {
                saved[roteiroId] = {
                    ...roteiroData,
                    saved_at: new Date().toISOString()
                };
                localStorage.setItem('saved_offline_roteiros', JSON.stringify(saved));
                btnOffline.classList.add('btn-success', 'text-white');
                btnOffline.classList.remove('btn-light');
                btnOffline.innerHTML = '<i class="bi bi-check2-circle"></i> Disponível Offline';
                alert('✓ Roteiro e dados baixados com sucesso! Disponível mesmo sem internet.');
            }
        } catch(e) {
            console.error(e);
        }
    });
});
</script>
@endpush
