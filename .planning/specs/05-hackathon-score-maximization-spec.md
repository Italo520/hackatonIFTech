# 📋 Especificação e Planejamento: Maximização da Avaliação do Hackathon (IFTech)
## Projeto: Destino Turístico Municipal — Turismo Inteligente
### Documento de Planejamento GSD (Spec Phase)

**Referência de Entrada:** `avaliacao_hackathon.md` & `scratchpad_x3g5kflg.md`  
**Objetivo Geral:** Elevar a pontuação do projeto de **~96/160 pts (60%)** para **165+/180 pts (90%+)** no julgamento final da banca avaliadora.  
**Estratégia de Execução:** Sequenciamento rigoroso do **mais fácil/rápido de revisar e consertar** para o **mais difícil/complexo**, dividido em **Fases**, **Waves Atômicas**, **Tasks** e **Subtasks**.

---

## 🗺️ Visão Geral das Fases de Implementação

| Fase | Nível de Complexidade | Foco Principal | Critérios Impactados | Ganho Estimado |
|---|---|---|---|---|
| **Fase 1** | 🟢 Muito Fácil / Rápido | Segurança, RBAC nas Rotas Admin e Sanitização | Critério 4 (Segurança & LGPD), Critério 7 | +4.0 pts |
| **Fase 2** | 🟢 Muito Fácil / Rápido | README.md Oficial, Docs de Arquitetura e Fix de Imagens | Critério 9 (Documentação), Critério 3 (UX) | +6.0 pts |
| **Fase 3** | 🟡 Fácil / Rápido | Fix da Lista de Roteiros (`/roteiros`) e Persistência LGPD | Critério 1 (Protótipo), Critério 4 (LGPD) | +3.5 pts |
| **Fase 4** | 🟠 Médio | Mapa de Calor Real, Dashboards Executivos e Métricas ESG | Critério 6 (Indicadores), Critério 7, Critério 8 | +7.0 pts |
| **Fase 5** | 🟠 Médio / Avançado | Módulo de Avaliações, Moderação e Análise de Sentimento com IA | Critério 1 (Protótipo), Critério 5 (IA), Critério 6 | +6.5 pts |
| **Fase 6** | 🔴 Avançado / Inovação | Tradução Multilíngue com IA e PWA Service Worker Offline | Critério 2 (Inovação - 30 pts), Critério 5 (IA) | +8.0 pts |

---

## 📌 Detalhamento Minucioso das Fases, Waves, Tasks e Subtasks

