/**
 * Sistema de Geolocalização, Seletor de Cidades e Integração com OpenStreetMap (Nominatim)
 * Permite alternar dinamicamente entre cidades (João Pessoa, Bonito, Recife, Natal, São Paulo...)
 * ou detectar a localização real do usuário via GPS, sincronizando o cabeçalho, atrações e mapas.
 */

import { CITIES_DATA, PLACES_DATA, ROTEIROS_DATA } from './places-data';

const BRAZIL_STATES = {
    'Acre': 'AC', 'Alagoas': 'AL', 'Amapá': 'AP', 'Amazonas': 'AM',
    'Bahia': 'BA', 'Ceará': 'CE', 'Distrito Federal': 'DF', 'Espírito Santo': 'ES',
    'Goiás': 'GO', 'Maranhão': 'MA', 'Mato Grosso': 'MT', 'Mato Grosso do Sul': 'MS',
    'Minas Gerais': 'MG', 'Pará': 'PA', 'Paraíba': 'PB', 'Paraná': 'PR',
    'Pernambuco': 'PE', 'Piauí': 'PI', 'Rio de Janeiro': 'RJ', 'Rio Grande do Norte': 'RN',
    'Rio Grande do Sul': 'RS', 'Rondônia': 'RO', 'Roraima': 'RR', 'Santa Catarina': 'SC',
    'São Paulo': 'SP', 'Sergipe': 'SE', 'Tocantins': 'TO'
};

const STORAGE_KEY = 'turismo_user_location';

