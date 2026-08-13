@extends('layouts.pwa')

@section('content')
<div class="px-3 py-3 sticky-top bg-light border-bottom" style="z-index: 1020;">
    <div class="position-relative">
        <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
            <i class="bi bi-search text-secondary"></i>
        </div>
        <input type="text" id="explorar-search-input" class="form-control rounded-pill border-0 shadow-sm ps-5 bg-white" placeholder="Buscar atrativos, praias, restaurantes..." style="height: 48px;">
    </div>
    
    <!-- Filtros Chips -->
    <div class="d-flex gap-2 mt-3 overflow-auto no-scrollbar pb-1" style="margin-left: -1rem; margin-right: -1rem; padding-left: 1rem; padding-right: 1rem;">
        <button class="btn btn-primary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 cat-filter-btn active" data-cat="all" style="min-height: 36px;">Todos</button>
        <button class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white cat-filter-btn" data-cat="proximos" style="min-height: 36px;">
            <i class="bi bi-crosshair text-primary me-1"></i> Mais Próximos
        </button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white cat-filter-btn" data-cat="praia" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Praias & Rios</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white cat-filter-btn" data-cat="gastronomia" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Gastronomia</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white cat-filter-btn" data-cat="cultura" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Cultura & História</button>
        <button class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-medium flex-shrink-0 bg-white cat-filter-btn" data-cat="natureza" style="min-height: 36px; border-color: rgba(0,0,0,0.1);">Natureza</button>
    </div>
</div>