```
FASE 1: Segurança, RBAC e Higiene de Ambiente (Muito Fácil)
 ├── Wave 1.1: Proteção de Rotas com Middleware CheckRole e RBAC
 │    ├── Task 1.1.1: Proteger rotas admin no web.php com middleware auth e role
 │    ├── Task 1.1.2: Proteger rotas de empreendedor e tratar redirects amigáveis
 │    └── Task 1.1.3: Testes de autorização (403 Forbidden para turista em rotas admin)
 └── Wave 1.2: Higiene de Ambiente e Headers de Segurança
      ├── Task 1.2.1: Ajustar configuração de APP_DEBUG e variáveis de ambiente
      └── Task 1.2.2: Ajustar SecurityHeaders (CSP) para não conflitar com CDNs e Leaflet

FASE 2: Documentação Oficial e Correções Visuais (Muito Fácil)
 ├── Wave 2.1: README.md Oficial e Documentação do Projeto
 │    ├── Task 2.1.1: Criar README.md completo e institucional em Português
 │    ├── Task 2.1.2: Documentar modelo de dados, fluxos e credenciais de demonstração
 │    └── Task 2.1.3: Gerar documentação interativa de endpoints API (Scribe/Swagger)
 └── Wave 2.2: Correção de Imagens e Fallbacks no Frontend
      ├── Task 2.2.1: Corrigir URLs quebradas em Atrativo.php e Seeders
      └── Task 2.2.2: Implementar fallback automático com onerror nas views Blade

FASE 3: Roteiros Turísticos e Consentimentos LGPD (Fácil)
 ├── Wave 3.1: Correção e Integração da Página de Roteiros (/roteiros)
 │    ├── Task 3.1.1: Carregar roteiros ativos do banco de dados na rota /roteiros
 │    ├── Task 3.1.2: Integrar rota no mapa Leaflet com traçado OSRM no detalhe (/roteiro/{id})
 │    └── Task 3.1.3: Botão de ação rápida "Gerar Roteiro com IA"
 └── Wave 3.2: Persistência Real de Consentimentos LGPD
      ├── Task 3.2.1: Criar endpoint API e persistência de consentimentos granulares
      └── Task 3.2.2: Conectar switches da view /privacidade ao backend com feedback visual

FASE 4: Inteligência Gerencial, Mapa de Calor e Dashboards (Médio)
 ├── Wave 4.1: Mapa de Calor Real com Agregação e Anonimização LGPD
 │    ├── Task 4.1.1: Refatorar AdminController::heatmapData() para ler AnalyticEvent
 │    ├── Task 4.1.2: Aplicar supressão estatística (< 5 interações) conforme LGPD
 │    └── Task 4.1.3: Ajustar renderização do Leaflet.heat no dashboard
 └── Wave 4.2: Gráficos de Inteligência Gerencial e Métricas ESG
      ├── Task 4.2.1: Integrar Chart.js no dashboard com evolução temporal de interações IA
      ├── Task 4.2.2: Adicionar gráfico de distribuição por categoria e município
      ├── Task 4.2.3: Criar card de Indicadores ESG (impacto ambiental e inclusão local)
      └── Task 4.2.4: Enriquecer exportação de relatórios (CSV consolidado para editais)

FASE 5: Avaliações do Turista, Moderação e Análise de Sentimento IA (Médio/Avançado)
 ├── Wave 5.1: Módulo de Avaliações no PWA do Turista
 │    ├── Task 5.1.1: Criar migration/model Avaliacao e endpoint POST /api/v1/avaliacoes
 │    ├── Task 5.1.2: Formulário interativo de avaliação (1-5 estrelas + comentário) em atrativo.blade.php
 │    └── Task 5.1.3: Exibir lista de avaliações aprovadas e nota média calculada
 └── Wave 5.2: Análise de Sentimento com IA e Painel de Moderação
      ├── Task 5.2.1: Implementar IAService::analisarSentimento() via Gemini 3.5-flash
      ├── Task 5.2.2: Criar tela administrativa de moderação (/admin/avaliacoes)
      └── Task 5.2.3: Integrar métricas de sentimento no dashboard administrativo

FASE 6: Inovações Avançadas — Tradução com IA e PWA Offline (Avançado)
 ├── Wave 6.1: Tradução Multilíngue Dinâmica com IA (PT / EN / ES)
 │    ├── Task 6.1.1: Adicionar seletor de idioma no header do PWA
 │    ├── Task 6.1.2: Tradução automática de conteúdo de atrativos via IA com cache
 │    └── Task 6.1.3: Ajustar prompt do assistente IA para responder no idioma ativo
 └── Wave 6.2: Service Worker e Resiliência Offline
      ├── Task 6.2.1: Registrar Service Worker (sw.js) com cache-first de assets estáticos
      └── Task 6.2.2: Tela de contingência offline com telefones de emergência e rotas salvas
```

---

## 🔍 Detalhamento por Fase

---

### 🟢 FASE 1: Segurança, RBAC e Higiene de Ambiente
**Nível:** Muito Fácil / Rápido  
**Tempo Estimado:** Curto  
**Critérios Atendidos:** Critério 4 (Segurança da Informação e LGPD - 10 pts), Critério 7 (Qualidade Técnica - 20 pts)

#### Wave 1.1: Proteção de Rotas com Middleware `CheckRole` e RBAC
- **Task 1.1.1: Proteger rotas administrativas no `routes/web.php`**
  - *Subtask 1.1.1.1:* Agrupar as rotas `/admin/*` e `/dashboard` sob o middleware `['auth', 'role:super_admin,prefeito,secretario,gestor_conteudo,gestor_cadastros']`.
  - *Subtask 1.1.1.2:* Garantir que a rota `/admin/prestadores` seja restrita a `super_admin,gestor_cadastros,secretario`.
- **Task 1.1.2: Proteger rotas do módulo de empreendedores**
  - *Subtask 1.1.2.1:* Agrupar `/parceiro/painel` e `/parceiro/atrativo` sob `['auth', 'role:empreendedor,super_admin']`.
  - *Subtask 1.1.2.2:* Customizar tela de erro 403 (`resources/views/errors/403.blade.php`) com design amigável e link de retorno ao PWA.
- **Task 1.1.3: Testes automatizados de controle de acesso**
  - *Subtask 1.1.3.1:* Criar teste em `tests/Feature/Auth/RbacMiddlewareTest.php` validando que turistas recebem 403 ao acessar `/admin/dashboard` e gestores recebem 200.