export const LocationService = {
    _cachedPlaces: null,

    /**
     * Retorna o catálogo de cidades disponíveis
     */
    getCities() {
        return CITIES_DATA;
    },

    /**
     * Retorna todas as atrações disponíveis (prioriza dados reais da API)
     */
    getAllPlaces() {
        if (this._cachedPlaces && this._cachedPlaces.length > 0) {
            return this._cachedPlaces;
        }
        return PLACES_DATA;
    },

    /**
     * Retorna as atrações filtradas por cidade
     */
    getAttractionsByCity(cityName) {
        const pool = (this._cachedPlaces && this._cachedPlaces.length > 0) ? this._cachedPlaces : PLACES_DATA;
        if (!cityName) return pool;
        const normalized = cityName.toLowerCase().trim();
        const filtered = pool.filter(p => 
            p.cidade.toLowerCase().includes(normalized) || 
            normalized.includes(p.cidade.toLowerCase())
        );
        return filtered;
    },

    /**
     * Busca atrações dinamicamente do backend e OpenStreetMap
     */
    async fetchAttractionsFromApi(cityName, lat = null, lng = null) {
        try {
            let url = `/api/v1/atrativos?status=ativo&per_page=250`;
            if (cityName) {
                url += `&cidade=${encodeURIComponent(cityName)}`;
            }
            if (lat && lng) {
                url += `&lat=${lat}&lng=${lng}`;
            }

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (response.ok) {
                const json = await response.json();
                const items = json.data || json;
                if (Array.isArray(items) && items.length > 0) {
                    const mapped = items.map(item => {
                        const slug = item.categoria?.slug || 'geral';
                        const colorMap = {
                            'rios': '#0077b6',
                            'praias-e-rios': '#0077b6',
                            'aventura': '#0a9396',
                            'grutas': '#ee9b00',
                            'gastronomia': '#ba1a1a',
                            'cultura': '#9b2226',
                            'hospedagem': '#6c757d',
                        };

                        return {
                            id: item.id,
                            nome: item.nome,
                            cidade: item.municipio?.nome || cityName || 'Destino',
                            uf: item.municipio?.uf || '',
                            cat: item.categoria?.nome || 'Atrativo',
                            catKey: slug === 'rios' ? 'praia' : (slug === 'grutas' ? 'natureza' : (slug === 'gastronomia' ? 'gastronomia' : (slug === 'cultura' ? 'cultura' : slug))),
                            catIcon: item.categoria?.icone || 'bi-geo-alt',
                            color: colorMap[slug] || '#005f73',
                            lat: parseFloat(item.lat),
                            lng: parseFloat(item.lng),
                            tempoVisita: item.tempo_medio_visita ? `${item.tempo_medio_visita} min` : '1-2 horas',
                            tempoMinutos: item.tempo_medio_visita || 60,
                            rating: 4.9,
                            numAvaliacoes: 120,
                            endereco: item.endereco || '',
                            descricao: item.descricao || '',
                            img: item.imagem_url || item.midias?.[0]?.url || 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                            fotos: item.fotos || (item.imagem_url ? [item.imagem_url] : []),
                            distancia: item.distancia_formatada || null,
                            distanciaKm: item.distancia_km || null,
                            acessibilidade: item.acessibilidade || [],
                        };
                    });

                    this._cachedPlaces = mapped;
                    return mapped;
                }
            }
        } catch (e) {
            console.warn('Falha ao carregar atrativos da API:', e);
        }
        return null;
    },

    /**
     * Retorna os roteiros recomendados para a cidade
     */
    getRoteirosByCity(cityName) {
        if (!cityName) return ROTEIROS_DATA['João Pessoa'] || [];
        
        // Encontra chave correspondente
        for (const [key, roteiros] of Object.entries(ROTEIROS_DATA)) {
            if (key.toLowerCase() === cityName.toLowerCase() || 
                cityName.toLowerCase().includes(key.toLowerCase()) || 
                key.toLowerCase().includes(cityName.toLowerCase())) {
                return roteiros;
            }
        }

        return ROTEIROS_DATA['João Pessoa'] || [];
    },

    /**
     * Retorna a localização armazenada ou o padrão
     */
    getSavedLocation() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (raw) {
                return JSON.parse(raw);
            }
        } catch (e) {
            console.warn('Erro ao ler localização do storage:', e);
        }
        return null;
    },

    /**
     * Salva a localização no LocalStorage e atualiza o DOM e eventos
     */
    async saveLocation(data) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            // Salva a cidade num cookie para o PHP poder acessar e filtrar server-side (ex: Explorar)
            document.cookie = `turismo_user_city=${encodeURIComponent(data.city)}; path=/; max-age=31536000`;
        } catch (e) {
            console.warn('Erro ao salvar localização no storage/cookie:', e);
        }
        this.updateDOM(data);

        // Busca atrativos reais da API
        let attractions = await this.fetchAttractionsFromApi(data.city, data.lat, data.lng);
        if (!attractions || attractions.length === 0) {
            attractions = this.getAttractionsByCity(data.city);
        }
        const roteiros = this.getRoteirosByCity(data.city);

        window.dispatchEvent(new CustomEvent('turismo:location-changed', { 
            detail: {
                ...data,
                attractions,
                roteiros,
                isUserAction: true,
                isInitial: false
            } 
        }));

        window.dispatchEvent(new CustomEvent('turismo:location-user-selected', { 
            detail: {
                ...data,
                attractions,
                roteiros,
                isUserAction: true,
                isInitial: false
            } 
        }));
    },

    /**
     * Atualiza os elementos visuais na tela
     */
    updateDOM(data) {
        if (!data) return;

        const displayText = data.display || `${data.city} ${data.uf || ''}`.trim();
        const cityName = data.city || 'Sua Região';

        // Atualiza cabeçalho
        document.querySelectorAll('#current-location-text, #current-location-display, .current-location-display').forEach(el => {
            el.textContent = displayText;
        });

        // Atualiza todas as menções dinâmicas à cidade
        document.querySelectorAll('.current-city-name').forEach(el => {
            el.textContent = cityName;
        });

        // Atualiza modal se estiver aberto
        const modalLocationDisplay = document.getElementById('modal-current-location-name');
        if (modalLocationDisplay) {
            modalLocationDisplay.textContent = displayText;
        }

        const modalCoordsDisplay = document.getElementById('modal-current-coords');
        if (modalCoordsDisplay && data.lat && data.lng) {
            modalCoordsDisplay.textContent = `${Number(data.lat).toFixed(4)}, ${Number(data.lng).toFixed(4)}`;
        }

        // Atualiza estado ativo nos botões do dropdown do Header
        document.querySelectorAll('.btn-select-city').forEach(btn => {
            const btnCity = btn.getAttribute('data-city');
            const checkIcon = btn.querySelector('.active-check');
            if (btnCity && (btnCity.toLowerCase() === cityName.toLowerCase() || cityName.toLowerCase().includes(btnCity.toLowerCase()))) {
                btn.classList.add('bg-primary-subtle', 'fw-bold');
                if (checkIcon) checkIcon.classList.remove('d-none');
            } else {
                btn.classList.remove('bg-primary-subtle', 'fw-bold');
                if (checkIcon) checkIcon.classList.add('d-none');
            }
        });

        // Atualiza botões de atalho rápido no modal
        document.querySelectorAll('.btn-quick-location').forEach(btn => {
            const btnCity = btn.getAttribute('data-city');
            if (btnCity && (btnCity.toLowerCase() === cityName.toLowerCase() || cityName.toLowerCase().includes(btnCity.toLowerCase()))) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-primary', 'text-white');
            } else {
                btn.classList.remove('btn-primary', 'text-white');
                btn.classList.add('btn-outline-secondary');
            }
        });
    },

    /**
     * Geocodificação reversa usando OpenStreetMap (Nominatim)
     */
    async reverseGeocode(lat, lng) {
        // 1. Tenta endpoint do backend Laravel
        try {
            const res = await fetch(`/api/v1/location/reverse?lat=${lat}&lng=${lng}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                const data = await res.json();
                if (data && data.city && data.city !== 'Desconhecido') {
                    return data;
                }
            }
        } catch (err) {
            console.log('Backend reverse geocode indisponível, usando fallback direto OSM Nominatim');
        }

        // 2. Fallback direto para OpenStreetMap Nominatim
        try {
            const osmUrl = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=10&addressdetails=1`;
            const res = await fetch(osmUrl, {
                headers: { 'Accept-Language': 'pt-BR,pt;q=0.9,en;q=0.8' }
            });
            if (res.ok) {
                const osmData = await res.json();
                const addr = osmData.address || {};

                const city = addr.city || addr.town || addr.municipality || addr.village || addr.city_district || addr.county || addr.suburb || 'Localização Atual';
                let uf = '';

                if (addr['ISO3166-2-lvl4']) {
                    uf = addr['ISO3166-2-lvl4'].replace('BR-', '');
                } else if (addr.state && BRAZIL_STATES[addr.state]) {
                    uf = BRAZIL_STATES[addr.state];
                } else if (addr.state) {
                    uf = addr.state;
                }

                const display = uf ? `${city} ${uf}` : city;

                return {
                    city,
                    uf,
                    state: addr.state || '',
                    display,
                    neighborhood: addr.suburb || addr.neighbourhood || null,
                    country: addr.country || 'Brasil',
                    lat,
                    lng,
                    raw_address: addr
                };
            }
        } catch (osmErr) {
            console.error('Erro na chamada direta ao OSM Nominatim:', osmErr);
        }

        return {
            city: 'Localização Atual',
            uf: '',
            state: '',
            display: 'Localização Atual',
            lat,
            lng
        };
    },

    /**
     * Busca de cidades via OpenStreetMap Nominatim
     */
    async searchCities(query) {
        if (!query || query.trim().length < 2) return [];

        // 1. Tenta backend
        try {
            const res = await fetch(`/api/v1/location/search?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                const results = await res.json();
                if (Array.isArray(results) && results.length > 0) return results;
            }
        } catch (e) {
            // fallback
        }

        // 2. Fallback direto OSM
        try {
            const osmUrl = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&countrycodes=br&limit=6&addressdetails=1`;
            const res = await fetch(osmUrl, {
                headers: { 'Accept-Language': 'pt-BR,pt;q=0.9' }
            });
            if (res.ok) {
                const items = await res.json();
                return items.map(item => {
                    const addr = item.address || {};
                    const city = addr.city || addr.town || addr.municipality || addr.village || addr.city_district || item.name;
                    let uf = '';
                    if (addr['ISO3166-2-lvl4']) {
                        uf = addr['ISO3166-2-lvl4'].replace('BR-', '');
                    } else if (addr.state && BRAZIL_STATES[addr.state]) {
                        uf = BRAZIL_STATES[addr.state];
                    }
                    const display = uf ? `${city} ${uf}` : city;
                    return {
                        city,
                        uf,
                        state: addr.state || '',
                        display,
                        display_name: item.display_name,
                        lat: parseFloat(item.lat),
                        lng: parseFloat(item.lon)
                    };
                });
            }
        } catch (err) {
            console.error('Erro ao buscar cidades no OSM:', err);
        }

        return [];
    },

    /**
     * Executa a detecção via GPS do navegador
     */
    detectGPS(options = {}) {
        const { showLoading = true, onStart, onSuccess, onError } = options;

        if (showLoading) {
            this.setLoading(true);
        }
        if (onStart) onStart();

        if (!navigator.geolocation) {
            this.setLoading(false);
            const err = new Error('Geolocalização não é suportada por este navegador.');
            if (onError) onError(err);
            return Promise.reject(err);
        }

        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    try {
                        const locationData = await this.reverseGeocode(lat, lng);
                        locationData.accuracy = position.coords.accuracy;
                        locationData.isGPS = true;
                        locationData.updatedAt = new Date().toISOString();

                        this.saveLocation(locationData);
                        this.setLoading(false);

                        if (onSuccess) onSuccess(locationData);
                        resolve(locationData);
                    } catch (err) {
                        this.setLoading(false);
                        if (onError) onError(err);
                        reject(err);
                    }
                },
                (geoError) => {
                    this.setLoading(false);
                    console.warn('Erro ao obter posição GPS:', geoError.message);
                    
                    // Mensagens em Português
                    let msg = 'Não foi possível obter sua localização GPS.';
                    if (geoError.code === 1) { // PERMISSION_DENIED
                        msg = 'Permissão de localização negada no navegador.';
                    } else if (geoError.code === 2) { // POSITION_UNAVAILABLE
                        msg = 'Sinal de GPS indisponível no momento.';
                    } else if (geoError.code === 3) { // TIMEOUT
                        msg = 'Tempo limite esgotado ao buscar GPS.';
                    }
                    
                    const err = new Error(msg);
                    if (onError) onError(err);
                    reject(err);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        });
    },

    /**
     * Calcula a distância entre duas coordenadas GPS em km (Fórmula Haversine)
     */
    calculateDistanceKm(lat1, lon1, lat2, lon2) {
        if (lat1 === null || lat1 === undefined || lon1 === null || lon1 === undefined || 
            lat2 === null || lat2 === undefined || lon2 === null || lon2 === undefined) return null;
        const R = 6371; // Raio da Terra em km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return parseFloat((R * c).toFixed(2));
    },

    /**
     * Formata distância amigável para exibição
     */
    formatDistance(distKm) {
        if (distKm === null || distKm === undefined || isNaN(distKm)) return '';
        if (distKm < 1) {
            return `${Math.round(distKm * 1000)} m`;
        }
        return `${distKm.toFixed(1).replace('.', ',')} km`;
    },

    /**
     * Gera URL de navegação/rota para o destino usando a localização GPS real do usuário
     */
    getDirectionsUrl(destLat, destLng, provider = 'google', destName = '') {
        const saved = this.getSavedLocation();
        const startLat = saved?.lat;
        const startLng = saved?.lng;

        if (provider === 'waze') {
            return `https://waze.com/ul?ll=${destLat},${destLng}&navigate=yes`;
        }
        if (provider === 'osm' || provider === 'openstreetmap') {
            if (startLat && startLng) {
                return `https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=${startLat}%2C${startLng}%3B${destLat}%2C${destLng}`;
            }
            return `https://www.openstreetmap.org/?mlat=${destLat}&mlon=${destLng}#map=16/${destLat}/${destLng}`;
        }
        if (provider === 'apple') {
            return `https://maps.apple.com/?daddr=${destLat},${destLng}&saddr=${startLat || ''},${startLng || ''}`;
        }

        // Padrão Google Maps
        if (startLat && startLng) {
            return `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}&travelmode=driving`;
        }
        return `https://maps.google.com/?q=${destLat},${destLng}${destName ? `(${encodeURIComponent(destName)})` : ''}`;
    },

    /**
     * Abre a melhor rota disponível
     */
    openDirections(destLat, destLng, destName = '') {
        const url = this.getDirectionsUrl(destLat, destLng, 'google', destName);
        window.open(url, '_blank');
    },

    /**
     * Define manualmente uma localização (ex: ao clicar no dropdown ou atalho)
     */
    setLocationManual(city, uf, lat, lng, displayName = null) {
        const display = displayName || (uf ? `${city} ${uf}` : city);
        const data = {
            city,
            uf,
            display,
            lat: parseFloat(lat),
            lng: parseFloat(lng),
            isGPS: false,
            updatedAt: new Date().toISOString()
        };
        this.saveLocation(data);
        return data;
    },

    /**
     * Controla o spinner de carregamento no cabeçalho e modal
     */
    setLoading(loading) {
        document.querySelectorAll('#location-spinner, .location-spinner').forEach(el => {
            if (loading) el.classList.remove('d-none');
            else el.classList.add('d-none');
        });
        document.querySelectorAll('#location-pin-icon, .location-pin-icon').forEach(el => {
            if (loading) el.classList.add('opacity-50');
            else el.classList.remove('opacity-50');
        });
    },

    /**
     * Inicializa o serviço no carregamento da página
     */
    init() {
        const defaultLocation = {
            city: 'João Pessoa',
            uf: 'PB',
            display: 'João Pessoa PB',
            lat: -7.1153,
            lng: -34.8641,
            isGPS: false
        };

        const saved = this.getSavedLocation() || defaultLocation;
        this.updateDOM(saved);

        // Dispara busca inicial na API para alimentar atrativos e fotos reais
        this.fetchAttractionsFromApi(saved.city, saved.lat, saved.lng).then(attractions => {
            const roteiros = this.getRoteirosByCity(saved.city);
            window.dispatchEvent(new CustomEvent('turismo:location-changed', { 
                detail: {
                    ...saved,
                    attractions: attractions || this.getAttractionsByCity(saved.city),
                    roteiros,
                    isInitial: true,
                    isUserAction: false
                } 
            }));
        });

        // Controla banner de permissão de GPS
        const banner = document.getElementById('location-permission-banner');
        if (banner && !saved.isGPS) {
            banner.classList.remove('d-none');
        }

        // Listener para botão de ativar GPS na Home
        document.getElementById('btn-enable-gps-home')?.addEventListener('click', () => {
            this.detectGPS({
                showLoading: true,
                onSuccess: () => {
                    banner?.classList.add('d-none');
                }
            });
        });
    }
};

// Auto inicializar no DOM
if (typeof window !== 'undefined') {
    window.LocationService = LocationService;
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            LocationService.init();
        });
    } else {
        LocationService.init();
    }
}

export default LocationService;
