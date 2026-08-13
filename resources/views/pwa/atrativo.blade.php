@extends('layouts.pwa')

@php
    $catalog = [
        101 => [
            'id' => 101,
            'nome' => 'Praia de Tambaú',
            'categoria' => 'Praias & Rios',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'Av. Almirante Tamandaré, Tambaú, João Pessoa - PB',
            'descricao' => 'Uma das praias urbanas mais famosas de João Pessoa, com calçadão movimentado, feirinha de artesanato e águas calmas e mornas.',
            'historia' => 'O coração do litoral de João Pessoa, ponto de embarque tradicional para passeios de catamarã às piscinas de Picãozinho.',
            'imagem' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '180 min',
            'preco' => 'Gratuito',
            'rating' => '4.9',
            'lat' => -7.1147,
            'lng' => -34.8239,
            'acessibilidade' => ['Rampa de Acesso à Praia', 'Cadeirantes', 'Deficiência Auditiva']
        ],
        102 => [
            'id' => 102,
            'nome' => 'Farol do Cabo Branco',
            'categoria' => 'Monumentos & Natureza',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'Ponta do Seixas, Cabo Branco, João Pessoa - PB',
            'descricao' => 'O ponto mais oriental das Américas continentais onde o sol nasce primeiro. Vista panorâmica inesquecível do oceano e falésias.',
            'historia' => 'Inaugurado em 1972, sua forma triangular única representa uma planta de sisal, símbolo da história econômica paraibana.',
            'imagem' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '60 min',
            'preco' => 'Gratuito',
            'rating' => '4.8',
            'lat' => -7.1477,
            'lng' => -34.7963,
            'acessibilidade' => ['Cadeirantes', 'Estacionamento Próximo']
        ],
        103 => [
            'id' => 103,
            'nome' => 'Piscinas Naturais dos Seixas',
            'categoria' => 'Praias & Rios',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'Praia dos Seixas, João Pessoa - PB',
            'descricao' => 'Recifes de corais que formam piscinas transparentes de água morna na maré baixa, ideais para flutuação com peixes coloridos.',
            'historia' => 'Área de preservação ambiental com passeios sustentáveis autorizados pela capitania dos portos.',
            'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '150 min',
            'preco' => 'R$ 70,00 (catamarã)',
            'rating' => '4.9',
            'lat' => -7.1597,
            'lng' => -34.7877,
            'acessibilidade' => ['Coletes Salva-vidas Inclusivos', 'Deficiência Auditiva']
        ],
        104 => [
            'id' => 104,
            'nome' => 'Centro Cultural São Francisco',
            'categoria' => 'História & Cultura',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'Praça São Francisco, Centro Histórico, João Pessoa - PB',
            'descricao' => 'Um dos mais expressivos monumentos da arquitetura barroca no Brasil, com igreja, convento franciscano e azulejos portugueses.',
            'historia' => 'Construção iniciada no século XVI, tombada pelo Patrimônio Histórico e Artístico Nacional (IPHAN).',
            'imagem' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '90 min',
            'preco' => 'R$ 12,00',
            'rating' => '4.9',
            'lat' => -7.1155,
            'lng' => -34.8864,
            'acessibilidade' => ['Cadeirantes', 'Guia Local', 'Auditivo']
        ],
        105 => [
            'id' => 105,
            'nome' => 'Mangai João Pessoa',
            'categoria' => 'Gastronomia Regional',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'Av. Edson Ramalho, 696, Manaíra, João Pessoa - PB',
            'descricao' => 'O restaurante mais consagrado da Paraíba para saborear a verdadeira culinária nordestina: carne de sol, baião de dois, tapiocas e doces artesanais.',
            'historia' => 'Nascido em João Pessoa como um mercadinho regional, virou referência gastronômica em todo o país.',
            'imagem' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '90 min',
            'preco' => 'Buffet / kg',
            'rating' => '4.9',
            'lat' => -7.1067,
            'lng' => -34.8315,
            'acessibilidade' => ['Cadeirantes', 'Cardápio em Braille', 'Acessibilidade Completa']
        ],
        106 => [
            'id' => 106,
            'nome' => 'Nau Frutos do Mar',
            'categoria' => 'Gastronomia Regional',
            'cidade' => 'João Pessoa - PB',
            'endereco' => 'R. Lupércio Branco, 130, Manaíra, João Pessoa - PB',
            'descricao' => 'Culinária refinada com frutos do mar frescos, camarões generosos e ambiente moderno e sofisticado.',
            'historia' => 'Marca paraibana premiada internacionalmente por sua excelência em frutos do mar.',
            'imagem' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=80',
            'duracao' => '90 min',
            'preco' => 'À la carte',
            'rating' => '4.8',
            'lat' => -7.1189,
            'lng' => -34.8302,
            'acessibilidade' => ['Cadeirantes', 'Ambiente Climatizado Acessível']
        ],
        1 => [
            'id' => 1,
            'nome' => 'Flutuação no Rio Sucuri',
            'categoria' => 'Praias & Rios',
            'cidade' => 'Bonito - MS',
            'endereco' => 'Fazenda São Geraldo, Rodovia Bonito - São Geraldo',
            'descricao' => 'Uma das águas mais cristalinas do mundo. Flutuação tranquila em meio a muita vida subaquática e vegetação exuberante.',
            'historia' => 'O Rio Sucuri é famoso por sua nascente e pela visibilidade inacreditável da água, resultado da alta concentração de calcário.',
            'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'duracao' => '120 min',
            'preco' => 'R$ 290,00',
            'rating' => '4.9',
            'lat' => -21.2642,
            'lng' => -56.5516,
            'acessibilidade' => ['Cadeirantes', 'Deficiência Auditiva']
        ],
        2 => [
            'id' => 2,
            'nome' => 'Gruta do Lago Azul',
            'categoria' => 'Monumentos & Natureza',
            'cidade' => 'Bonito - MS',
            'endereco' => 'Rodovia MS 382, Km 20',
            'descricao' => 'Cartão postal de Bonito, uma caverna com um lago subterrâneo de coloração azul intensa.',
            'historia' => 'Descoberta em 1924 por índios Terena, a gruta é um monumento natural tombado pelo IPHAN.',
            'imagem' => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'duracao' => '90 min',
            'preco' => 'R$ 150,00',
            'rating' => '4.8',
            'lat' => -21.1469,
            'lng' => -56.5861,
            'acessibilidade' => ['Escadas com Corrimão']
        ],
        3 => [
            'id' => 3,
            'nome' => 'Bóia Cross no Rio Formoso',
            'categoria' => 'Aventura & Trilhas',
            'cidade' => 'Bonito - MS',
            'endereco' => 'Parque Ecológico Rio Formoso',
            'descricao' => 'Aventura em bóias individuais por corredeiras refrescantes no Rio Formoso.',
            'historia' => 'Atividade tradicional que mistura emoção e contato com a natureza.',
            'imagem' => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'duracao' => '60 min',
            'preco' => 'R$ 130,00',
            'rating' => '4.7',
            'lat' => -21.1895,
            'lng' => -56.4523,
            'acessibilidade' => ['Deficiência Auditiva']
        ],
        4 => [
            'id' => 4,
            'nome' => 'Casa do João',
            'categoria' => 'Gastronomia Regional',
            'cidade' => 'Bonito - MS',
            'endereco' => 'Rua Cel. Nélson Felício dos Santos, Centro',
            'descricao' => 'Um dos restaurantes mais famosos da região, conhecido por seus pratos com peixes locais como Pintado e Pacu.',
            'historia' => 'Fundado pela família de Seu João, virou ponto de encontro obrigatório para os turistas em Bonito.',
            'imagem' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'duracao' => '120 min',
            'preco' => 'R$ 95,00',
            'rating' => '4.9',
            'lat' => -21.1275,
            'lng' => -56.4831,
            'acessibilidade' => ['Cadeirantes', 'Cego']
        ]
    ];

    $item = $catalog[(int)($id ?? 101)] ?? $catalog[101];