#### Wave 1.2: Higiene de Ambiente e Headers de Segurança
- **Task 1.2.1: Ajustar configurações de ambiente e logs**
  - *Subtask 1.2.1.1:* Assegurar que `APP_DEBUG=false` seja a recomendação padrão em produção, documentando a flag no `.env.example`.
  - *Subtask 1.2.1.2:* Criar helper de verificação de chave de IA em `IAService` com log amigável caso não esteja configurada.
- **Task 1.2.2: Revisão de Security Headers (CSP)**
  - *Subtask 1.2.2.1:* Ajustar `SecurityHeaders.php` para permitir CDNs legítimas do projeto (Leaflet, Bootstrap Icons, Unsplash, Google Fonts, OpenStreetMap tiles).

---

### 🟢 FASE 2: Documentação Oficial e Correções Visuais
**Nível:** Muito Fácil / Rápido  
**Tempo Estimado:** Curto  
**Critérios Atendidos:** Critério 9 (Qualidade da Documentação - 10 pts), Critério 3 (Experiência do Usuário - 10 pts), Critério 8 (Viabilidade - 10 pts)

#### Wave 2.1: README.md Oficial e Documentação do Projeto
- **Task 2.1.1: Criar README.md completo e profissional**
  - *Subtask 2.1.1.1:* Escrever cabeçalho com badges, visão geral do projeto e resumo executivo alinhado ao tema do edital.
  - *Subtask 2.1.1.2:* Seção "Arquitetura e Tecnologias" detalhando Laravel 11, Bootstrap 5, PostgreSQL, Leaflet, OSRM e Gemini 3.5-flash.
  - *Subtask 2.1.1.3:* Seção "Guia de Instalação e Execução" com passo a passo claro para Docker Compose e ambiente local.
  - *Subtask 2.1.1.4:* Tabela de Usuários e Perfis de Teste (`super_admin@demo.com`, `turista@demo.com`, etc.).
  - *Subtask 2.1.1.5:* Mapeamento de conformidade item por item com os 11 critérios de avaliação da banca.
- **Task 2.1.2: Documentação da API com Scribe**
  - *Subtask 2.1.2.1:* Executar `php artisan scribe:generate` para produzir documentação HTML interativa dos endpoints.
  - *Subtask 2.1.2.2:* Adicionar rota e link no painel admin para visualização rápida da documentação da API.

#### Wave 2.2: Correção de Imagens e Fallbacks no Frontend
- **Task 2.2.1: Corrigir URLs quebradas em `Atrativo.php` e `DatabaseSeeder.php`**
  - *Subtask 2.2.1.1:* Atualizar URLs do Unsplash no seeder para imagens de alta resolução que estejam ativas (João Pessoa, Bonito, Recife, Natal).
  - *Subtask 2.2.1.2:* Atualizar método `resolveFallbackImage()` no Model `Atrativo.php` para todas as categorias existentes.
- **Task 2.2.2: Adicionar fallback robusto com `onerror` nas views Blade**
  - *Subtask 2.2.2.1:* Adicionar `onerror="this.onerror=null;this.src='/images/placeholder-turismo.jpg';"` nos templates `explorar.blade.php`, `home.blade.php` e `atrativo.blade.php`.

---

### 🟡 FASE 3: Roteiros Turísticos e Consentimentos LGPD
**Nível:** Fácil  
**Tempo Estimado:** Curto / Médio  
**Critérios Atendidos:** Critério 1 (Funcionamento do Protótipo - 10 pts), Critério 4 (LGPD - 10 pts), Critério 5 (Roteiros - 20 pts)

#### Wave 3.1: Correção e Integração da Página de Roteiros (`/roteiros`)
- **Task 3.1.1: Carregar roteiros dinâmicos na view `/roteiros`**
  - *Subtask 3.1.1.1:* Ajustar a rota `/roteiros` em `web.php` para carregar `Roteiro::with('itens.atrativo')->where('publico', true)->get()` e passar para a view.
  - *Subtask 3.1.1.2:* Exibir cards de roteiros com tempo estimado, orçamento, tema, fotos dos atrativos e badge "Oficial" ou "Gerado por IA".
- **Task 3.1.2: Integração com Mapa e Traçado OSRM na página `/roteiro/{id}`**
  - *Subtask 3.1.2.1:* Na visualização detalhada `/roteiro/{id}`, carregar os pontos do roteiro e chamar a API `/api/v1/routes/directions` para desenhar o traçado da rota real entre os pontos.

