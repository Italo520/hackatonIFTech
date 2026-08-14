# Especificação Funcional & Técnica (SPEC) - Fase 03
## Auditoria Detalhada, Refatoração de UI/UX, Acessibilidade e Testes Automatizados das Páginas do Turista (PWA)

## 1. Visão Geral & Objetivos
O ecossistema do turista (PWA Público) é a principal porta de entrada da plataforma **Destino Inteligente**, permitindo que visitantes descubram atrativos, itinerários, eventos, alertas de segurança e suporte por IA.

Esta especificação define o plano sequencial e exaustivo para auditar, refatorar e testar unitariamente cada uma das páginas do módulo do turista, eliminando:
1. **Erros e bugs de execução**: Falhas em Blade, variáveis nulas, quebras de JavaScript, links mortos e estados vazios mal tratados.
2. **Componentes e códigos duplicados**: Extração de blocos repetidos para componentes Blade modulares e reutilizáveis (`<x-pwa.*>`).
3. **Problemas de sobreposição (Z-Index / Layout)**: Conflitos visuais entre a Bottom Navigation Bar fixa, cabeçalhos flutuantes, modais, alertas de Defesa Civil e botões flutuantes (FAB).
4. **Inconsistências de responsividade**: Ajuste fino mobile-first para telas pequenas (320px a 414px) até resoluções desktop, eliminando overflows horizontais e touch targets inadequados.
5. **Barreiras de acessibilidade (WCAG 2.1 AA)**: Inclusão de atributos ARIA, contraste cromático, navegação por teclado e semântica HTML estruturada.
6. **Lacunas de testes**: Criação e execução de testes automatizados dedicados (PHPUnit Feature & Unit tests) cobrindo 100% das páginas e comportamentos do turista.

---

## 2. Inventário Completo das Páginas do Turista

| # | Página / Módulo | Rota HTTP | Controller / Handler | View Blade | Layout Utilizado |
|---|---|---|---|---|---|
| **0** | **Layout Base & Componentes Globais** | N/A (compartilhado) | N/A | `resources/views/layouts/pwa.blade.php` | N/A |
| **1** | **Início / Home** | `GET /` | `HomeController@index` | `resources/views/pwa/home.blade.php` | `layouts/pwa` |
| **2** | **Explorar & Catálogo de Atrativos** | `GET /explorar` | `ExplorarController@index` | `resources/views/pwa/explorar.blade.php` | `layouts/pwa` |
| **3** | **Detalhe do Atrativo Turístico** | `GET /atrativo/{id}` | `AtrativoWebController@show` | `resources/views/pwa/atrativo.blade.php` | `layouts/pwa` |
| **4** | **Calendário de Eventos** | `GET /eventos` | Closure / API Consumer | `resources/views/pwa/eventos.blade.php` | `layouts/pwa` |
| **5** | **Mapa Turístico Interativo** | `GET /mapa` | `HomeController@mapa` | `resources/views/pwa/mapa.blade.php` | `layouts/pwa` |
| **6** | **Catálogo de Roteiros** | `GET /roteiros` | Closure / API Consumer | `resources/views/pwa/roteiros.blade.php` | `layouts/pwa` |
| **7** | **Detalhe & Guia do Roteiro** | `GET /roteiro/{id}` | Closure / API Consumer | `resources/views/pwa/roteiro.blade.php` | `layouts/pwa` |
| **8** | **Assistente & Guia IA** | `GET /ia` | Closure / API Consumer | `resources/views/pwa/ia.blade.php` | `layouts/pwa` |
| **9** | **Telefones & Utilidade Pública** | `GET /utilidade` | Closure / API Consumer | `resources/views/pwa/utilidade.blade.php` | `layouts/pwa` |
| **10** | **Privacidade & LGPD** | `GET /privacidade` | Closure / View Direta | `resources/views/pwa/privacidade.blade.php` | `layouts/pwa` |
| **11** | **Ponto de Entrada QR Code** | `GET /qr/{hash}` | `QrCodeController@resolve` | Redirecionamento / View | N/A |