<div class="container-fluid px-3 py-4">
    <!-- Header de Informações de Proximidade -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="small text-secondary">
            <span>Mostrando locais perto de </span><strong class="text-dark current-city-name">sua região</strong>
        </div>
        <span class="badge bg-light text-primary border rounded-pill px-3 py-1" id="location-accuracy-badge">
            <i class="bi bi-geo-alt-fill me-1"></i> GPS Ativo
        </span>
    </div>

    <!-- Lista de Atrativos -->
    <div class="d-flex flex-column gap-3" id="atrativos-container">
        <!-- Renderizado dinamicamente com base na localização e filtros -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ALL_ATRACTIVOS = [
            // João Pessoa - PB
            {
                id: 101,
                nome: 'Praia de Tambaú',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'Av. Alm. Tamandaré, Tambaú',
                img: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                color: 'primary',
                lat: -7.1147,
                lng: -34.8239,
                rating: 4.9,
                avaliacoes: 342,
                tempo: '180 min'
            },
            {
                id: 102,
                nome: 'Farol do Cabo Branco',
                cat: 'Monumentos & Natureza',
                catKey: 'natureza',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'Ponta do Seixas, Cabo Branco',
                img: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
                color: 'warning',
                lat: -7.1477,
                lng: -34.7963,
                rating: 4.8,
                avaliacoes: 215,
                tempo: '60 min'
            },
            {
                id: 103,
                nome: 'Piscinas Naturais dos Seixas',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'Praia dos Seixas',
                img: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80',
                color: 'primary',
                lat: -7.1597,
                lng: -34.7877,
                rating: 4.9,
                avaliacoes: 480,
                tempo: '150 min'
            },
            {
                id: 104,
                nome: 'Centro Cultural São Francisco',
                cat: 'Cultura & História',
                catKey: 'cultura',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'Praça São Francisco, Centro Histórico',
                img: 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=800&q=80',
                color: 'danger',
                lat: -7.1155,
                lng: -34.8864,
                rating: 4.9,
                avaliacoes: 190,
                tempo: '90 min'
            },
            {
                id: 105,
                nome: 'Mangai João Pessoa',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'Av. Edson Ramalho, 696, Manaíra',
                img: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
                color: 'success',
                lat: -7.1067,
                lng: -34.8315,
                rating: 4.9,
                avaliacoes: 520,
                tempo: '90 min'
            },
            {
                id: 106,
                nome: 'Nau Frutos do Mar',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'João Pessoa',
                uf: 'PB',
                endereco: 'R. Lupércio Branco, 130, Manaíra',
                img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
                color: 'success',
                lat: -7.1189,
                lng: -34.8302,
                rating: 4.8,
                avaliacoes: 310,
                tempo: '90 min'
            },
            // Bonito - MS
            {
                id: 1,
                nome: 'Flutuação no Rio Sucuri',
                cat: 'Praias & Rios',
                catKey: 'praia',
                cidade: 'Bonito',
                uf: 'MS',
                endereco: 'Fazenda São Geraldo',
                img: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80',
                color: 'primary',
                lat: -21.2642,
                lng: -56.5516,
                rating: 4.9,
                avaliacoes: 412,
                tempo: '120 min'
            },
            {
                id: 2,
                nome: 'Gruta do Lago Azul',
                cat: 'Monumentos & Natureza',
                catKey: 'natureza',
                cidade: 'Bonito',
                uf: 'MS',
                endereco: 'Rodovia MS 382, Km 20',
                img: 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=800&q=80',
                color: 'warning',
                lat: -21.1469,
                lng: -56.5861,
                rating: 4.8,
                avaliacoes: 320,
                tempo: '90 min'
            },
            {
                id: 3,
                nome: 'Bóia Cross no Rio Formoso',
                cat: 'Aventura & Trilhas',
                catKey: 'natureza',
                cidade: 'Bonito',
                uf: 'MS',
                endereco: 'Parque Ecológico Rio Formoso',
                img: 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=800&q=80',
                color: 'secondary',
                lat: -21.1895,
                lng: -56.4523,
                rating: 4.7,
                avaliacoes: 145,
                tempo: '60 min'
            },
            {
                id: 4,
                nome: 'Casa do João',
                cat: 'Gastronomia Regional',
                catKey: 'gastronomia',
                cidade: 'Bonito',
                uf: 'MS',
                endereco: 'Rua Cel. Nélson Felício dos Santos, Centro',
                img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
                color: 'success',
                lat: -21.1275,
                lng: -56.4831,
                rating: 4.9,
                avaliacoes: 512,
                tempo: '120 min'
            }
        ];

        const container = document.getElementById('atrativos-container');
        const searchInput = document.getElementById('explorar-search-input');
        const filterBtns = document.querySelectorAll('.cat-filter-btn');

        let activeCat = 'all';
        let searchQuery = '';

        function renderAtrativos() {
            const savedLocation = window.LocationService ? window.LocationService.getSavedLocation() : null;
            const uLat = savedLocation?.lat ? parseFloat(savedLocation.lat) : null;
            const uLng = savedLocation?.lng ? parseFloat(savedLocation.lng) : null;

            // Calcula distâncias em tempo real para cada atrativo
            let items = ALL_ATRACTIVOS.map(item => {
                let distKm = null;
                let distText = '';
                if (uLat && uLng && window.LocationService) {
                    distKm = window.LocationService.calculateDistanceKm(uLat, uLng, item.lat, item.lng);
                    distText = window.LocationService.formatDistance(distKm);
                }
                return { ...item, distKm, distText };
            });

            // Filtro por texto de busca
            if (searchQuery.trim().length > 0) {
                const q = searchQuery.toLowerCase();
                items = items.filter(i => 
                    i.nome.toLowerCase().includes(q) || 
                    i.cat.toLowerCase().includes(q) || 
                    i.cidade.toLowerCase().includes(q) ||
                    i.endereco.toLowerCase().includes(q)
                );
            }

            // Filtro por Categoria
            if (activeCat === 'proximos') {
                items = items.filter(i => i.distKm !== null).sort((a, b) => (a.distKm || 99999) - (b.distKm || 99999));
            } else if (activeCat !== 'all') {
                items = items.filter(i => i.catKey === activeCat);
            } else if (uLat && uLng) {
                // Ordenação inteligente: prioriza locais na mesma cidade ou mais próximos
                items.sort((a, b) => (a.distKm || 99999) - (b.distKm || 99999));
            }

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 bg-white rounded-4 border p-4">
                        <i class="bi bi-geo-alt text-muted display-4"></i>
                        <h4 class="fw-bold fs-6 mt-3">Nenhum local encontrado</h4>
                        <p class="small text-secondary mb-0">Tente buscar por outro termo ou alterar o filtro.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = items.map(a => `
                <a href="/atrativo/${a.id}" class="card border-0 rounded-4 text-decoration-none text-dark d-flex flex-row overflow-hidden shadow-sm position-relative" style="box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04) !important;">
                    <div class="position-relative flex-shrink-0" style="width: 125px; background-color: #f3f4f5;">
                        <img src="${a.img}" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" alt="${a.nome}" loading="lazy">
                        ${a.distText ? `
                            <span class="position-absolute bottom-0 start-0 m-1 badge bg-dark bg-opacity-75 rounded-pill px-2 py-1" style="font-size: 0.65rem; backdrop-filter: blur(4px);">
                                <i class="bi bi-geo-alt-fill text-warning"></i> ${a.distText}
                            </span>
                        ` : ''}
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-uppercase fw-bold text-${a.color}" style="font-size: 0.65rem; letter-spacing: 0.05em;">${a.cat}</span>
                            <span class="text-muted small" style="font-size: 0.68rem;"><i class="bi bi-clock"></i> ${a.tempo}</span>
                        </div>
                        <h3 class="card-title fs-6 fw-bold mb-1" style="line-height: 1.2;">${a.nome}</h3>
                        <p class="text-secondary small mb-2 text-truncate" style="font-size: 0.75rem;">${a.endereco}</p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="d-flex align-items-center gap-1 text-secondary" style="font-size: 0.7rem;">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="fw-bold text-dark">${a.rating}</span>
                                <span class="text-muted">(${a.avaliacoes})</span>
                            </div>
                            <span class="text-primary fw-semibold small" style="font-size: 0.75rem;">Ver detalhes <i class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                </a>
            `).join('');
        }

        // Eventos de filtro
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => {
                    b.classList.remove('btn-primary', 'active');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary', 'active');
                activeCat = this.getAttribute('data-cat');
                renderAtrativos();
            });
        });

        // Evento de busca
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value;
                renderAtrativos();
            });
        }

        // Listener para atualização de localização em tempo real
        window.addEventListener('turismo:location-changed', function() {
            renderAtrativos();
        });

        // Renderização inicial
        renderAtrativos();
    });
</script>
@endpush