#### Wave 3.2: Persistência Real de Consentimentos LGPD
- **Task 3.2.1: Endpoint e persistência de consentimentos**
  - *Subtask 3.2.1.1:* Criar rota `POST /api/v1/lgpd/consentimentos` recebendo `gps`, `alertas`, `metricas`.
  - *Subtask 3.2.1.2:* Se logado, salvar no campo JSON `consentimentos` da tabela `users`. Se visitante anônimo, salvar em cookie criptografado e `localStorage`.
- **Task 3.2.2: Interatividade na tela `/privacidade`**
  - *Subtask 3.2.2.1:* Adicionar JavaScript que escuta a mudança nos switches e envia a requisição assíncrona, exibindo Toast de confirmação.

---

### 🟠 FASE 4: Inteligência Gerencial, Mapa de Calor e Dashboards
**Nível:** Médio  
**Tempo Estimado:** Médio  
**Critérios Atendidos:** Critério 6 (Qualidade dos Indicadores - 20 pts), Critério 11 (Dashboard Executivo), Critério 13 (Inteligência Territorial), Critério 16 (Sustentabilidade/ESG)

#### Wave 4.1: Mapa de Calor Real com Agregação e Anonimização LGPD
- **Task 4.1.1: Refatorar `AdminController::heatmapData()` com dados reais**
  - *Subtask 4.1.1.1:* Consultar coordenadas reais ponderadas por consultas de atrativos em `AnalyticEvent` e interações de `AssistantLog`.
  - *Subtask 4.1.1.2:* Normalizar a intensidade de 0.1 a 1.0 com base na frequência de acessos.
- **Task 4.1.2: Salvaguarda LGPD de Supressão Estatística**
  - *Subtask 4.1.2.1:* Descartar clusters/células com menos de 5 registros individuais para preservar a privacidade do turista.
  - *Subtask 4.1.2.2:* Atualizar a renderização no Leaflet.heat com gradiente elegante no painel administrativo.

#### Wave 4.2: Gráficos de Inteligência Gerencial e Métricas ESG
- **Task 4.2.1: Integrar gráficos Chart.js no `admin/dashboard.blade.php`**
  - *Subtask 4.2.1.1:* Gráfico de Linha: Tendência de Interações com IA e Consultas por Dia (últimos 7/30 dias).
  - *Subtask 4.2.1.2:* Gráfico de Rosca/Donut: Distribuição de Atrativos e Prestadores por Categoria Turística.
- **Task 4.2.2: Módulo de Indicadores ESG e Sustentabilidade**
  - *Subtask 4.2.2.1:* Card no dashboard: "Economia de Papel & Sustentabilidade" (estimativa de impressos economizados por leitura de QR Codes).
  - *Subtask 4.2.2.2:* Card: "Inclusão Produtiva" (percentual de artesãos, pequenos negócios e produtores rurais cadastrados no trade turístico).
  - *Subtask 4.2.2.3:* Card: "Acessibilidade Universal" (percentual de atrativos com itens de acessibilidade comprovados).
- **Task 4.2.3: Enriquecimento do Relatório para Captação de Recursos**
  - *Subtask 4.2.3.1:* Atualizar `RelatorioController::exportCsv()` para incluir sumário executivo de métricas pronto para anexar em propostas do Ministério do Turismo, Sebrae e BNDES.

---

### 🟠 FASE 5: Módulo de Avaliações, Moderação e Análise de Sentimento com IA
**Nível:** Médio / Avançado  
**Tempo Estimado:** Médio  
**Critérios Atendidos:** Critério 1 (Funcionamento - 10 pts), Critério 5 (Uso de IA - 20 pts), Critério 6 (Indicadores - 20 pts), Critério 14 (Comportamento)

#### Wave 5.1: Módulo de Avaliações no PWA do Turista
- **Task 5.1.1: Estrutura de dados e API de Avaliações**
  - *Subtask 5.1.1.1:* Revisar migration e model `Avaliacao` com campos: `entidade_type`, `entidade_id`, `user_id`, `nota` (1 a 5), `comentario`, `sentimento` (positivo/neutro/negativo), `status` (pendente/aprovado/rejeitado).
  - *Subtask 5.1.1.2:* Criar controller e rota `POST /api/v1/avaliacoes` com validação de texto e rate-limiting.
- **Task 5.1.2: Interface de Avaliação no `atrativo.blade.php`**
  - *Subtask 5.1.2.1:* Adicionar seção visual com estrelas interativas, campo de comentário e botão de envio.
  - *Subtask 5.1.2.2:* Exibir avaliações já aprovadas com nota média calculada e selo de visitante.