---

## 3. Matriz de Critérios de Análise por Página

Para cada página auditada, serão inspecionadas 6 dimensões fundamentais:

```
[Código & Lógica] ──► [Duplicação & Componentes] ──► [Sobreposição & Z-Index]
         │                          │                          │
         ▼                          ▼                          ▼
[Responsividade]  ──► [Acessibilidade WCAG]     ──► [Testes Unitários & Feature]
```

### Critérios Específicos:
1. **Erros & Bugs**:
   - Falta de validação em parâmetros de entrada ou variáveis nulas em coleções vazias (`@forelse`).
   - Console JS sem erros de sintaxe ou referências a elementos ausentes no DOM.
   - Resolução robusta de URLs estáticas e assets com `asset()` e rotas nomeadas com `route()`.
2. **Componentes Duplicados**:
   - Identificação de cards de atrativo repetidos, badges de status, botões de ação e headers customizados.
   - Extração para componentes Blade dedicados (ex.: `<x-pwa.atrativo-card>`, `<x-pwa.badge>`, `<x-pwa.alerta-banner>`).
3. **Sobreposição de Componentes & Camadas**:
   - `z-index` padronizado para evitar que Bottom Nav cubra botões flutuantes ou modais fiquem abaixo de mapas.
   - Espaçamento inferior (`padding-bottom: 5rem` ou `pb-20`) obrigatório para que a Bottom Navigation Bar não oculte o rodapé do conteúdo.
4. **Responsividade Mobile & Multi-Dispositivo**:
   - Teste em resoluções 320px (iPhone SE antigo), 375px/390px (smartphones modernos), 768px (tablets) e desktops.
   - Touch targets de pelo menos 44x44px em todos os botões e links clicáveis.
   - Prevenção rigorosa de overflow horizontal (`max-w-full`, `overflow-x-hidden`).
5. **Acessibilidade (a11y)**:
   - Atributos `aria-label`, `aria-expanded`, `aria-hidden` e `role` em elementos interativos.
   - Ordem de foco do teclado lógica (`Tab` / `Shift+Tab`).
   - Contraste visual mínimo de 4.5:1 para textos normais.
   - Imagens com `alt` descritivo ou `alt=""` quando puramente decorativas.
6. **Testes Automatizados (PHPUnit)**:
   - Verificação de status HTTP (200 OK, 404 em não encontrados).
   - Verificação de renderização de componentes críticos e textos chave.
   - Validação de estados vazios (empty state), filtros e interações principais.

---

## 4. Plano de Execução Sequencial (Página por Página)

### Etapa 0: Layout Base & Componentes Compartilhados (`layouts/pwa.blade.php`)
- **Foco**: Bottom Navigation Bar, Top Bar, banner de instalação PWA, modal de emergência Defesa Civil, controle flutuante de acessibilidade e meta tags PWA.
- **Entregável**: Estrutura de layout 100% blindada contra sobreposição e componentes globais organizados.
- **Teste**: Teste de estrutura do layout e componentes fundamentais.

### Etapa 1: Início / Home (`/`)
- **Foco**: Seletor de localização, carrossel de atrativos em destaque, cards de eventos próximos, banner de alertas e atalhos rápidos.
- **Entregável**: Home responsiva, sem duplicação de cards e com transições suaves.
- **Teste**: `test_pwa_home_renders_all_sections_and_handles_empty_states`.

### Etapa 2: Explorar & Catálogo (`/explorar`)
- **Foco**: Filtro por categorias, busca textual em tempo real, seleção de município, tags de acessibilidade e grid de atrativos.
- **Entregável**: Catálogo performático com estados vazios estilizados e paginação limpa.
- **Teste**: `test_pwa_explorar_filters_by_category_and_keyword`.

### Etapa 3: Detalhes do Atrativo (`/atrativo/{id}`)
- **Foco**: Galeria de fotos, botão "Como Chegar", botão de audiodescrição (TTS), avaliações, horários e atrativos relacionados.
- **Entregável**: Página detalhada com dados protegidos contra nulos e ação de favoritar/salvar offline.
- **Teste**: `test_pwa_atrativo_detail_displays_full_information_and_handles_404`.

