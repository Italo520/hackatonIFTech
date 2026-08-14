@extends('layouts.pwa')

@section('content')
<div class="d-flex flex-column h-100 bg-light" style="margin-top: -1rem; min-height: calc(100vh - 80px);">
    <!-- Header Especial IA -->
    <div class="p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #005f73, #0a9396); border-bottom-left-radius: 24px; border-bottom-right-radius: 24px;">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-opacity-25" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                <i class="bi bi-robot fs-4"></i>
            </div>
            <div>
                <h1 class="fw-bold fs-5 mb-0" style="letter-spacing: -0.01em;">Assistente de Viagem IA</h1>
                <span class="small text-white-50"><i class="bi bi-geo-alt-fill text-warning"></i> Conectado a <span class="current-location-display">Sua Localização</span></span>
            </div>
        </div>
        <p class="small text-white opacity-75 mt-3 mb-1">Guia inteligente com inteligência geográfica e recomendações locais.</p>
    </div>

    <!-- Abas: Chat vs Gerador de Roteiro -->
    <div class="px-3" style="margin-top: -20px; z-index: 10;">
        <div class="bg-white rounded-pill shadow-sm border p-1 d-flex w-100">
            <button class="btn btn-primary rounded-pill flex-grow-1 fw-bold btn-sm tab-ia-btn active" id="tab-chat-btn" style="min-height: 36px;">Conversar</button>
            <button class="btn btn-link text-decoration-none text-secondary rounded-pill flex-grow-1 fw-semibold btn-sm tab-ia-btn" id="tab-roteiro-btn" style="min-height: 36px;">Criar Roteiro</button>
        </div>
    </div>

    <!-- Chat View -->
    <div id="view-chat" class="flex-grow-1 px-3 py-4 overflow-auto no-scrollbar d-flex flex-column gap-3">
        <!-- Boas-vindas -->
        <div class="d-flex gap-2 w-100" style="max-width: 92%;">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background-color: #005f73; margin-top: 4px;">
                <i class="bi bi-robot small"></i>
            </div>
            <div class="bg-white p-3 shadow-sm border text-dark" style="border-radius: 18px; border-top-left-radius: 4px; font-size: 0.9rem;">
                Olá! Sou seu guia virtual com IA para <strong class="text-primary current-location-display">sua localização</strong>. Você pode me perguntar:
                <div class="d-flex flex-column gap-1 mt-2">
                    <button class="btn btn-sm btn-light border text-start rounded-pill px-3 py-1 text-primary suggestion-chip" style="font-size: 0.8rem;">
                        <i class="bi bi-chat-quote-fill me-1"></i> O que comer e restaurantes típicos perto daqui?
                    </button>
                    <button class="btn btn-sm btn-light border text-start rounded-pill px-3 py-1 text-primary suggestion-chip" style="font-size: 0.8rem;">
                        <i class="bi bi-chat-quote-fill me-1"></i> Melhores passeios e praias para família?
                    </button>
                    <button class="btn btn-sm btn-light border text-start rounded-pill px-3 py-1 text-primary suggestion-chip" style="font-size: 0.8rem;">
                        <i class="bi bi-chat-quote-fill me-1"></i> Roteiro de 1 dia com monumentos e história?
                    </button>
                </div>
            </div>
        </div>

        <div id="chat-messages-container" class="d-flex flex-column gap-3"></div>

        @guest
            <div class="card border-0 rounded-4 shadow-sm p-4 text-center my-2" style="background: linear-gradient(135deg, rgba(0,95,115,0.06), rgba(10,147,150,0.12)); border: 1px dashed rgba(0,95,115,0.3) !important;">
                <div class="rounded-circle d-inline-flex p-3 mx-auto mb-2 text-primary" style="background: rgba(0,95,115,0.1); width: 52px; height: 52px; align-items: center; justify-content: center;">
                    <i class="bi bi-stars fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Guia IA com Inteligência Local</h5>
                <p class="text-secondary small mb-3">Crie sua conta gratuita de <strong>Turista</strong> ou faça login para conversar em tempo real com a IA, pedir sugestões sob medida e salvar roteiros.</p>
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Fazer Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold bg-white">
                        <i class="bi bi-person-plus me-1"></i> Criar Conta Grátis
                    </a>
                </div>
            </div>
        @endguest
    </div>

    <!-- View Gerar Roteiro -->
    <div id="view-roteiro" class="d-none px-3 py-4">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
            <h3 class="fs-6 fw-bold mb-3">Gerador Automático de Roteiro</h3>
            <p class="small text-secondary mb-3">A IA montará uma sequência inteligente de paradas otimizada por proximidade geográfica.</p>
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Estilo de Viagem</label>
                <select id="roteiro-tema" class="form-select rounded-pill">
                    <option value="Praias, Piscinas Naturais e Sol">Praias, Piscinas Naturais e Sol</option>
                    <option value="Gastronomia Regional e Frutos do Mar">Gastronomia Regional e Sabores</option>
                    <option value="História, Monumentos e Cultura">História, Monumentos e Cultura</option>
                    <option value="Ecoturismo e Aventura">Ecoturismo e Aventura</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Tempo Disponível</label>
                <select id="roteiro-tempo" class="form-select rounded-pill">
                    <option value="120">Meio período (~2 a 3 horas)</option>
                    <option value="240">Dia inteiro (~4 a 6 horas)</option>
                    <option value="480">Fim de semana completo</option>
                </select>
            </div>

            @auth
                <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" id="btn-gerar-roteiro-ia">
                    <i class="bi bi-stars me-1"></i> Gerar Roteiro com IA
                </button>
            @else
                <div class="text-center p-3 bg-light rounded-4 border">
                    <p class="small text-muted mb-2">Faça login para gerar roteiros personalizados automaticamente.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 btn-sm fw-bold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar para Gerar Roteiro
                    </a>
                </div>
            @endauth

            <div id="roteiro-resultado-box" class="mt-4 d-none"></div>
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-3 bg-white border-top pb-4" id="chat-input-wrapper">
        @auth
            <form id="ia-chat-form" class="position-relative d-flex align-items-center" aria-label="Formulário de Mensagem do Assistente IA">
                <input type="text" id="ia-prompt-input" class="form-control rounded-pill bg-light border-0 ps-4 pe-5 shadow-none" placeholder="Pergunte sobre praias, locais, dicas..." aria-label="Digite sua mensagem para o assistente IA" style="height: 48px;" required>
                <button type="submit" id="ia-send-btn" class="btn btn-primary position-absolute end-0 rounded-circle d-flex align-items-center justify-content-center me-1" style="width: 40px; height: 40px; z-index: 5;" aria-label="Enviar mensagem">
                    <i class="bi bi-send-fill small" aria-hidden="true"></i>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-secondary" style="font-size: 0.68rem;">Recomendações geradas com base na sua localização atual.</span>
            </div>
        @else
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between p-2.5 px-3 rounded-4 bg-light border gap-2">
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="bi bi-lock-fill text-primary fs-5"></i>
                    <span>Cadastre-se como <strong>Turista</strong> para conversar com o Guia IA.</span>
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold text-nowrap">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold text-nowrap shadow-sm">
                        Cadastrar Grátis
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages-container');
        const chatForm = document.getElementById('ia-chat-form');
        const promptInput = document.getElementById('ia-prompt-input');
        const sendBtn = document.getElementById('ia-send-btn');

        const tabChatBtn = document.getElementById('tab-chat-btn');
        const tabRoteiroBtn = document.getElementById('tab-roteiro-btn');
        const viewChat = document.getElementById('view-chat');
        const viewRoteiro = document.getElementById('view-roteiro');
        const chatInputWrapper = document.getElementById('chat-input-wrapper');

        // Histórico de Conversa
        let chatHistory = [];

        // Função TTS
        window.speakText = function(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const cleanText = text.replace(/\*\*/g, '').replace(/<[^>]*>?/gm, '');
                const utterance = new SpeechSynthesisUtterance(cleanText);
                utterance.lang = 'pt-BR';
                window.speechSynthesis.speak(utterance);
            } else {
                alert('Seu navegador não suporta leitura em voz alta.');
            }
        };

        // Alternar abas
        tabChatBtn.addEventListener('click', function() {
            tabChatBtn.classList.add('btn-primary', 'active');
            tabChatBtn.classList.remove('btn-link', 'text-secondary');
            tabRoteiroBtn.classList.add('btn-link', 'text-secondary');
            tabRoteiroBtn.classList.remove('btn-primary', 'active');
            viewChat.classList.remove('d-none');
            viewRoteiro.classList.add('d-none');
            chatInputWrapper.classList.remove('d-none');
        });

        tabRoteiroBtn.addEventListener('click', function() {
            tabRoteiroBtn.classList.add('btn-primary', 'active');
            tabRoteiroBtn.classList.remove('btn-link', 'text-secondary');
            tabChatBtn.classList.add('btn-link', 'text-secondary');
            tabChatBtn.classList.remove('btn-primary', 'active');
            viewChat.classList.add('d-none');
            viewRoteiro.classList.remove('d-none');
            chatInputWrapper.classList.add('d-none');
        });

        function renderFontes(fontes) {
            if (!fontes || fontes.length === 0) return '';
            return `
                <div class="mt-3 pt-2 border-top small text-secondary">
                    <strong class="d-block mb-1 text-dark" style="font-size: 0.75rem;">Sugestões de locais:</strong>
                    <div class="d-flex flex-column gap-1">
                        ${fontes.map(f => `
                            <a href="/atrativo/${f.id}" class="badge bg-light text-primary border text-decoration-none text-start p-2 rounded-3">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i> ${f.nome} <span class="text-muted">(${f.cidade || f.tipo})</span>
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function appendMessage(text, isUser = false, fontes = [], msgId = null) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `d-flex gap-2 w-100 ${isUser ? 'align-self-end flex-row-reverse' : ''}`;
            msgDiv.style.maxWidth = '92%';
            if (msgId) msgDiv.id = msgId;

            if (isUser) {
                msgDiv.innerHTML = `
                    <div class="p-3 shadow-sm text-white" style="background-color: #005f73; border-radius: 18px; border-top-right-radius: 4px; font-size: 0.9rem;">
                        ${escapeHtml(text)}
                    </div>
                `;
            } else {
                const fontesHtml = renderFontes(fontes);
                const formattedText = typeof marked !== 'undefined' ? marked.parse(text) : text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
                const safeText = text ? escapeHtml(text).replace(/'/g, "\\'") : '';

                msgDiv.innerHTML = `
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background-color: #005f73; margin-top: 4px;">
                        <i class="bi bi-robot small"></i>
                    </div>
                    <div class="bg-white p-3 shadow-sm border text-dark w-100 position-relative" style="border-radius: 18px; border-top-left-radius: 4px; font-size: 0.9rem;">
                        <button onclick="window.speakText('${safeText}')" class="btn btn-sm btn-light border rounded-circle position-absolute top-0 end-0 m-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 30px; height: 30px; padding: 0;">
                            <i class="bi bi-volume-up-fill text-primary" style="font-size: 0.9rem;"></i>
                        </button>
                        <div id="${msgId ? msgId + '-content' : ''}" class="pt-1 pe-4">${formattedText}</div>
                        <div id="${msgId ? msgId + '-fontes' : ''}">${fontesHtml}</div>
                    </div>
                `;
            }

            chatContainer.appendChild(msgDiv);
            viewChat.scrollTop = viewChat.scrollHeight;
        }

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        async function handleSend(pergunta) {
            if (!pergunta || pergunta.trim().length === 0) return;

            appendMessage(pergunta, true);
            promptInput.value = '';

            chatHistory.push({ role: 'user', text: pergunta });
            if (chatHistory.length > 8) chatHistory.shift(); // Manter apenas as últimas 8 mensagens (4 trocas)

            // Loading message
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'ai-loading-indicator';
            loadingDiv.className = 'd-flex gap-2 w-100';
            loadingDiv.innerHTML = `
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background-color: #005f73; margin-top: 4px;">
                    <i class="bi bi-robot small"></i>
                </div>
                <div class="bg-white p-3 shadow-sm border text-muted" style="border-radius: 18px; border-top-left-radius: 4px; font-size: 0.85rem;">
                    <span class="spinner-border spinner-border-sm me-2 text-primary" role="status"></span>
                    Consultando guia e localização...
                </div>
            `;
            chatContainer.appendChild(loadingDiv);
            viewChat.scrollTop = viewChat.scrollHeight;

            const savedLoc = window.LocationService ? window.LocationService.getSavedLocation() : null;

            try {
                const res = await fetch('/api/v1/ia/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        pergunta: pergunta,
                        idioma: 'pt-BR',
                        userLocation: savedLoc || {},
                        historico: chatHistory
                    })
                });

                document.getElementById('ai-loading-indicator')?.remove();

                if (res.ok) {
                    const reader = res.body.getReader();
                    const decoder = new TextDecoder("utf-8");
                    let accumulatedText = "";
                    let fontesData = [];
                    
                    const msgId = 'msg-' + Date.now();
                    appendMessage('', false, [], msgId);
                    const msgContentDiv = document.getElementById(msgId + '-content');
                    const msgFontesDiv = document.getElementById(msgId + '-fontes');

                    let isDone = false;
                    while (!isDone) {
                        const { value, done } = await reader.read();
                        if (done) break;
                        
                        const chunk = decoder.decode(value, {stream: true});
                        const lines = chunk.split('\n');
                        
                        for (let i = 0; i < lines.length; i++) {
                            const line = lines[i].trim();
                            if (line.startsWith('event: done')) {
                                isDone = true;
                            } else if (line.startsWith('data: ')) {
                                const dataStr = line.substring(6);
                                if (dataStr === 'done' || dataStr === '{}') continue;
                                
                                try {
                                    const dataObj = JSON.parse(dataStr);
                                    if (dataObj.fontes) {
                                        fontesData = dataObj.fontes;
                                        msgFontesDiv.innerHTML = renderFontes(fontesData);
                                    } else if (dataObj.chunk) {
                                        accumulatedText += dataObj.chunk + ' ';
                                        msgContentDiv.innerHTML = typeof marked !== 'undefined' ? marked.parse(accumulatedText) : accumulatedText;
                                        viewChat.scrollTop = viewChat.scrollHeight;
                                    }
                                } catch (e) {
                                    // ignore parse errors for partial chunks if any
                                }
                            }
                        }
                    }
                    
                    chatHistory.push({ role: 'model', text: accumulatedText });
                } else {
                    appendMessage('Desculpe, tive uma oscilação na consulta da IA. Mas você pode conferir as opções na aba Explorar!', false);
                }
            } catch (err) {
                document.getElementById('ai-loading-indicator')?.remove();
                appendMessage('Você está conectado à sua localização real! Explore os atrativos próximos na tela de mapa e exploração.', false);
            }
        }

        if (chatForm && promptInput) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleSend(promptInput.value);
            });
        }

        document.querySelectorAll('.suggestion-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                const text = this.textContent.trim();
                @guest
                    const guestCard = document.querySelector('#view-chat .card');
                    if (guestCard) {
                        guestCard.scrollIntoView({ behavior: 'smooth' });
                        guestCard.classList.add('shadow', 'border-primary');
                        setTimeout(() => guestCard.classList.remove('shadow', 'border-primary'), 1500);
                    }
                @else
                    handleSend(text);
                @endguest
            });
        });

        // Gerador de Roteiro
        document.getElementById('btn-gerar-roteiro-ia')?.addEventListener('click', async function() {
            const btn = this;
            const tema = document.getElementById('roteiro-tema').value;
            const duracao = parseInt(document.getElementById('roteiro-tempo').value);
            const savedLoc = window.LocationService ? window.LocationService.getSavedLocation() : null;
            const cidade = savedLoc?.city || 'João Pessoa';

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Gerando Roteiro Otimizado...';

            try {
                const res = await fetch('/api/v1/ia/roteiro', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        tema: tema,
                        duracao_max: duracao,
                        cidade: cidade,
                        lat: savedLoc?.lat || -7.1153,
                        lng: savedLoc?.lng || -34.8641
                    })
                });

                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-stars me-1"></i> Gerar Novo Roteiro';

                const box = document.getElementById('roteiro-resultado-box');
                box.classList.remove('d-none');

                if (res.ok) {
                    const data = await res.json();
                    let itensHtml = '';
                    if (data.itens && data.itens.length > 0) {
                        data.itens.forEach(item => {
                            itensHtml += `
                                <div class="bg-white p-3 rounded-3 small shadow-sm border-start border-4 border-primary">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark"><i class="bi bi-geo-alt text-primary me-1"></i> ${item.nome}</strong>
                                        <span class="badge bg-light text-secondary"><i class="bi bi-clock me-1"></i> ${item.tempo_estimado} min</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Recomendado pela IA</span>
                                </div>
                            `;
                        });
                    } else {
                        itensHtml = '<div class="alert alert-warning py-2 small">Nenhum local específico foi encontrado, mas você pode explorar a região livremente!</div>';
                    }

                    box.innerHTML = `
                        <div class="alert alert-success rounded-4 border-0 shadow-sm p-3" style="background-color: #f8f9fa;">
                            <h4 class="fw-bold fs-6 mb-1 text-dark"><i class="bi bi-map-fill text-primary me-1"></i> ${data.titulo}</h4>
                            <p class="small text-secondary mb-2">Duração: ${data.duracao} min | <i class="bi bi-cash"></i> Orçamento: R$ ${data.orcamento}</p>
                            
                            <!-- Mapa Leaflet Real -->
                            <div id="mapa-roteiro-ia" class="w-100 bg-light border rounded-3 mt-3 shadow-inner" style="height: 200px; z-index: 1;"></div>

                            <hr class="my-3 opacity-25">
                            <div class="d-flex flex-column gap-2 mt-2">
                                ${itensHtml}
                            </div>
                            <a href="/roteiros" class="btn btn-primary w-100 rounded-pill btn-sm mt-3 fw-bold shadow-sm">Salvar e Iniciar Roteiro</a>
                        </div>
                    `;

                    // Inicializar o Leaflet Map se houver coordenadas
                    setTimeout(() => {
                        const mapContainer = document.getElementById('mapa-roteiro-ia');
                        if (mapContainer && data.itens && data.itens.length > 0) {
                            // Encontra a primeira coordenada válida
                            const firstItem = data.itens.find(i => i.lat && i.lng);
                            const center = firstItem ? [firstItem.lat, firstItem.lng] : [-7.1153, -34.8641];
                            
                            const map = L.map('mapa-roteiro-ia').setView(center, 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap'
                            }).addTo(map);

                            // Adicionar Marcadores
                            const bounds = [];
                            data.itens.forEach((item, index) => {
                                if (item.lat && item.lng) {
                                    bounds.push([item.lat, item.lng]);
                                    L.marker([item.lat, item.lng])
                                     .bindPopup(`<b>${index + 1}. ${item.nome}</b><br>${item.tempo_estimado} min`)
                                     .addTo(map);
                                }
                            });
                            
                            if (bounds.length > 1) {
                                map.fitBounds(bounds, { padding: [20, 20] });
                            }
                        }
                    }, 200);
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-stars me-1"></i> Gerar Roteiro com IA';
            }
        });
    });
</script>
@endpush