#### Wave 5.2: Análise de Sentimento com IA e Painel de Moderação
- **Task 5.2.1: Análise de Sentimento no `IAService.php`**
  - *Subtask 5.2.1.1:* Criar método `analisarSentimento(string $texto): array` que envia prompt ao Gemini para classificar sentimento (`positivo`, `neutro`, `negativo`) e extrair tags (ex: `infraestrutura`, `limpeza`, `atendimento`).
  - *Subtask 5.2.1.2:* Salvar o resultado automaticamente ao registrar a avaliação.
- **Task 5.2.2: Tela de Moderação no Painel Administrativo**
  - *Subtask 5.2.2.1:* Criar view `resources/views/admin/avaliacoes/index.blade.php` com listagem de comentários, badge de sentimento da IA e botões de "Aprovar" e "Rejeitar".
  - *Subtask 5.2.2.2:* Criar controller `App\Http\Controllers\Web\Admin\AvaliacaoAdminController.php`.

---

### 🔴 FASE 6: Inovações Avançadas — Tradução com IA e PWA Offline
**Nível:** Avançado / Diferencial de Inovação  
**Tempo Estimado:** Médio / Longo  
**Critérios Atendidos:** Critério 2 (Inovação e Criatividade - 30 pts), Critério 5 (IA - 20 pts), Critério 7 (Manutenibilidade - 20 pts)

#### Wave 6.1: Tradução Multilíngue Dinâmica com IA
- **Task 6.1.1: Seletor de Idioma no PWA**
  - *Subtask 6.1.1.1:* Adicionar seletor dropdown elegante no topo do PWA (`pwa.blade.php`) com opções Português (PT-BR), English (EN) e Español (ES).
  - *Subtask 6.1.1.2:* Armazenar idioma ativo em cookie/sessão.
- **Task 6.1.2: Tradução On-Demand de Atrativos com IA e Cache**
  - *Subtask 6.1.2.1:* Criar rota `GET /api/v1/traducao` que recebe texto e idioma alvo, consulta o Gemini para tradução contextual e salva em cache Laravel por 30 dias.
  - *Subtask 6.1.2.2:* Integrar na view do atrativo para traduzir a descrição e história quando o usuário selecionar idioma estrangeiro.
- **Task 6.1.3: Assistente IA Multilíngue**
  - *Subtask 6.1.3.1:* O Assistente IA (`IAService::chat`) já recebe o parâmetro `$idioma` e passa no prompt de sistema para responder fluentemente no idioma do turista.

#### Wave 6.2: Service Worker e Resiliência Offline
- **Task 6.2.1: Criação e Registro do Service Worker (`sw.js`)**
  - *Subtask 6.2.1.1:* Criar arquivo `public/sw.js` com estratégia Cache-First para assets estáticos (CSS, JS, fontes, ícones).
  - *Subtask 6.2.1.2:* Registrar o service worker em `resources/views/layouts/pwa.blade.php`.
- **Task 6.2.2: Tela de Contingência Offline com Telefones de Emergência**
  - *Subtask 6.2.2.1:* Implementar fallback offline que exibe mapa em cache, telefones de utilidade pública (SAMU, Bombeiros, CAT) e roteiro salvo caso o turista perca a conexão em áreas remotas.

---

## 🎯 Projeção de Pontuação Após Execução das Fases

| Critério de Avaliação | Peso Máx. | Nota Atual | Nota Projetada | Ganho |
|---|---|---|---|---|
| 1. Funcionamento do protótipo | 10 pts | 7.5 | **9.5** | +2.0 |
| 2. Inovação e criatividade | 30 pts | 21.0 | **28.0** | +7.0 |
| 3. Experiência do usuário | 10 pts | 8.5 | **9.5** | +1.0 |
| 4. Segurança e proteção de dados | 10 pts | 7.0 | **9.5** | +2.5 |
| 5. Uso da Inteligência Artificial | 20 pts | 16.0 | **19.0** | +3.0 |
| 6. Indicadores e inteligência gerencial | 20 pts | 11.0 | **18.5** | +7.5 |
| 7. Qualidade técnica e manutenibilidade | 20 pts | 14.0 | **18.5** | +4.5 |
| 8. Viabilidade de implantação | 10 pts | 7.5 | **9.5** | +2.0 |
| 9. Qualidade da documentação | 10 pts | 3.5 | **9.5** | +6.0 |
| 10. Clareza e objetividade do pitch | 10 pts | — | **9.0** (estimado) | +9.0 |
| 11. Domínio técnico da equipe | 10 pts | — | **9.5** (estimado) | +9.5 |
| **TOTAL GERAL** | **180 pts** | **~96/160 (60%)** | **~169/180 (93.8%)** | **+73 pts** |
