@extends('layouts.pwa')

{{--
    Página de detalhe de um Atrativo.
    Os dados vêm do AtrativoWebController, que carrega o Model real do banco de dados
    com as relações: categoria, municipio, midias.
--}}

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')

{{-- Header flutuante com botão Voltar, Favoritar e Compartilhar --}}
<div class="position-fixed top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center z-3"
     style="pointer-events: none;">
    <a href="{{ route('pwa.explorar') }}"
       class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0"
       style="width: 44px; height: 44px; pointer-events: auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
        <i class="bi bi-chevron-left text-dark fs-5"></i>
    </a>

    <div class="d-flex gap-2" style="pointer-events: auto;">
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0"
                style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-heart text-danger fs-5"></i>
        </button>
        <button class="btn btn-light rounded-circle shadow-sm d-flex justify-content-center align-items-center p-0"
                id="btn-share"
                style="width: 44px; height: 44px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
            <i class="bi bi-share text-dark fs-5"></i>
        </button>
    </div>
</div>

<div class="container-fluid px-3 pt-5 pb-5 mt-2 mb-5">

    {{-- Hero Image --}}
    <div class="position-relative w-100 rounded-5 overflow-hidden shadow-sm mb-4" style="height: 320px;">
        @if ($imagemPrincipal)
            <img src="{{ $imagemPrincipal }}"
                 class="w-100 h-100 object-fit-cover"
                 alt="{{ $atrativo->nome }}">
        @else
            {{--
                Fallback: imagem do Unsplash baseada na categoria.
                Será substituída quando mídias reais forem cadastradas no admin.
            --}}
            @php
                $fallbackImages = [
                    'rios'        => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
                    'aventura'    => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=1000&q=80',
                    'grutas'      => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=1000&q=80',
                    'gastronomia' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80',
                    'hospedagem'  => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1000&q=80',
                    'cultura'     => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1000&q=80',
                ];
                $slug = $atrativo->categoria?->slug ?? '';
                $heroImg = $fallbackImages[$slug] ?? 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1000&q=80';
            @endphp
            <img src="{{ $heroImg }}"
                 class="w-100 h-100 object-fit-cover"
                 alt="{{ $atrativo->nome }}">
        @endif

        {{-- Overlay com categoria e distância --}}
        <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex justify-content-between align-items-end"
             style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
            <span class="badge rounded-pill px-3 py-2 fw-bold"
                  style="backdrop-filter: blur(5px); background-color: rgba(0, 95, 115, 0.85); color: white;">
                {{ $atrativo->categoria?->nome ?? 'Atrativo' }}
            </span>
            <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2 text-white border border-secondary"
                  id="atrativo-distance-pill">
                <i class="bi bi-geo-alt-fill text-warning"></i> Calculando distância...
            </span>
        </div>
    </div>

    {{-- Título, endereço e avaliação --}}
    <div class="d-flex justify-content-between align-items-start mb-3 px-1">
        <div class="flex-grow-1 pe-2">
            <h1 class="fw-bolder text-dark mb-1"
                style="font-size: 1.85rem; letter-spacing: -0.03em; line-height: 1.15;">
                {{ $atrativo->nome }}
            </h1>
            <p class="text-secondary small mb-0 d-flex align-items-center gap-1 mt-2">
                <i class="bi bi-geo-alt-fill text-primary"></i>
                {{ $atrativo->endereco ?? ($atrativo->municipio ? $atrativo->municipio->nome . ' - ' . $atrativo->municipio->uf : 'Endereço não informado') }}
            </p>
        </div>

        @if ($mediaAvaliacao)
            <div class="bg-warning text-dark rounded-4 p-2 d-flex flex-column align-items-center justify-content-center shadow-sm flex-shrink-0 ms-2"
                 style="min-width: 58px; height: 58px;">
                <i class="bi bi-star-fill small"></i>
                <span class="fw-bolder fs-5 lh-1 mt-1">{{ $mediaAvaliacao }}</span>
            </div>
        @endif
    </div>

    {{-- Banner de rota / distância --}}
    <div class="card border-0 rounded-4 p-3 mb-4 text-white shadow-sm"
         style="background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="small opacity-75">Sua Proximidade</div>
                <div class="fw-bold fs-5" id="atrativo-distance-text">Calculando...</div>
                <div class="small opacity-75" id="atrativo-eta-text">Baseado no seu GPS</div>
            </div>
            <button class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-1"
                    id="btn-open-route-modal">
                <i class="bi bi-cursor-fill"></i> Traçar Rota
            </button>
        </div>
    </div>

    {{-- Sobre o local --}}
    <div class="mb-4 px-1">
        <h2 class="fs-6 fw-bold text-dark mb-2">Sobre o Local</h2>
        <p class="text-secondary mb-2" style="font-size: 0.95rem; line-height: 1.6;">
            {{ $atrativo->descricao }}
        </p>
        @if ($atrativo->historia)
            <p class="text-secondary small fst-italic" style="font-size: 0.85rem; line-height: 1.5;">
                {{ $atrativo->historia }}
            </p>
        @endif

        {{-- Audiodescrição (WCAG / Acessibilidade) --}}
        <div class="card border-0 rounded-4 bg-light p-3 shadow-sm mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                        <i class="bi bi-volume-up-fill fs-5" id="audio-play-icon"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small">Guia por Voz & Audiodescrição</div>
                        <div class="text-muted" style="font-size: 0.72rem;">Ouça a história e detalhes do local</div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary rounded-pill btn-sm px-3 fw-bold" id="btn-toggle-audio-guide">
                    <i class="bi bi-play-fill me-1"></i> <span id="label-audio-guide">Ouvir</span>
                </button>
            </div>
        </div>

        {{-- Experiência Imersiva 360° (Piloto do PRD) --}}
        <div class="card border-0 rounded-4 p-3 shadow-sm mb-3 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #0a9396 0%, #005f73 100%);">
            <div class="d-flex align-items-center justify-content-between position-relative z-1">
                <div>
                    <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-2.5 py-0.5 small mb-1">
                        <i class="bi bi-view-360 me-1"></i> Tour Virtual Piloto
                    </span>
                    <h6 class="fw-bold text-white mb-0">Vista Panorâmica 360°</h6>
                </div>
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTour360">
                    <i class="bi bi-badge-vr me-1 text-primary"></i> Explorar 360°
                </button>
            </div>
        </div>
    </div>

    {{-- Bento Grid: Duração, Preço, Mapa --}}
    <div class="row g-3 mb-4">

        {{-- Duração e Preço (coluna esquerda) --}}
        <div class="col-6 d-flex flex-column gap-3">

            @if ($atrativo->tempo_medio_visita)
                @php
                    $min = $atrativo->tempo_medio_visita;
                    $duracaoStr = $min >= 60
                        ? floor($min / 60) . 'h' . ($min % 60 > 0 ? ' ' . $min % 60 . 'min' : '')
                        : $min . ' min';
                @endphp
                <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 40px; height: 40px; background-color: rgba(10, 147, 150, 0.15); color: #0a9396;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase;">Duração</div>
                        <div class="fw-bolder text-dark" style="font-size: 0.9rem;">{{ $duracaoStr }}</div>
                    </div>
                </div>
            @endif

            {{-- Preço: exibe o primeiro item do JSON ou "Consultar" --}}
            @php
                $precos = $atrativo->precos ?? [];
                $precoLabel = 'Consultar';
                if (!empty($precos)) {
                    $primeiro = is_array($precos) ? reset($precos) : $precos;
                    $precoLabel = is_array($primeiro)
                        ? ($primeiro['valor'] ?? 'Consultar')
                        : $primeiro;
                }
            @endphp
            <div class="bg-light rounded-4 p-3 d-flex align-items-center gap-3 border shadow-sm">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 40px; height: 40px; background-color: rgba(238, 155, 0, 0.15); color: #ee9b00;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold" style="font-size: 0.60rem; text-transform: uppercase;">Preço</div>
                    <div class="fw-bolder text-dark" style="font-size: 0.85rem;">{{ $precoLabel }}</div>
                </div>
            </div>
        </div>

        {{-- Mini Mapa (coluna direita) --}}
        @if ($atrativo->lat && $atrativo->lng)
            <div class="col-6">
                <div id="map-container"
                     class="rounded-4 overflow-hidden position-relative border shadow-sm"
                     style="min-height: 160px; height: 100%; background-color: #e9ecef; cursor: pointer;">
                    <div id="map" class="w-100 h-100 position-absolute top-0 start-0"></div>
                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2 text-center w-100 px-2"
                         style="pointer-events: none; z-index: 1000;">
                        <span class="badge bg-dark bg-opacity-75 text-white shadow-sm border border-secondary px-3 py-2 rounded-pill"
                              style="font-size: 0.7rem; backdrop-filter: blur(4px);">
                            <i class="bi bi-cursor-fill text-warning me-1"></i>Abrir Navegação
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Horários de funcionamento (se existirem) --}}
    @if (!empty($atrativo->horarios))
        <div class="mb-4">
            <h2 class="fs-6 fw-bold text-dark mb-3">
                <i class="bi bi-door-open me-1 text-primary"></i>Horário de Funcionamento
            </h2>
            <div class="bg-light rounded-4 p-3 border shadow-sm">
                @php
                    $diasPt = ['seg' => 'Segunda', 'ter' => 'Terça', 'qua' => 'Quarta', 'qui' => 'Quinta',
                               'sex' => 'Sexta', 'sab' => 'Sábado', 'dom' => 'Domingo'];
                @endphp
                @foreach ($atrativo->horarios as $dia => $horario)
                    <div class="d-flex justify-content-between py-1 border-bottom last-border-none">
                        <span class="small fw-semibold">{{ $diasPt[$dia] ?? ucfirst($dia) }}</span>
                        <span class="small text-secondary">{{ $horario }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Acessibilidade --}}
    @if (!empty($atrativo->acessibilidade))
        <div class="mb-4">
            <h2 class="fs-6 fw-bold text-dark mb-3">
                <i class="bi bi-universal-access-circle me-1 text-primary"></i>Acessibilidade e Inclusão
            </h2>
            <div class="bg-light rounded-4 p-3 border shadow-sm">
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $labelAcessivel = [
                            'cadeirante'       => '♿ Cadeirante',
                            'libras'           => '🤟 Libras',
                            'cego'             => '🦯 Deficiente Visual',
                            'deficiencia_auditiva' => '🔇 Deficiente Auditivo',
                        ];
                    @endphp
                    @foreach ($atrativo->acessibilidade as $ac)
                        <span class="badge rounded-pill fw-medium d-flex align-items-center gap-1 px-3 py-2 border text-dark bg-white shadow-sm"
                              style="font-size: 0.75rem;">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            {{ $labelAcessivel[$ac] ?? ucfirst(str_replace('_', ' ', $ac)) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Restrições / Segurança --}}
    @if ($atrativo->restricoes || $atrativo->seguranca)
        <div class="mb-4">
            <h2 class="fs-6 fw-bold text-dark mb-3">
                <i class="bi bi-shield-exclamation me-1 text-warning"></i>Orientações
            </h2>
            @if ($atrativo->restricoes)
                <div class="alert alert-warning rounded-4 small mb-2 py-2">
                    <strong>Restrições:</strong> {{ $atrativo->restricoes }}
                </div>
            @endif
            @if ($atrativo->seguranca)
                <div class="alert alert-info rounded-4 small mb-0 py-2">
                    <strong>Segurança:</strong> {{ $atrativo->seguranca }}
                </div>
            @endif
        </div>
    @endif

    {{-- Avaliações --}}
    @if ($totalAvaliacoes > 0)
        <div class="mb-4">
            <h2 class="fs-6 fw-bold text-dark mb-3">
                <i class="bi bi-star-fill me-1 text-warning"></i>Avaliações
            </h2>
            <div class="bg-light rounded-4 p-3 border shadow-sm d-flex align-items-center gap-3">
                <div class="text-center">
                    <div class="fw-bolder text-dark" style="font-size: 2.5rem; line-height: 1;">{{ $mediaAvaliacao }}</div>
                    <div class="text-warning small">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($mediaAvaliacao) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $totalAvaliacoes }} avaliações</div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Modal: Escolher app de navegação --}}
