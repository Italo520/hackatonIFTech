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

            <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" id="btn-gerar-roteiro-ia">
                <i class="bi bi-stars me-1"></i> Gerar Roteiro com IA
            </button>

            <div id="roteiro-resultado-box" class="mt-4 d-none"></div>
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-3 bg-white border-top pb-4" id="chat-input-wrapper">
        <form id="ia-chat-form" class="position-relative d-flex align-items-center">
            <input type="text" id="ia-prompt-input" class="form-control rounded-pill bg-light border-0 ps-4 pe-5 shadow-none" placeholder="Pergunte sobre praias, locais, dicas..." style="height: 48px;" required>
            <button type="submit" id="ia-send-btn" class="btn btn-primary position-absolute end-0 rounded-circle d-flex align-items-center justify-content-center me-1" style="width: 40px; height: 40px; z-index: 5;">
                <i class="bi bi-send-fill small"></i>
            </button>
        </form>
        <div class="text-center mt-2">
            <span class="text-secondary" style="font-size: 0.68rem;">Recomendações geradas com base na sua localização atual.</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

        function appendMessage(text, isUser = false, fontes = []) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `d-flex gap-2 w-100 ${isUser ? 'align-self-end flex-row-reverse' : ''}`;
            msgDiv.style.maxWidth = '92%';

            if (isUser) {
                msgDiv.innerHTML = `
                    <div class="p-3 shadow-sm text-white" style="background-color: #005f73; border-radius: 18px; border-top-right-radius: 4px; font-size: 0.9rem;">
                        ${escapeHtml(text)}
                    </div>
                `;
            } else {
                let fontesHtml = '';
                if (fontes && fontes.length > 0) {
                    fontesHtml = `
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

                // Utilizando marked.js para renderizar markdown completo (títulos, listas, negrito, etc)
                const formattedText = typeof marked !== 'undefined' ? marked.parse(text) : text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');

                msgDiv.innerHTML = `
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 34px; height: 34px; background-color: #005f73; margin-top: 4px;">
                        <i class="bi bi-robot small"></i>
                    </div>
                    <div class="bg-white p-3 shadow-sm border text-dark w-100 position-relative" style="border-radius: 18px; border-top-left-radius: 4px; font-size: 0.9rem;">
                        <button onclick="window.speakText('${escapeHtml(text).replace(/'/g, "\\'")}')" class="btn btn-sm btn-light border rounded-circle position-absolute top-0 end-0 m-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 30px; height: 30px; padding: 0;">
                            <i class="bi bi-volume-up-fill text-primary" style="font-size: 0.9rem;"></i>
                        </button>
                        <div class="pt-1 pe-4">${formattedText}</div>
                        ${fontesHtml}
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
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        pergunta: pergunta,
                        historico: chatHistory,
                        cidade: savedLoc?.city || 'João Pessoa',
                        uf: savedLoc?.uf || 'PB',
                        lat: savedLoc?.lat || -7.1153,
                        lng: savedLoc?.lng || -34.8641,
                        idioma: 'pt-BR'
                    })
                });

                document.getElementById('ai-loading-indicator')?.remove();

                if (res.ok) {
                    const data = await res.json();
                    chatHistory.push({ role: 'model', text: data.resposta || '' });
                    appendMessage(data.resposta || 'Aqui estão algumas sugestões!', false, data.fontes || []);
                } else {
                    appendMessage('Desculpe, tive uma oscilação na consulta da IA. Mas você pode conferir as opções na aba Explorar!', false);
                }
            } catch (err) {
                document.getElementById('ai-loading-indicator')?.remove();
                appendMessage('Você está conectado à sua localização real! Explore os atrativos próximos na tela de mapa e exploração.', false);
            }
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleSend(promptInput.value);
        });

        document.querySelectorAll('.suggestion-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                handleSend(this.textContent.trim());
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
                            
                            <!-- Mock de Mapa (Leaflet Placeholder) -->
                            <div class="w-100 bg-light border rounded-3 mt-3 d-flex flex-column align-items-center justify-content-center text-secondary position-relative shadow-inner" style="height: 180px; background-image: radial-gradient(#ccc 1px, transparent 1px); background-size: 15px 15px; overflow: hidden;">
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.9));"></div>
                                <i class="bi bi-pin-map-fill fs-2 mb-1 text-primary opacity-75" style="z-index: 2;"></i>
                                <span class="small fw-bold text-dark" style="z-index: 2;">Integração Leaflet/Mapbox</span>
                                <span class="text-muted text-center px-2" style="font-size: 0.65rem; z-index: 2;">(Seu amigo inserirá o código do mapa interativo aqui)</span>
                            </div>

                            <hr class="my-3 opacity-25">
                            <div class="d-flex flex-column gap-2 mt-2">
                                ${itensHtml}
                            </div>
                            <a href="/roteiros" class="btn btn-primary w-100 rounded-pill btn-sm mt-3 fw-bold shadow-sm">Salvar e Iniciar Roteiro</a>
                        </div>
                    `;
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-stars me-1"></i> Gerar Roteiro com IA';
            }
        });
    });
</script>
@endpush