@endphp

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<!-- Header flutuante -->
<div class="position-fixed top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center z-3" style="pointer-events: none;">
    <a href="{{ route('pwa.explorar') }}" class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; pointer-events: auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
        <i class="bi bi-chevron-left text-dark fs-5"></i>
    </a>
    
    <div class="d-flex gap-2" style="pointer-events: auto;">
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-heart text-danger fs-5"></i>
        </button>
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0" id="btn-share" style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-share text-dark fs-5"></i>
        </button>
    </div>
</div>

<div class="container-fluid px-3 pt-5 pb-5 mt-2 mb-5">
    
    <!-- Hero Image Card -->
    <div class="position-relative w-100 rounded-5 overflow-hidden shadow-sm mb-4" style="height: 320px; background-color: #f8f9fa;">
        <img src="{{ $item['imagem'] }}" class="w-100 h-100 object-fit-cover" alt="{{ $item['nome'] }}">
        
        <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex justify-content-between align-items-end" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
            <span class="badge rounded-pill px-3 py-2 fw-bold" style="backdrop-filter: blur(5px); background-color: rgba(0, 95, 115, 0.85) !important; color: white;">
                {{ $item['categoria'] }}
            </span>
            <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2 text-white border border-secondary" id="atrativo-distance-pill">
                <i class="bi bi-geo-alt-fill text-warning"></i> Calculando distância...
            </span>
        </div>
    </div>

    <!-- Title & Rating -->
    <div class="d-flex justify-content-between align-items-start mb-3 px-1">
        <div>
            <h1 class="fw-bolder text-dark mb-1" style="font-size: 1.85rem; letter-spacing: -0.03em; line-height: 1.15;">{{ $item['nome'] }}</h1>
            <p class="text-secondary small mb-0 d-flex align-items-center gap-1 mt-2">
                <i class="bi bi-geo-alt-fill text-primary"></i> {{ $item['endereco'] }}
            </p>
        </div>
        <div class="bg-warning text-dark rounded-4 p-2 d-flex flex-column align-items-center justify-content-center shadow-sm flex-shrink-0 ms-2" style="min-width: 58px; height: 58px;">
            <i class="bi bi-star-fill small"></i>
            <span class="fw-bolder fs-5 lh-1 mt-1">{{ $item['rating'] }}</span>
        </div>
    </div>

    <!-- Distância e Banner de Rota Direta -->
    <div class="card border-0 rounded-4 p-3 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="small opacity-75">Sua Proximidade</div>
                <div class="fw-bold fs-5" id="atrativo-distance-text">Calculando...</div>
                <div class="small opacity-75" id="atrativo-eta-text">Baseado no seu GPS</div>
            </div>
            <button class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-1" id="btn-open-route-modal">
                <i class="bi bi-cursor-fill"></i> Traçar Rota
            </button>
        </div>
    </div>

    <!-- About Section -->
    <div class="mb-4 px-1">
        <h2 class="fs-6 fw-bold text-dark mb-2">Sobre o Local</h2>
        <p class="text-secondary mb-2" style="font-size: 0.95rem; line-height: 1.6;">
            {{ $item['descricao'] }}
        </p>
        @if(!empty($item['historia']))
            <p class="text-secondary small fst-italic" style="font-size: 0.85rem; line-height: 1.5;">
                {{ $item['historia'] }}
            </p>
        @endif
    </div>

    <!-- BENTO GRID -->
    <div class="row g-3 mb-5 pb-5">
        <!-- Duration / Price -->
        <div class="col-6 d-flex flex-column gap-3">
            <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm h-50">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(10, 147, 150, 0.15); color: #0a9396;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase;">Duração</div>
                    <div class="fw-bolder text-dark" style="font-size: 0.9rem;">{{ $item['duracao'] }}</div>
                </div>
            </div>
            
            <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm h-50">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: rgba(238, 155, 0, 0.15); color: #ee9b00;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase;">Preço</div>
                    <div class="fw-bolder text-dark" style="font-size: 0.85rem;">{{ $item['preco'] }}</div>
                </div>
            </div>
        </div>

        <!-- Map / Location block -->
        <div class="col-6">
            <div id="map-container" class="rounded-4 overflow-hidden position-relative border shadow-sm h-100" style="min-height: 150px; background-color: #e9ecef; cursor: pointer;">
                <div id="map" class="w-100 h-100 position-absolute top-0 start-0"></div>
                
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2 text-center w-100 px-2" style="pointer-events: none; z-index: 1000;">
                    <span class="badge bg-dark bg-opacity-75 text-white shadow-sm border border-secondary px-3 py-2 rounded-pill" style="font-size: 0.7rem; backdrop-filter: blur(4px);">
                        <i class="bi bi-cursor-fill text-warning me-1"></i> Abrir Navegação
                    </span>
                </div>
            </div>
        </div>

        <!-- Accessibility -->
        <div class="col-12">
            <div class="bg-light rounded-4 p-3 border shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-universal-access-circle text-primary fs-4"></i>
                    <span class="fw-bold text-dark fs-6">Acessibilidade e Inclusão</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($item['acessibilidade'] as $ac)
                    <span class="badge rounded-pill fw-medium d-flex align-items-center gap-1 px-3 py-2 border text-dark bg-white shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-check-circle-fill text-success"></i> {{ $ac }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Modal Escolher Aplicativo de Navegação -->
<div class="modal fade" id="modal-route-options" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Como deseja chegar?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="small text-secondary mb-3">
                    Inicie a navegação direta de onde você está até <strong>{{ $item['nome'] }}</strong>:
                </p>

                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn" data-provider="google">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-google text-danger fs-4"></i>
                            <div>
                                <div class="fw-bold">Google Maps</div>
                                <div class="small text-secondary">Traçar rota com trânsito em tempo real</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>

                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn" data-provider="waze">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-compass text-info fs-4"></i>
                            <div>
                                <div class="fw-bold">Waze</div>
                                <div class="small text-secondary">Alertas de radares e tráfego</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>

                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn" data-provider="osm">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-map-fill text-success fs-4"></i>
                            <div>
                                <div class="fw-bold">OpenStreetMap (OSRM)</div>
                                <div class="small text-secondary">Rotas abertas e sustentáveis</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
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
        const destLat = {{ $item['lat'] }};
        const destLng = {{ $item['lng'] }};
        const destName = "{{ addslashes($item['nome']) }}";

        // Inicializa Mapa Leaflet
        const map = L.map('map', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false
        }).setView([destLat, destLng], 14);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        const destIcon = L.divIcon({
            html: '<div style="margin-top:-15px; margin-left:-7px;"><i class="bi bi-geo-alt-fill text-danger fs-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i></div>',
            className: 'custom-div-icon',
            iconSize: [30, 30]
        });
        
        L.marker([destLat, destLng], { icon: destIcon }).addTo(map);

        // Atualiza distância baseada no GPS do usuário
        function updateDistance() {
            const saved = window.LocationService ? window.LocationService.getSavedLocation() : null;
            if (saved && saved.lat && saved.lng && window.LocationService) {
                const uLat = parseFloat(saved.lat);
                const uLng = parseFloat(saved.lng);
                const distKm = window.LocationService.calculateDistanceKm(uLat, uLng, destLat, destLng);
                const distFormatted = window.LocationService.formatDistance(distKm);

                document.getElementById('atrativo-distance-pill').innerHTML = `<i class="bi bi-geo-alt-fill text-warning"></i> ${distFormatted} de você`;
                document.getElementById('atrativo-distance-text').textContent = `${distFormatted} de você`;

                // Estimativa de tempo de carro
                const minutesCar = Math.max(2, Math.round(distKm / 30 * 60));
                document.getElementById('atrativo-eta-text').textContent = `Aproximadamente ${minutesCar} min de carro`;

                // Adiciona marcador do usuário no mini mapa
                const userIcon = L.divIcon({
                    html: '<div style="width: 14px; height: 14px; background-color: #0077b6; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 8px rgba(0,119,182,0.8);"></div>',
                    className: 'user-gps-icon',
                    iconSize: [14, 14]
                });
                L.marker([uLat, uLng], { icon: userIcon }).addTo(map);

                // Ajusta visualização para enquadrar ambos os pontos
                const bounds = L.latLngBounds([[uLat, uLng], [destLat, destLng]]);
                map.fitBounds(bounds, { padding: [20, 20], maxZoom: 15 });
            } else {
                document.getElementById('atrativo-distance-pill').innerHTML = `<i class="bi bi-geo-alt text-warning"></i> ${destName}`;
                document.getElementById('atrativo-distance-text').textContent = 'Localize-se para ver distância';
            }
        }

        updateDistance();
        window.addEventListener('turismo:location-changed', updateDistance);

        // Modal de Rotas
        const routeModal = new bootstrap.Modal(document.getElementById('modal-route-options'));
        
        document.getElementById('btn-open-route-modal')?.addEventListener('click', () => routeModal.show());
        document.getElementById('map-container')?.addEventListener('click', () => routeModal.show());

        document.querySelectorAll('.nav-app-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const provider = this.getAttribute('data-provider');
                if (window.LocationService) {
                    const url = window.LocationService.getDirectionsUrl(destLat, destLng, provider, destName);
                    window.open(url, '_blank');
                } else {
                    window.open(`https://maps.google.com/?q=${destLat},${destLng}`, '_blank');
                }
                routeModal.hide();
            });
        });

        // Compartilhar
        document.getElementById('btn-share')?.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: destName,
                    text: `Conheça ${destName} no Guia de Turismo!`,
                    url: window.location.href
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copiado para a área de transferência!');
            }
        });
    });
</script>
@endpush