<div class="modal fade" id="modal-route-options" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 border-0 shadow-lg p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Como deseja chegar?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="small text-secondary mb-3">
                    Navegue até <strong>{{ $atrativo->nome }}</strong> usando:
                </p>
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn"
                            data-provider="google">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-google text-danger fs-4"></i>
                            <div>
                                <div class="fw-bold">Google Maps</div>
                                <div class="small text-secondary">Rota com trânsito em tempo real</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn"
                            data-provider="waze">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-compass text-info fs-4"></i>
                            <div>
                                <div class="fw-bold">Waze</div>
                                <div class="small text-secondary">Alertas de radares e tráfego</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                    <button class="btn btn-light border rounded-4 p-3 d-flex align-items-center justify-content-between text-start nav-app-btn"
                            data-provider="osm">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-map-fill text-success fs-4"></i>
                            <div>
                                <div class="fw-bold">OpenStreetMap</div>
                                <div class="small text-secondary">Rotas abertas e gratuitas</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Visualizador Panorâmico 360° (Piloto) --}}
<div class="modal fade" id="modalTour360" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-5 border-0 shadow-lg overflow-hidden bg-dark text-white">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-view-360 me-2 text-warning"></i> Tour Virtual 360°</h5>
                    <span class="small text-white-50">{{ $atrativo->nome }}</span>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="position-relative rounded-4 overflow-hidden" style="height: 380px; background: #000;">
                    <img src="{{ $imagemPrincipal ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80' }}" id="img-panorama-360" class="w-100 h-100 object-fit-cover" style="cursor: grab; filter: brightness(0.95);" alt="Panorâmica 360">
                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 text-center w-100 px-3" style="pointer-events: none;">
                        <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-1.5 small border border-secondary" style="backdrop-filter: blur(5px);">
                            <i class="bi bi-arrows-move me-1 text-warning"></i> Arraste para girar a visão 360°
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-3 px-4 text-center justify-content-center">
                <span class="small text-white-50" style="font-size: 0.72rem;">* Experiência piloto de realidade virtual e imersão turística.</span>
            </div>
        </div>
    </div>