### Etapa 4: Calendário de Eventos (`/eventos`)
- **Foco**: Filtro por data e categoria, lista de eventos oficiais, badge "Gratuito" e botão "Adicionar ao Calendário".
- **Entregável**: Interface clara de eventos sem elementos sobrepostos.
- **Teste**: `test_pwa_eventos_page_renders_and_filters`.

### Etapa 5: Mapa Turístico Interativo (`/mapa`)
- **Foco**: Inicialização do mapa Leaflet, marcadores categorizados por cores, geolocalização do usuário, bottom sheet de informações ao tocar no pino.
- **Entregável**: Mapa responsivo sem travamentos em mobile e com botão de recentralizar GPS funcional.
- **Teste**: `test_pwa_mapa_initializes_with_markers_and_user_location`.

### Etapa 6: Catálogo de Roteiros (`/roteiros`)
- **Foco**: Cards de roteiros sugeridos com duração, custo estimado e nível de dificuldade.
- **Entregável**: Listagem fluida de itinerários oficiais.
- **Teste**: `test_pwa_roteiros_list_page_renders_correctly`.

### Etapa 7: Detalhe do Roteiro (`/roteiro/{id}`)
- **Foco**: Linha do tempo sequencial dos pontos turísticos, cálculo de distâncias, instruções de trajeto e modo offline.
- **Entregável**: Guia passo a passo do itinerário com mapa integrado.
- **Teste**: `test_pwa_roteiro_detail_displays_timeline_and_stops`.

### Etapa 8: Assistente IA do Turista (`/ia`)
- **Foco**: Chat interativo, streaming de respostas, chips de sugestões rápidas, histórico e botões para abrir atrativos recomendados.
- **Entregável**: Interface de chat fluida com auto-scroll, sanitização de PII e acessibilidade no leitor.
- **Teste**: `test_pwa_ia_chat_interface_renders_and_dispatches_messages`.

### Etapa 9: Telefones Úteis & Utilidade Pública (`/utilidade`)
- **Foco**: Botões de discagem rápida (`tel:`), contatos de emergência (Polícia, SAMU, Bombeiros, Defesa Civil, Postos de Saúde) e endereços com rota.
- **Entregável**: Guia de suporte com tipografia legível e contraste alto para emergências.
- **Teste**: `test_pwa_utilidade_page_lists_emergency_contacts_with_tel_links`.

### Etapa 10: Privacidade & LGPD (`/privacidade`)
- **Foco**: Política de dados do turista, anonimização de localização e botão de exclusão de dados em cache local.
- **Entregável**: Texto estruturado com sumário navegável e conformidade com a LGPD.
- **Teste**: `test_pwa_privacidade_page_renders_lgpd_content`.

### Etapa 11: Leitor e Redirecionamento QR Code (`/qr/{hash}`)
- **Foco**: Resolução instantânea do QR code escaneado nas placas físicas, incremento de métricas e redirecionamento correto.
- **Entregável**: Fluxo de resolução confiável com tratamento de QR code expirado ou inválido.
- **Teste**: `test_pwa_qr_code_resolution_and_redirect`.

---

## 5. Critérios de Aceite (Falsificabilidade)
- [ ] Todas as 11 páginas/módulos do turista listadas no inventário foram auditadas contra os 6 critérios de qualidade.
- [ ] Não há sobreposições de layout ou elementos inacessíveis por trás da Bottom Navigation Bar ou Modais.
- [ ] Componentes duplicados identificados foram refatorados em componentes Blade reutilizáveis.
- [ ] Acessibilidade validada com semântica HTML adequada, `aria-labels` e suporte a navegação por teclado.
- [ ] Nova suíte de testes de integração (`PwaPagesTest.php` / testes dedicados) criada cobrindo cada uma das páginas do turista.
- [ ] Todos os testes (novos e existentes) executam e passam com 100% de sucesso via PHPUnit (`php artisan test`).
