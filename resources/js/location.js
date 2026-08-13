/**
 * Sistema de Geolocalização e Integração com OpenStreetMap (Nominatim)
 * Permite detectar a localização real do usuário (ex: João Pessoa - PB)
 * e atualizar dinamicamente a interface do PWA.
 */

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
     * Salva a localização no LocalStorage e atualiza o DOM
     */
    saveLocation(data) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        } catch (e) {
            console.warn('Erro ao salvar localização no storage:', e);
        }
        this.updateDOM(data);
        window.dispatchEvent(new CustomEvent('turismo:location-changed', { detail: data }));
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
     * Define manualmente uma localização (ex: clicando em um atalho ou busca)
     */
    setLocationManual(city, uf, lat, lng, displayName = null) {
        const display = displayName || (uf ? `${city} ${uf}` : city);
        const data = {
            city,
            uf,
            display,
            lat,
            lng,
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
        const saved = this.getSavedLocation();
        if (saved) {
            this.updateDOM(saved);
        }

        // Tenta detectar automaticamente se não houver ou se for GPS
        if (navigator.geolocation) {
            navigator.permissions?.query({ name: 'geolocation' }).then(permissionStatus => {
                if (permissionStatus.state === 'granted') {
                    // Se já tiver permissão concedida, atualiza discretamente em background
                    this.detectGPS({ showLoading: !saved });
                } else if (!saved) {
                    // Primeira vez: tenta detectar
                    this.detectGPS({ showLoading: true }).catch(() => {
                        // Se falhar a permissão, define fallback suave
                    });
                }
            }).catch(() => {
                if (!saved) {
                    this.detectGPS({ showLoading: true }).catch(() => {});
                }
            });
        }
    }
};

// Auto inicializar no DOM
if (typeof window !== 'undefined') {
    window.LocationService = LocationService;
    document.addEventListener('DOMContentLoaded', () => {
        LocationService.init();
    });
}

export default LocationService;
