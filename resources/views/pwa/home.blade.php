@extends('layouts.pwa')

@push('styles')
<style>
    .home-transition-fade {
        transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
    }
    .home-transition-fade.updating {
        opacity: 0.3;
        transform: translateY(4px);
    }
    .place-home-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .place-home-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 py-4">
    <!-- Saudação -->
    <div class="mb-4">
        <h1 class="fw-bold text-dark fs-1 mb-1" style="letter-spacing: -0.02em;">Bom dia, Turista!</h1>
        <p class="text-secondary small mt-1">O que vamos descobrir hoje em <span class="current-city-name fw-semibold text-primary">João Pessoa</span>?</p>
    </div>


    <!-- Alertas e Comunicados Oficiais de Defesa Civil -->
    @if(isset($alertasDefesaCivil) && $alertasDefesaCivil->count() > 0)
        <div class="mb-4" id="home-alerts-wrapper">
            @foreach($alertasDefesaCivil as $alerta)
                @php
                    $isUrgente = in_array($alerta->urgencia, ['urgente', 'emergencia', 'perigo']);
                @endphp
                <x-pwa.alerta-banner 
                    :id="$alerta->id"
                    :titulo="$alerta->titulo"
                    :descricao="Str::limit($alerta->corpo, 110)"
                    :urgencia="$alerta->urgencia"
                    :responsavel="$alerta->responsavel ?? 'Defesa Civil'"
                    :contatoEmergencia="$alerta->contato_emergencia"
                    :validoAte="$alerta->valido_ate ? $alerta->valido_ate->format('d/m H:i') : null"
                />

                <!-- Modal de Detalhes do Alerta -->
                <div class="modal fade" id="modalAlerta{{ $alerta->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                            <div class="modal-header border-0 pb-0 pt-4 px-4 {{ $isUrgente ? 'bg-danger text-white' : 'bg-light' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-fill-exclamation fs-4 {{ $isUrgente ? 'text-warning' : 'text-primary' }}"></i>
                                    <h5 class="modal-title fw-bold fs-6">Comunicado Oficial</h5>
                                </div>
                                <button type="button" class="btn-close {{ $isUrgente ? 'btn-close-white' : '' }}" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="d-flex gap-2 mb-3 flex-wrap">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-bold">
                                        Grau de Risco: {{ strtoupper($alerta->urgencia) }}
                                    </span>
                                    <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">
                                        Válido até: {{ $alerta->valido_ate ? $alerta->valido_ate->format('d/m/Y H:i') : '24h' }}
                                    </span>
                                </div>

                                <h5 class="fw-bold text-dark mb-2">{{ $alerta->titulo }}</h5>
                                <p class="text-secondary small mb-4" style="line-height: 1.6; white-space: pre-line;">{{ $alerta->corpo }}</p>

                                <div class="bg-light p-3 rounded-4 border mb-3">
                                    @if($alerta->responsavel)
                                        <div class="small fw-bold text-dark mb-1">
                                            <i class="bi bi-shield-check text-primary me-1"></i> Órgão Emissor:
                                        </div>
                                        <div class="text-secondary small mb-3">{{ $alerta->responsavel }}</div>
                                    @endif

                                    <div class="fw-bold text-dark small mb-2"><i class="bi bi-telephone-fill text-danger me-1"></i> Telefones & Contatos de Emergência:</div>
                                    <div class="text-danger fw-bold small mb-2">{{ $alerta->contato_emergencia ?? 'Defesa Civil 199 / SAMU 192' }}</div>

                                    <div class="d-flex flex-wrap gap-2 pt-1">
                                        <a href="tel:199" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                                            <i class="bi bi-shield me-1"></i> Defesa Civil 199
                                        </a>
                                        <a href="tel:193" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                                            <i class="bi bi-fire me-1"></i> Bombeiros 193
                                        </a>
                                        <a href="tel:192" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">
                                            <i class="bi bi-heart-pulse me-1"></i> SAMU 192
                                        </a>
                                        <a href="tel:190" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
                                            <i class="bi bi-shield-shaded me-1"></i> PM 190
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-primary rounded-pill px-4 w-100 fw-bold" data-bs-dismiss="modal" onclick="if(window.AlertasManager) window.AlertasManager.dismissHomeBanner({{ $alerta->id }});">
                                    <i class="bi bi-check2-circle me-1"></i> Estou Ciente (Marcar como Visto)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Banner de Permissão de Localização (exibido se GPS não detectado) -->
    <div id="location-permission-banner" class="card border-0 rounded-4 p-3 mb-4 text-white shadow-sm d-none" style="background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pe-2">
                <div class="fw-bold fs-6"><i class="bi bi-geo-alt-fill text-warning me-1"></i> Ativar Localização Real</div>
                <div class="small opacity-90" style="font-size: 0.78rem;">Descubra atrativos, praias e restaurantes próximos a você via OpenStreetMap.</div>
            </div>
            <button class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 flex-shrink-0 shadow-sm" id="btn-enable-gps-home" style="font-size: 0.85rem;">
                <i class="bi bi-crosshair me-1"></i> Ativar GPS
            </button>
        </div>
    </div>

    <!-- Roteiros em Destaque Dinâmicos -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h2 class="fs-5 fw-bold text-dark m-0">Roteiros Recomendados</h2>
                <div class="text-muted small" style="font-size: 0.75rem;">Itinerários prontos para <span class="current-city-name text-primary fw-bold">João Pessoa</span></div>
            </div>
            <a href="{{ route('pwa.roteiros') }}" class="small fw-semibold text-primary text-decoration-none" style="min-height: 44px; display: flex; align-items: center;">Ver todos</a>
        </div>
        
        <div id="home-roteiros-container" class="d-flex overflow-auto no-scrollbar gap-3 pb-3 home-transition-fade" style="margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem; scroll-snap-type: x mandatory;">
            <!-- Renderizado dinamicamente via JS de acordo com a cidade -->
        </div>
    </div>

    <!-- Categorias -->
    <div class="mb-5">
        <h2 class="fs-5 fw-bold text-dark mb-3">Explorar por Categoria</h2>
        <div class="row row-cols-4 g-2 text-center">
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=rios" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(0, 95, 115, 0.1); color: var(--bs-primary);">
                        <i class="bi bi-water fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Rios/Praias</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=grutas" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(238, 155, 0, 0.1); color: #ee9b00;">
                        <i class="bi bi-geo fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Natureza</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=aventura" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(10, 147, 150, 0.1); color: var(--bs-secondary);">
                        <i class="bi bi-bicycle fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Aventura</span>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('pwa.explorar') }}?cat=gastronomia" class="text-decoration-none text-dark d-flex flex-column align-items-center gap-2">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(186, 26, 26, 0.1); color: #ba1a1a;">
                        <i class="bi bi-cup-hot fs-3"></i>
                    </div>
                    <span class="small fw-semibold">Comer</span>
                </a>
            </div>
        </div>
    </div>

    <!-- SEÇÃO: Atrações em Destaque na Cidade Selecionada (Dados do Mapa) -->
    <div class="mb-5" id="home-attractions-section">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h2 class="fs-5 fw-bold text-dark m-0">Atrações em <span class="current-city-name text-primary">João Pessoa</span></h2>
                <div class="text-muted small" style="font-size: 0.75rem;"><span id="home-attractions-count">6 atrações</span> disponíveis no mapa</div>
            </div>
            <a href="{{ route('pwa.mapa') }}" class="small fw-semibold text-primary text-decoration-none d-flex align-items-center gap-1">
                <i class="bi bi-map"></i> Ver no Mapa
            </a>
        </div>

        <!-- Filtros Rápidos de Categoria na Home -->
        <div class="d-flex gap-2 overflow-auto no-scrollbar pb-2 mb-3">
            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold flex-shrink-0 home-category-filter active" data-cat="all">Todas</button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold flex-shrink-0 home-category-filter" data-cat="praia">
                <i class="bi bi-water text-primary me-1"></i> Praias & Rios
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold flex-shrink-0 home-category-filter" data-cat="natureza">
                <i class="bi bi-geo-alt-fill text-warning me-1"></i> Natureza
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold flex-shrink-0 home-category-filter" data-cat="cultura">
                <i class="bi bi-bank text-danger me-1"></i> Cultura
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold flex-shrink-0 home-category-filter" data-cat="gastronomia">
                <i class="bi bi-cup-hot text-success me-1"></i> Gastronomia
            </button>
        </div>

        <!-- Grid de Atrações Dinâmicas (3 colunas na web) -->
        <div id="home-attractions-grid" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-3 home-transition-fade">
            <!-- Renderizado dinamicamente via JS de acordo com a cidade e filtros -->
        </div>
    </div>

    <!-- Banner DTI / Turismo Seguro -->
    <div class="card border-0 rounded-4 text-white overflow-hidden mb-4" style="background: linear-gradient(135deg, #0a9396, #005f73); box-shadow: 0 8px 24px rgba(0, 95, 115, 0.25);">
        <div class="position-absolute opacity-25" style="right: -20px; bottom: -20px;">
            <i class="bi bi-shield-check" style="font-size: 8rem;"></i>
        </div>
        <div class="card-body p-4 position-relative z-1">
            <h3 class="fw-bold fs-5 mb-1">Turismo Seguro</h3>
            <p class="small text-white-50 mb-3 w-75">Acesse telefones úteis, hospitais e alertas da Defesa Civil.</p>
            <a href="{{ route('pwa.utilidade') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm" style="min-height: 44px; display: inline-flex; align-items: center;">
                Acessar Central
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roteirosContainer = document.getElementById('home-roteiros-container');
        const attractionsGrid = document.getElementById('home-attractions-grid');
        const attractionsCountEl = document.getElementById('home-attractions-count');
        let currentFilter = 'all';

        function renderRoteiros(city) {
            if (!roteirosContainer) return;
            const roteiros = window.LocationService ? window.LocationService.getRoteirosByCity(city) : [];
            
            let html = '';
            roteiros.forEach(r => {
                html += `
                    <a href="/roteiro/${r.id}" class="card border-0 rounded-4 text-decoration-none text-dark flex-shrink-0" style="width: 270px; scroll-snap-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
                        <div class="position-relative overflow-hidden rounded-top-4" style="height: 140px; background-color: #f3f4f5;">
                            <img src="${r.img}" alt="${r.titulo}" class="w-100 h-100 object-fit-cover" loading="lazy">
                            <div class="position-absolute top-0 start-0 m-2 px-2 py-1 rounded bg-white bg-opacity-75" style="backdrop-filter: blur(4px);">
                                <span class="small fw-bold ${r.tagColor || 'text-primary'}">${r.tag}</span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h3 class="card-title fs-6 fw-bold mb-1">${r.titulo}</h3>
                            <p class="card-text small text-secondary mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${r.subtitulo}</p>
                            <div class="d-flex align-items-center justify-content-between small fw-medium text-secondary">
                                <span class="d-flex align-items-center gap-1"><i class="bi bi-clock text-primary"></i> ${r.duracao}</span>
                                <span class="d-flex align-items-center gap-1"><i class="bi bi-geo-alt-fill text-warning"></i> ${r.cidade}</span>
                            </div>
                        </div>
                    </a>
                `;
            });

            // Card Fixo do Assistente com IA
            html += `
                <a href="{{ route('pwa.ia') }}" class="card border-0 rounded-4 text-decoration-none text-dark flex-shrink-0" style="width: 270px; scroll-snap-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
                    <div class="position-relative overflow-hidden rounded-top-4 d-flex flex-column align-items-center justify-content-center text-white" style="height: 140px; background: linear-gradient(135deg, #005f73, #0a9396);">
                        <i class="bi bi-robot display-5 mb-1"></i>
                        <span class="fw-bold">Assistente com IA</span>
                    </div>
                    <div class="card-body p-3">
                        <h3 class="card-title fs-6 fw-bold mb-1">Roteiro Personalizado</h3>
                        <p class="card-text small text-secondary">Deixe nossa inteligência artificial montar o dia perfeito para você em ${city}.</p>
                    </div>
                </a>
            `;

            roteirosContainer.innerHTML = html;
        }

        function renderAttractions(city) {
            if (!attractionsGrid) return;
            const savedLoc = window.LocationService ? window.LocationService.getSavedLocation() : null;
            const uLat = savedLoc?.lat ? parseFloat(savedLoc.lat) : null;
            const uLng = savedLoc?.lng ? parseFloat(savedLoc.lng) : null;

            const allCityPlaces = window.LocationService ? window.LocationService.getAttractionsByCity(city) : [];
            const filteredPlaces = currentFilter === 'all' 
                ? allCityPlaces 
                : allCityPlaces.filter(p => p.catKey === currentFilter);

            if (attractionsCountEl) {
                attractionsCountEl.textContent = `${allCityPlaces.length} ${allCityPlaces.length === 1 ? 'atração' : 'atrações'}`;
            }

            if (filteredPlaces.length === 0) {
                attractionsGrid.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <div class="rounded-circle d-inline-flex p-3 bg-light text-muted mb-2">
                            <i class="bi bi-compass fs-2"></i>
                        </div>
                        <p class="text-secondary small mb-0">Nenhuma atração encontrada para esta categoria em ${city}.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            filteredPlaces.forEach(place => {
                let distText = '';
                if (uLat && uLng && window.LocationService) {
                    const distKm = window.LocationService.calculateDistanceKm(uLat, uLng, place.lat, place.lng);
                    const formatted = window.LocationService.formatDistance(distKm);
                    if (formatted) {
                        distText = `<span class="badge bg-warning-subtle text-dark border rounded-pill px-2 py-1"><i class="bi bi-geo-alt-fill text-warning me-1"></i>${formatted}</span>`;
                    }
                }

                html += `
                    <div class="col">
                        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 place-home-card bg-white">
                            <div class="position-relative" style="height: 160px;">
                                <img src="${place.img}" alt="${place.nome}" class="w-100 h-100 object-fit-cover" loading="lazy">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge rounded-pill px-2.5 py-1 text-white shadow-sm" style="background-color: ${place.color}; font-size: 0.72rem;">
                                        <i class="bi ${place.catIcon} me-1"></i>${place.cat}
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-white text-dark rounded-pill px-2 py-1 shadow-sm fw-bold small">
                                        <i class="bi bi-star-fill text-warning me-1"></i>${place.rating}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h3 class="card-title fs-6 fw-bold mb-1 text-dark">${place.nome}</h3>
                                    <p class="card-text small text-secondary mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.8rem;">
                                        ${place.descricao}
                                    </p>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 mb-3 border-top small text-muted" style="font-size: 0.75rem;">
                                        <span><i class="bi bi-clock me-1 text-primary"></i>${place.tempoVisita || '1-2 horas'}</span>
                                        ${distText}
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="/atrativo/${place.id}" class="btn btn-primary rounded-pill btn-sm w-100 fw-bold py-1.5" style="font-size: 0.8rem;">
                                            Ver Detalhes
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-bold py-1.5" style="font-size: 0.8rem;" 
                                            onclick="window.LocationService ? window.LocationService.openDirections(${place.lat}, ${place.lng}, '${place.nome}') : null" title="Traçar rota até o local">
                                            <i class="bi bi-arrow-up-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            attractionsGrid.innerHTML = html;
        }

        function refreshHome(city) {
            const cityName = city || (window.LocationService?.getSavedLocation()?.city) || 'João Pessoa';
            
            // Efeito suave de transição
            if (roteirosContainer) roteirosContainer.classList.add('updating');
            if (attractionsGrid) attractionsGrid.classList.add('updating');

            setTimeout(() => {
                renderRoteiros(cityName);
                renderAttractions(cityName);
                if (roteirosContainer) roteirosContainer.classList.remove('updating');
                if (attractionsGrid) attractionsGrid.classList.remove('updating');
            }, 150);
        }

        // Inicialização com a cidade salva
        const initialLoc = window.LocationService ? window.LocationService.getSavedLocation() : null;
        refreshHome(initialLoc ? initialLoc.city : 'João Pessoa');

        // Escuta mudanças de localização emitidas pelo LocationService
        window.addEventListener('turismo:location-changed', function(e) {
            const locData = e.detail;
            if (locData && locData.city) {
                refreshHome(locData.city);
            }
        });

        // Manipulador dos filtros de categoria na home
        document.querySelectorAll('.home-category-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.home-category-filter').forEach(b => {
                    b.classList.remove('btn-dark', 'active');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-dark', 'active');
                currentFilter = this.getAttribute('data-cat');
                
                const curCity = window.LocationService?.getSavedLocation()?.city || 'João Pessoa';
                renderAttractions(curCity);
            });
        });
    });
</script>
@endpush