</div>

{{-- FAB: Adicionar ao Roteiro --}}
<div class="position-fixed start-50 translate-middle-x w-100 px-4 z-3 d-flex justify-content-center"
     style="bottom: 80px; max-width: 400px; pointer-events: none;">
    <button class="btn fw-bold py-3 px-4 rounded-pill shadow-lg d-flex align-items-center justify-content-center gap-2 text-white w-100"
            style="background-color: rgba(0, 95, 115, 0.95); font-size: 1.1rem; backdrop-filter: blur(10px); pointer-events: auto; border: 2px solid rgba(255,255,255,0.1);">
        <i class="bi bi-plus-circle-fill fs-4 text-warning"></i>
        <span>Adicionar ao Roteiro</span>
    </button>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Coordenadas e nome do atrativo passados pelo controller
    @if ($atrativo->lat && $atrativo->lng)
    const destLat  = {{ $atrativo->lat }};
    const destLng  = {{ $atrativo->lng }};
    const destName = @json($atrativo->nome);

    // =========================================================================
    // MINI MAPA LEAFLET
    // =========================================================================
    const map = L.map('map', {
        zoomControl: false,
        dragging: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
    }).setView([destLat, destLng], 14);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap',
    }).addTo(map);

    const destIcon = L.divIcon({
        html: '<div style="margin-top:-15px; margin-left:-7px;"><i class="bi bi-geo-alt-fill text-danger fs-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i></div>',
        className: 'custom-div-icon',
        iconSize: [30, 30],
    });
    L.marker([destLat, destLng], { icon: destIcon }).addTo(map);

    // =========================================================================
    // DISTÂNCIA DINÂMICA (GPS)
    // =========================================================================
    function updateDistance() {
        const saved = window.LocationService ? window.LocationService.getSavedLocation() : null;
        if (saved?.lat && saved?.lng && window.LocationService) {
            const uLat   = parseFloat(saved.lat);
            const uLng   = parseFloat(saved.lng);
            const distKm = window.LocationService.calculateDistanceKm(uLat, uLng, destLat, destLng);
            const distFmt = window.LocationService.formatDistance(distKm);

            document.getElementById('atrativo-distance-pill').innerHTML =
                `<i class="bi bi-geo-alt-fill text-warning"></i> ${distFmt} de você`;
            document.getElementById('atrativo-distance-text').textContent = `${distFmt} de você`;

            const minCar = Math.max(2, Math.round(distKm / 30 * 60));
            document.getElementById('atrativo-eta-text').textContent =
                `Aproximadamente ${minCar} min de carro`;

            // Marcador do usuário no mini mapa
            const userIcon = L.divIcon({
                html: '<div style="width:14px;height:14px;background:#0077b6;border:2px solid white;border-radius:50%;box-shadow:0 0 8px rgba(0,119,182,0.8);"></div>',
                className: 'user-gps-icon',
                iconSize: [14, 14],
            });
            L.marker([uLat, uLng], { icon: userIcon }).addTo(map);

            const bounds = L.latLngBounds([[uLat, uLng], [destLat, destLng]]);
            map.fitBounds(bounds, { padding: [20, 20], maxZoom: 15 });
        } else {
            document.getElementById('atrativo-distance-pill').innerHTML =
                `<i class="bi bi-geo-alt text-warning"></i> ${destName}`;
            document.getElementById('atrativo-distance-text').textContent =
                'Ative o GPS para ver a distância';
        }
    }

    updateDistance();
    window.addEventListener('turismo:location-changed', updateDistance);

    // =========================================================================
    // MODAL DE NAVEGAÇÃO
    // =========================================================================
    const routeModal = new bootstrap.Modal(document.getElementById('modal-route-options'));

    document.getElementById('btn-open-route-modal')?.addEventListener('click', () => routeModal.show());
    document.getElementById('map-container')?.addEventListener('click', () => routeModal.show());

    document.querySelectorAll('.nav-app-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const provider = this.getAttribute('data-provider');
            const url = window.LocationService
                ? window.LocationService.getDirectionsUrl(destLat, destLng, provider, destName)
                : `https://maps.google.com/?q=${destLat},${destLng}`;
            window.open(url, '_blank');
            routeModal.hide();
        });
    });
    @endif

    // =========================================================================
    // COMPARTILHAR
    // =========================================================================
    document.getElementById('btn-share')?.addEventListener('click', function () {
        if (navigator.share) {
            navigator.share({
                title: @json($atrativo->nome),
                text: `Conheça {{ $atrativo->nome }} no Guia de Turismo!`,
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copiado para a área de transferência!');
        }
    });

    // =========================================================================
    // AUDIODESCRIÇÃO E GUIA POR VOZ (WCAG / SPEECH SYNTHESIS)
    // =========================================================================
    let isPlayingAudio = false;
    const btnAudio = document.getElementById('btn-toggle-audio-guide');
    const labelAudio = document.getElementById('label-audio-guide');
    const iconAudio = document.getElementById('audio-play-icon');

    btnAudio?.addEventListener('click', function () {
        if (!('speechSynthesis' in window)) {
            alert('Síntese de voz não suportada neste navegador.');
            return;
        }

        if (isPlayingAudio) {
            window.speechSynthesis.cancel();
            isPlayingAudio = false;
            if (labelAudio) labelAudio.textContent = 'Ouvir';
            if (iconAudio) iconAudio.className = 'bi bi-volume-up-fill fs-5';
            btnAudio.classList.remove('btn-danger');
            btnAudio.classList.add('btn-primary');
        } else {
            const texto = `{{ $atrativo->nome }}. {{ $atrativo->descricao }} {{ $atrativo->historia ?? '' }}`;
            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR';
            utterance.rate = 1.0;

            utterance.onend = function () {
                isPlayingAudio = false;
                if (labelAudio) labelAudio.textContent = 'Ouvir';
                if (iconAudio) iconAudio.className = 'bi bi-volume-up-fill fs-5';
                btnAudio.classList.remove('btn-danger');
                btnAudio.classList.add('btn-primary');
            };

            window.speechSynthesis.speak(utterance);
            isPlayingAudio = true;
            if (labelAudio) labelAudio.textContent = 'Pausar';
            if (iconAudio) iconAudio.className = 'bi bi-pause-fill fs-5';
            btnAudio.classList.remove('btn-primary');
            btnAudio.classList.add('btn-danger');
        }
    });
});
</script>
@endpush
