# PRD — Destino Turístico Inteligente
## Plataforma Digital de Turismo Municipal com IA, Geolocalização e Gestão Orientada por Dados

| Campo | Valor |
|---|---|
| Documento | Product Requirements Document (PRD) |
| Versão | 1.0 |
| Data | 12/08/2026 |
| Origem da demanda | Edital HACKATON — Máxima Tecnologia LTDA ("Desafio Destino Turístico Municipal") |
| Produto | Plataforma digital de turismo municipal (PWA do turista + Painel web do gestor) |
| Status | Aprovado para desenvolvimento |

---

# 1. Visão Geral e Contexto

## 1.1 Problema
Municípios turísticos brasileiros sofrem com **dispersão de informações**: atrativos, eventos, gastronomia e serviços ficam espalhados em redes sociais, páginas desatualizadas e sistemas sem integração. O turista não encontra informação confiável em um só lugar; o empreendedor local não tem vitrine oficial; e a Administração Pública decide sem dados — sem saber quem visita, o que procura, de onde vem e onde investir.

## 1.2 Solução
Um **ecossistema digital único** com três frentes:

1. **App do Turista (PWA mobile-first, offline-capable)** — descoberta de atrativos, busca por linguagem natural, roteiros inteligentes com IA, mapas interativos, agenda de eventos, QR Codes nos locais, conteúdo imersivo e modo offline para áreas sem conectividade.
2. **Área do Empreendedor (web responsivo)** — cadastro e autogestão de prestadores locais (restaurantes, hospedagens, guias, artesãos), com fluxo de validação municipal e selo de fornecedor verificado.
3. **Painel do Gestor (web responsivo, template Tabler/Bootstrap)** — CMS completo, moderação, dashboard executivo para prefeito/secretário, mapas de calor, indicadores econômicos e ESG, alertas de segurança e relatórios exportáveis para captação de recursos.

## 1.3 Diferenciais
- IA aplicada com **responsabilidade**: assistente virtual com RAG sobre a base oficial validada (reduz alucinação), conteúdo gerado por IA sempre sinalizado, supervisão humana obrigatória.
- **Offline-first** para o turista: roteiros, mapas e telefones de emergência disponíveis sem internet (regiões rurais e áreas naturais).
- **Governança de dados**: nenhum cadastro público sem validação municipal; trilha de auditoria completa; LGPD by design.
- **Replicável**: multi-município por configuração (white-label), não por fork de código.

## 1.4 Alinhamento com o edital
O edital exige: portal público, área de empreendedores, painel administrativo, IA, geolocalização, mapas, roteiros, QR Code, acessibilidade, conteúdo imersivo, indicadores, segurança, LGPD, interoperabilidade — e define stack obrigatória: **PHP + Laravel, Bootstrap e PostgreSQL** (seção 23 do edital). Este PRD cobre 100% dos requisitos do edital e marca o recorte de MVP para a entrega do hackathon.

---

# 2. Objetivos e Métricas de Sucesso

## 2.1 Objetivos de negócio
| # | Objetivo | Métrica | Meta (6 meses pós-lançamento) |
|---|---|---|---|
| O1 | Centralizar a informação turística | Atrativos/serviços cadastrados e validados | ≥ 150 itens validados |
| O2 | Prolongar permanência do turista | Roteiros gerados/consultados por visitante | ≥ 1,5 roteiro/sessão |
| O3 | Fortalecer economia local | Empreendedores cadastrados e aprovados | ≥ 80 prestadores ativos |
| O4 | Apoiar decisão pública | Gestores ativos no painel (semanal) | ≥ 70% da equipe de turismo |
| O5 | Inclusão digital | Conformidade acessibilidade | WCAG 2.1 AA (auditoria) |
| O6 | Funcionar sem internet | Recursos essenciais offline | 100% dos roteiros baixados acessíveis offline |

## 2.2 Métricas de produto (North Star e suporte)
- **North Star:** roteiros turísticos completados (iniciados e finalizados com avaliação).
- Ativação: % de visitantes que executam ≥ 1 busca ou abrem ≥ 1 atrativo.
- Engajamento: tempo médio de navegação, páginas/sessão, taxa de retorno.
- Qualidade: nota média das avaliações, % avaliações moderadas em < 48h.
- Operação: tempo médio de validação de cadastro de empreendedor (< 5 dias úteis).
- Técnica: LCP < 2,5s (4G), uptime ≥ 99,5%, 0 vulnerabilidades críticas abertas.

## 2.3 Critérios de sucesso do hackathon (banca)
Demonstrar, funcionalmente: (a) pesquisa de atrativos; (b) visualização de informações turísticas; (c) criação/apresentação de roteiros; (d) interação com IA; (e) indicadores no painel administrativo — conforme seção 20 do edital.

---

# 3. Escopo

## 3.1 Dentro do escopo (MVP do hackathon)
- PWA do turista: home, busca (keyword + linguagem natural), páginas de atrativos, agenda de eventos, mapa interativo, roteiros (pré-definidos + 1 gerador por IA), assistente virtual (RAG), modo offline de roteiro salvo, QR Code de atrativo, avaliações, utilidade pública/emergência.
- Área do empreendedor: cadastro, edição, upload de mídia/documentos, acompanhamento de status.
- Painel do gestor (Tabler): autenticação + RBAC, CMS de atrativos/eventos/roteiros, fila de validação de cadastros, moderação de avaliações, dashboard executivo (KPIs principais), mapa de calor básico, alertas/comunicados, relatório PDF/CSV, trilha de auditoria.
- Segurança e LGPD: HTTPS, proteção OWASP (SQLi/XSS/CSRF), consentimento, anonimização agregada, direitos do titular (exportar/excluir).
- Acessibilidade WCAG 2.1 AA nos fluxos do MVP.

## 3.2 Fora do escopo (pós-hackathon / roadmap)
- App nativo iOS/Android (o PWA cobre o mobile; arquitetura já prevê API para app nativo futuro).
- Realidade aumentada e tours 360° em produção (MVP: 1 atrativo piloto com 360°).
- Integrações com sistemas municipais/estaduais/federais (API documentada e pronta, integrações específicas depois).
- Pagamentos/reservas in-app (MVP: deep link/WhatsApp do prestador).
- Notificações push segmentadas em produção (MVP: comunicados no app + infra de push preparada).

## 3.3 Premissas
- Prefeitura fornece carga inicial mínima: 30+ atrativos, 10+ eventos, 20+ prestadores (via seeders/planilha de importação).
- LLM via API externa (ex.: OpenAI/Gemini) com chave do projeto; embeddings armazenados no próprio PostgreSQL (pgvector).
- Tiles de mapa: provedor compatível com cache offline (ou MBTiles próprios do bounding box municipal).

## 3.4 Restrições
- Stack obrigatória do edital: PHP/Laravel estável mais recente, Bootstrap estável, PostgreSQL, MVC, migrations/seeders, UTF-8, Git.
- Todas as dependências com suporte ativo e sem vulnerabilidades conhecidas na entrega.
- IA não substitui validação humana de conteúdo oficial (regra do edital).

---

# 4. Personas

| Persona | Perfil | Necessidades | Dispositivo |
|---|---|---|---|
| **Turista (visitante)** | 25–55 anos, visita a lazer, conectividade instável em zonas rurais | Descobrir o que fazer, roteiro pronto, mapa offline, info confiável, emergência | Mobile (PWA) |
| **Turista PcD** | Deficiência visual/auditiva/motora ou mobilidade reduzida | Leitor de tela, contraste, filtro de experiências acessíveis, audiodescrição | Mobile/Desktop |
| **Empreendedor local** | Dono de pousada/restaurante, artesão, guia; baixa familiaridade técnica | Cadastrar negócio, atualizar horário/preço, ganhar selo de validado | Web mobile/desktop |
| **Gestor de conteúdo (servidor)** | Equipe da secretaria de turismo | Publicar/editar atrativos e eventos, validar cadastros, moderar avaliações | Desktop (painel Tabler) |
| **Secretário de Turismo** | Gestor tático | KPIs, mapas de calor, reclamações recorrentes, relatórios para captação | Desktop/tablet |
| **Prefeito** | Gestor executivo | Visão estratégica resumida, comparativos mensais/anuais, ESG | Desktop/tablet |
| **Atendente/CAT** | Centro de atendimento ao turista | Responder ocorrências, consultar cadastros, apoiar visitante | Desktop |

---

# 5. Arquitetura e Stack Técnica

## 5.1 Visão de alto nível

```
┌─────────────────────┐   ┌──────────────────────┐   ┌─────────────────────┐
│  PWA do Turista      │   │  Painel do Gestor     │   │  Área do Empreendedor│
│  (mobile-first,      │   │  (Tabler / Bootstrap  │   │  (web responsivo)    │
│  offline, Bootstrap) │   │  layout vertical)     │   │                      │
└─────────┬───────────┘   └──────────┬───────────┘   └──────────┬───────────┘
          │ HTTPS / JSON (REST)      │                          │
┌─────────▼──────────────────────────▼──────────────────────────▼───────────┐
│                    Laravel (MVC) — API + Blade (painel)                    │
│  Auth (Sanctum) │ RBAC (Gates/Policies) │ Auditoria │ Fila (Jobs) │ Cache  │
├─────────────────┴───────────────────────┴─────────────────────────────────┤
│  Módulo IA (RAG): ingestão → embeddings → pgvector → LLM API → resposta    │
│  com fontes + flag "conteúdo gerado por IA"                                │
├────────────────────────────────────────────────────────────────────────────┤
│  PostgreSQL (+ PostGIS p/ geodados, + pgvector p/ IA) │ Redis (cache/fila) │
│  Storage S3-compatível (mídias) │ Tiles de mapa (cache offline)            │
└────────────────────────────────────────────────────────────────────────────┘
```

## 5.2 Decisões de stack
| Camada | Decisão | Justificativa |
|---|---|---|
| Backend | PHP 8.3+ / Laravel 11+ (MVC) | Exigência do edital; ecossistema maduro |
| Banco | PostgreSQL 16+ com **PostGIS** (geo) e **pgvector** (RAG) | Exigência do edital + um único banco para relacional, geográfico e vetorial |
| Painel gestor | **Tabler (layout vertical)** sobre Bootstrap 5, Blade + Blade components | Template definido pelo time; sidebar vertical ideal para muitos módulos; responsivo nativo; dark mode; ApexCharts e mapas vetoriais embutidos |
| App turista | **PWA** mobile-first (Bootstrap 5 + tema próprio, mesma identidade visual), Service Worker via Workbox | "Mobile" sem custo de app nativo; offline real; instalável na home screen; uma única base de código |
| Empreendedor | Web responsivo (mesma base Blade/Bootstrap do painel, layout simplificado) | Simplicidade e reaproveitamento |
| Mapas | Leaflet + tiles OSM (ou provedor com ToS de cache); pacote offline em MBTiles do município | Open-source, leve, permite cache offline |
| IA | LLM via API (function calling) + RAG com pgvector; jobs em fila para embeddings/tradução/sentimento | Base oficial validada como fonte prioritária (exigência do edital) |
| Auth | Laravel Sanctum (API/PWA) + sessão (painel); 2FA TOTP para perfis administrativos | Segurança exigida pelo edital |
| Filas/Cache | Redis + Laravel Horizon | Embeddings, traduções, notificações, relatórios pesados |
| Mídia | Storage S3-compatível + CDN; imagens otimizadas (WebP, srcset) | Performance mobile |
| Observabilidade | Logs estruturados, Laravel Telescope (staging), Sentry | Rastreabilidade |
| CI/CD | GitHub Actions: lint (Pint), testes (Pest/PHPUnit), análise estática (Larastan), scan de dependências (Composer audit), deploy | Qualidade e replicabilidade |

## 5.3 Padrões de engenharia
- MVC estrito; lógica de negócio em Services/Actions; Form Requests para validação backend; Policies para autorização.
- API REST versionada (`/api/v1`), documentada com OpenAPI (Scribe), respeitando requisito de interoperabilidade do edital.
- Migrations + seeders para todo o schema e carga inicial; índices em colunas de busca (full-text `tsvector` em português, GIN em arrays de acessibilidade, GiST em geolocalização).
- Internacionalização (i18n): pt-BR, en, es desde o MVP (arquivos de lang + tradução assistida por IA com revisão humana).
- Codificação UTF-8 em todo o pipeline.

---

# 6. Estratégia Offline (PWA do Turista)

## 6.1 Princípio
O turista não pode ficar sem informação essencial em trilha, zona rural ou área natural. O app deve funcionar **offline-first para consumo** e **online-first para interação**.

## 6.2 Camadas de cache
| Camada | Tecnologia | Conteúdo | Estratégia |
|---|---|---|---|
| App shell | Cache API (Workbox, precache) | HTML/CSS/JS, ícones, fontes | Cache First, atualização em background |
| Dados de leitura | IndexedDB (via `idb`) | Atrativos, eventos, categorias, utilidade pública, telefones de emergência | Stale-While-Revalidate a partir da API |
| Roteiro baixado ("Modo viagem") | IndexedDB + Cache API | Roteiro completo: descrições, ordem, tempos, fotos otimizadas, contatos | Download explícito pelo usuário (pacote versionado) |
| Mapa offline | Cache API (tiles) ou MBTiles | Bounding box do município, zoom 10–16 | Pré-cache sob demanda com barra de progresso e estimativa de MB |
| Fila de escrita | IndexedDB + Background Sync | Avaliações, ocorrências, favoritos criados offline | Fila com retry exponencial e resolução de conflito (last-write-wins + marcação "enviado offline") |

## 6.3 Regras de negócio offline
- Todo roteiro (pré-definido ou gerado por IA) pode ser **baixado para offline** com 1 toque.
- Telefones de emergência e utilidade pública são **sempre** precacheados (primeira abertura) — nunca dependem de rede.
- Banner de status: "Você está offline — exibindo dados salvos em {data/hora da última sincronização}".
- Conteúdo que exige rede (assistente IA, tradução, disponibilidade em tempo real) deve degradar graciosamente com mensagem clara.
- Sincronização automática ao reconectar (`online` event + Background Sync API); conflitos resolvidos no servidor com registro em auditoria.
- Limite de armazenamento: app monitora quota (`navigator.storage.estimate`) e alerta antes de downloads grandes; política de expiração (LRU, máx. 200 MB padrão configurável).

## 6.4 Segurança no offline
- Dados pessoais sensíveis **não** são armazenados no dispositivo (apenas conteúdo público + favoritos locais).
- Fila de escrita offline criptografada em repouso quando o navegador suportar (chave por sessão); tokens com expiração curta e refresh rotation.
- Logout remoto invalida tokens; conteúdo offline permanece (é público), mas ações pendentes são descartadas.

---

# 7. Segurança da Informação e LGPD

## 7.1 Controles de segurança (obrigatórios)
| Domínio | Controle |
|---|---|
| Transporte | HTTPS obrigatório (HSTS), TLS 1.2+ |
| Autenticação | Senha com Argon2id/bcrypt; política de senha forte; 2FA TOTP obrigatório para admin; bloqueio após 5 tentativas; reset com token expirável |
| Autorização | RBAC com Policies; menor privilégio; escopos de API por perfil |
| Perfis (roles) | `super_admin`, `prefeito`, `secretario`, `gestor_conteudo`, `gestor_cadastros`, `atendente`, `empreendedor`, `turista` |
| OWASP | Eloquent/Query Builder (anti-SQLi); escape Blade + sanitização de HTML (HTMLPurifier) anti-XSS; tokens CSRF; rate limiting em login/API; validação dupla (frontend + Form Request); upload com allowlist de MIME/extensão e reencode de imagem |
| Headers | CSP estrita, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy |
| Auditoria | Trilha imutável: usuário, data/hora, operação, entidade, antes/depois (ex.: spatie/laravel-auditing); acessos administrativos logados |
| Sessões/API | Sanctum com expiração, refresh rotation, revogação em logout/troca de senha |
| Infra | Backups diários com retenção 30 dias + teste de restore mensal; segredos em vault/`.env` fora do VCS; dependências auditadas em CI (composer audit); SAST no pipeline |
| Privacidade IA | Prompts e respostas do assistente logados sem PII; PII nunca enviada ao LLM (mascaramento no middleware) |

## 7.2 LGPD by design
- **Minimização**: turista usa o app sem conta; conta só é exigida para avaliar, favoritar sincronizado ou receber notificações.
- **Consentimento granular**: geolocalização, notificações e analytics são opt-in separados, com finalidade informada em linguagem clara.
- **Registro de tratamento**: tabela de consentimentos (quem, quando, versão do termo, finalidade).
- **Direitos do titular**: autoatendimento para exportar (JSON/PDF) e excluir dados (anonimização preservando estatística agregada).
- **Anonimização agregada**: dashboards e mapas de calor exibem apenas dados agregados (k-anonimato mínimo configurável, ex.: células com < 5 indivíduos são suprimidas); **proibido rastreamento individual** sem base legal.
- **Retenção**: logs de acesso 6 meses; dados de conta enquanto ativa; avaliações anonimizadas após exclusão da conta.
- **Incidentes**: runbook de resposta a vazamento com comunicação à ANPD e titulares.
- **Transparência IA**: todo conteúdo gerado por IA exibe selo "Conteúdo gerado por IA — verifique informações oficiais"; respostas do assistente citam as fontes oficiais usadas.

---

# 8. Modelo de Dados (alto nível)

Entidades principais (PostgreSQL; geo em PostGIS; vetores em pgvector):

| Entidade | Campos-chave | Observações |
|---|---|---|
| `users` | id, nome, email, senha_hash, role, 2fa_secret, idioma, consentimentos | Soft delete |
| `municipios` | id, nome, uf, bbox_geo, tema_visual, config | Multi-município (replicabilidade) |
| `categorias` | id, nome, slug, ícone, tipo (atrativo/evento/serviço) | Histórico, cultural, gastronômico, rural, ecológico… |
| `atrativos` | id, municipio_id, categoria_id, nome, descricao, historia, endereco, geo (Point), horarios, tempo_medio_visita, precos, contatos, acessibilidade (jsonb), restricoes, seguranca, status, validado_por, validado_em | Full-text + GiST |
| `midias` | id, entidade_polimorfica, tipo (foto/vídeo/360/áudio), url, autor, licenca, alt_text, legenda | Controle de direitos autorais |
| `eventos` | id, nome, descricao, local, geo, inicio, fim, organizador, ingressos, capacidade, faixa_etaria, gratuito, acessibilidade, status (ativo/alterado/cancelado) | Agenda |
| `prestadores` | id, user_id (dono), tipo (hospedagem/gastronomia/guia/artesão…), dados, documentos, validade_documentos, status (pendente/aprovado/rejeitado/suspenso/complementar), selo_validado, ultima_atualizacao | Fluxo de validação |
| `roteiros` | id, titulo, tema, duracao, dificuldade, transporte, orcamento, perfil, origem (oficial/ia/usuario), geo (LineString), distancia_total, publico | Pré-definidos e personalizados |
| `roteiro_itens` | roteiro_id, atrativo_id, ordem, tempo_estimado | Sequência |
| `avaliacoes` | id, user_id, entidade_polimorfica, nota, comentario, sentimento (IA), status_moderacao, origem_offline | Moderação obrigatória |
| `ocorrencias` | id, tipo, entidade, local/geo, gravidade, descricao, status_atendimento, origem | Segurança do turista |
| `alertas` | id, titulo, corpo, segmentacao (geo/idioma/interesse), urgencia, vigencia, criado_por | Comunicados |
| `notificacoes` | id, user_id, alerta_id, canal, status | Opt-in |
| `qrcodes` | id, atrativo_id, payload_url, impressoes, scans | Rastreio de scans |
| `assistant_logs` | id, pergunta (sem PII), resposta, fontes (json), idioma, feedback_util | Métricas de IA |
| `audit_logs` | id, user_id, acao, entidade, antes, depois, ip, created_at | Imutável |
| `consentimentos` | id, user_id, finalidade, versao_termo, aceito_em, revogado_em | LGPD |
| `embeddings` | id, entidade, chunk, vector(1536), idioma | RAG (pgvector) |
| `sync_packets` | id, roteiro_id/entidade, versao, hash, gerado_em | Controle de versão offline |

---

# 9. Requisitos Funcionais (RF) e Não-Funcionais (RNF)

## 9.1 Módulo A — PWA do Turista
| ID | Requisito | Prioridade |
|---|---|---|
| RF-A01 | Home com acesso rápido: o que fazer, onde ficar, onde comer, como chegar, eventos | Must |
| RF-A02 | Busca por palavra-chave, categoria, localização, data, interesse, orçamento, duração, perfil | Must |
| RF-A03 | Busca em linguagem natural ("passeios gratuitos com crianças por 3 horas") via IA | Must |
| RF-A04 | Página de atrativo completa (descrição, história, geo, horários, preços, contatos, acessibilidade, restrições, segurança, fotos/vídeos, avaliações, serviços próximos) | Must |
| RF-A05 | Agenda de eventos com filtros (período, localidade, categoria, faixa etária, gratuidade, acessibilidade) e estados de alteração/cancelamento | Must |
| RF-A06 | Mapa interativo com posição do usuário, atrativos próximos e estimativa de deslocamento | Must |
| RF-A07 | Roteiros pré-definidos filtráveis (tema, duração, dificuldade, transporte, acessibilidade, orçamento, perfil) | Must |
| RF-A08 | Gerador de roteiro personalizado por IA (tempo, orçamento, interesses, crianças, mobilidade reduzida) | Must |
| RF-A09 | Download de roteiro para uso offline (conteúdo + mapa) | Must |
| RF-A10 | Assistente virtual (chat) com RAG sobre base oficial, multilíngue, citando fontes | Must |
| RF-A11 | Utilidade pública e emergência (saúde, polícia, bombeiros, defesa civil, transporte, CAT) sempre offline | Must |
| RF-A12 | Leitura de QR Code in loco abrindo conteúdo do atrativo (história, áudio, segurança) | Must |
| RF-A13 | Avaliações com nota e comentário (online e via fila offline) | Must |
| RF-A14 | Filtro explícito de experiências acessíveis | Must |
| RF-A15 | Seleção de idioma (pt/en/es) com tradução de conteúdo | Should |
| RF-A16 | Favoritos e "minha viagem" | Should |
| RF-A17 | Conteúdo imersivo: galeria, vídeo, 360° (piloto em 1 atrativo), audiodescrição | Should |
| RF-A18 | Notificações opt-in (eventos, alertas) | Could (MVP: comunicados in-app) |

## 9.2 Módulo B — Área do Empreendedor
| ID | Requisito | Prioridade |
|---|---|---|
| RF-B01 | Solicitação de cadastro com dados, categoria, documentos e termo de responsabilidade | Must |
| RF-B02 | Autogestão: editar informações, horários, preços, fotos, acessibilidade | Must |
| RF-B03 | Acompanhamento de status (pendente/aprovado/rejeitado/suspenso/complementar) com motivo | Must |
| RF-B04 | Alertas de documento vencendo e cadastro desatualizado (> 6 meses sem update) | Should |
| RF-B05 | Exibição do selo "Validado pelo Município" após aprovação | Must |
| RF-B06 | Resposta a avaliações do próprio estabelecimento | Could |

## 9.3 Módulo C — Painel do Gestor (Tabler)
| ID | Requisito | Prioridade |
|---|---|---|
| RF-C01 | Login com 2FA e RBAC por perfil | Must |
| RF-C02 | CMS de atrativos, eventos, categorias, roteiros, utilidade pública, mídias (CRUD + publicar/desativar/exclusão lógica) | Must |
| RF-C03 | Fila de validação de prestadores (aprovar/rejeitar/suspender/solicitar complemento) | Must |
| RF-C04 | Moderação de avaliações (legítima/suspeita/ofensiva/duplicada) com registro de providência | Must |
| RF-C05 | Dashboard executivo: acessos, visitantes únicos, recorrentes, tempo médio, taxa de retorno, páginas/atrativos/roteiros/eventos top, buscas, origem geográfica, idiomas, dispositivos, canais | Must |
| RF-C06 | Comparativos mensais/trimestrais/anuais e sazonalidade | Must |
| RF-C07 | Mapa de calor de acessos/pesquisas/atrativos/eventos (agregado e anonimizado) | Must |
| RF-C08 | Indicadores econômicos: prestadores por segmento/região, estimativas (rotuladas como projeção + metodologia) | Should |
| RF-C09 | Indicadores de comportamento da IA: perguntas frequentes, temas emergentes, demandas não atendidas, análise de sentimento | Should |
| RF-C10 | Gestão de ocorrências e alertas emergenciais (publicação rápida, segmentação) | Must |
| RF-C11 | Indicadores ESG/acessibilidade/sustentabilidade | Should |
| RF-C12 | Relatórios filtráveis exportáveis em PDF e CSV/planilha | Must |
| RF-C13 | Trilha de auditoria consultável | Must |
| RF-C14 | Alertas operacionais (cadastros incompletos, desatualizados, documentos vencidos, eventos próximos, avaliações críticas) | Should |
| RF-C15 | Gestão de usuários e permissões | Must |
| RF-C16 | Geração e métricas de QR Codes | Should |

## 9.4 Requisitos Não-Funcionais
| ID | Requisito | Meta |
|---|---|---|
| RNF-01 | Performance mobile | LCP < 2,5s em 4G; TTI < 3,5s; imagens WebP/srcset |
| RNF-02 | Offline | RNF conforme seção 6 (100% dos recursos essenciais offline) |
| RNF-03 | Acessibilidade | WCAG 2.1 AA: teclado, leitores de tela, contraste, alt text, legendas, audiodescrição, ampliação de fonte |
| RNF-04 | Segurança | Seção 7.1; 0 vulnerabilidades críticas/altas conhecidas na entrega |
| RNF-05 | LGPD | Seção 7.2 |
| RNF-06 | Compatibilidade | 2 últimas versões de Chrome, Firefox, Safari, Edge; Android/iOS via PWA |
| RNF-07 | Escalabilidade/replicabilidade | Multi-município por configuração; módulos desacoplados; API documentada (OpenAPI) |
| RNF-08 | Confiabilidade | Uptime ≥ 99,5%; backup diário; restore testado |
| RNF-09 | Internacionalização | pt-BR, en, es; datas/moeda localizadas |
| RNF-10 | Observabilidade | Logs estruturados, métricas, alertas de erro |
| RNF-11 | Manutenibilidade | Código documentado, Pint/Larastan, cobertura de testes ≥ 60% nos domínios críticos |
| RNF-12 | SEO/Share | Meta/OG tags, sitemap, URLs amigáveis (portal é vitrine do destino) |

---

# 10. User Stories

Legenda de prioridade: **M** = Must (MVP hackathon), **S** = Should, **C** = Could.

## Épico EP-01 — Descoberta e Busca (Turista)
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-001 | Como turista, quero uma home com atalhos para "o que fazer, onde ficar, onde comer, eventos" para decidir rápido o que fazer | Home carrega < 2,5s em 4G; atalhos visíveis sem scroll em mobile; conteúdo reflete dados validados | M |
| US-002 | Como turista, quero buscar por palavra-chave e filtros (categoria, distância, preço, acessibilidade) para achar opções relevantes | Resultados em < 1s; filtros combináveis; empty state com sugestões | M |
| US-003 | Como turista, quero perguntar em linguagem natural ("roteiro gratuito com crianças, 3h") e receber sugestões estruturadas | IA retorna atrativos/roteiros da base oficial; resposta cita fontes; flag de conteúdo IA | M |
| US-004 | Como turista, quero ver a página completa do atrativo com fotos, horário, preço e como chegar | Todos os campos do RF-A04; botão "como chegar" abre mapa; dados marcados com última atualização | M |
| US-005 | Como turista PcD, quero filtrar apenas experiências acessíveis (rampa, Libras, banheiro adaptado) | Filtro dedicado; campos de acessibilidade estruturados (não texto livre); resultado respeita WCAG | M |
| US-006 | Como turista, quero ver a agenda de eventos por período e saber se houve cancelamento | Filtros do RF-A05; evento cancelado exibe aviso destacado; alterações registram histórico | M |
| US-007 | Como turista estrangeiro, quero usar o app em inglês/espanhol | Troca de idioma persiste; conteúdo traduzido (IA + revisão); UI 100% i18n | S |

## Épico EP-02 — Roteiros e Mapas
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-010 | Como turista, quero roteiros prontos por tema/duração/dificuldade para não planejar do zero | Lista filtrável; detalhe com ordem, tempos, distância, dificuldade, serviços no caminho, segurança | M |
| US-011 | Como turista, quero que a IA monte um roteiro com meu tempo, orçamento e interesses | Formulário de preferências; roteiro coerente com horários de funcionamento; exibe estimativa de custo/tempo; editável (remover/reordenar paradas) | M |
| US-012 | Como turista, quero ver o roteiro no mapa com minha posição e atrativos próximos | Mapa com polyline do roteiro; geolocalização opt-in; raio de "perto de mim" configurável | M |
| US-013 | Como turista, quero baixar o roteiro para usar sem internet | Botão "Baixar para offline"; barra de progresso com MB estimados; após download, 100% funcional em modo avião (texto, fotos, mapa, telefones) | M |
| US-014 | Como turista, quero escanear o QR Code no local e ver história/áudio/segurança | Scan abre página do atrativo em < 2s; funciona com câmera nativa; scan registrado para métricas | M |
| US-015 | Como turista, quero salvar favoritos e montar "minha viagem" | Favoritos locais (sem conta) e sincronizados (com conta); lista exportável | S |

## Épico EP-03 — Assistente Virtual (IA)
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-020 | Como turista, quero tirar dúvidas no chat (atrativos, eventos, transporte) com respostas confiáveis | Resposta baseada na base oficial (RAG); fontes citadas; "não sei" quando fora da base; sem PII no log | M |
| US-021 | Como turista, quero que o assistente fale meu idioma | Detecção/seleção de idioma; respostas em pt/en/es | M |
| US-022 | Como gestor, quero que conteúdo gerado por IA seja identificado e revisável | Selo "gerado por IA"; descrições assistidas passam por aprovação humana antes de publicar | M |
| US-023 | Como gestor, quero ver as perguntas mais frequentes ao assistente para criar conteúdo | Ranking de temas/perguntas no painel; agrupamento por tópico | S |

## Épico EP-04 — Avaliações e Ocorrências
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-030 | Como turista, quero avaliar atrativos com nota e comentário | Avaliação exige conta; passa por moderação; exibe média e contagem | M |
| US-031 | Como turista offline, quero que minha avaliação seja enviada quando a internet voltar | Fila offline com retry; marca "enviado offline"; sem duplicar | M |
| US-032 | Como turista, quero registrar uma ocorrência (problema em atrativo/serviço) | Formulário com tipo, local, gravidade; protocolo gerado; disclaimer de que não substitui emergência | M |
| US-033 | Como gestor, quero moderar avaliações e ver sentimento agregado | Fila de moderação; IA sugere sentimento/duplicidade; decisão humana registrada | M |

## Épico EP-05 — Empreendedor
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-040 | Como empreendedor, quero solicitar cadastro do meu negócio com documentos | Wizard simples; upload com validação; termo de responsabilidade; protocolo | M |
| US-041 | Como empreendedor, quero acompanhar e complementar meu cadastro | Timeline de status; motivo de rejeição/complemento visível; reenvio | M |
| US-042 | Como empreendedor, quero atualizar horários/preços/fotos quando quiser | Edição entra em revisão leve (mudanças críticas) ou direta (campos livres), conforme política; log de atualização | M |
| US-043 | Como empreendedor, quero receber alerta de documento vencendo | Alerta 30/15/7 dias antes; e-mail + painel | S |
| US-044 | Como empreendedor aprovado, quero exibir o selo de validado | Selo visível no perfil público; critérios de concessão documentados; revogável | M |

## Épico EP-06 — Gestão de Conteúdo (Painel Tabler)
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-050 | Como gestor de conteúdo, quero CRUD completo de atrativos com mídias e acessibilidade | Form com validação dupla; upload com alt text obrigatório; pré-visualização; publicar/desativar/exclusão lógica | M |
| US-051 | Como gestor, quero publicar/alterar/cancelar eventos | Estados com aviso público; alteração notifica interessados | M |
| US-052 | Como gestor, quero montar roteiros oficiais arrastando atrativos em ordem | Editor com mapa; cálculo automático de distância/tempo | M |
| US-053 | Como gestor, quero validar cadastros de empreendedores em fila | Fila com filtros; ações aprovar/rejeitar/suspender/complementar; SLA visível | M |
| US-054 | Como gestor, quero gerenciar usuários e permissões | CRUD de usuários com roles; 2FA enforce; log de acessos | M |
| US-055 | Como gestor, quero que toda alteração relevante fique em auditoria | Trilha consultável com filtro por usuário/entidade/período | M |

## Épico EP-07 — Dashboards e Inteligência
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-060 | Como prefeito/secretário, quero um dashboard executivo com KPIs do destino | KPIs do RF-C05; comparativo mensal; visual claro (ApexCharts do Tabler) | M |
| US-061 | Como secretário, quero mapa de calor do interesse turístico por região | Heatmap agregado/anonimizado; supressão de células pequenas (LGPD) | M |
| US-062 | Como gestor, quero ver origem dos visitantes, idiomas e dispositivos | Dimensões por município/UF/país quando lícito; agregado | S |
| US-063 | Como gestor, quero identificar atrativos com muito acesso e baixa interação e vice-versa | Relatório de "conteúdo quente/frio"; sugestão de revisão | S |
| US-064 | Como gestor, quero indicadores econômicos e ESG com metodologia visível | Estimativas rotuladas como projeção; metodologia anexada | S |
| US-065 | Como gestor, quero exportar relatórios em PDF/planilha para prestar contas e captar recursos | Filtros por período/categoria/região; export PDF + CSV; disclaimer de que não garante elegibilidade a editais | M |

## Épico EP-08 — Segurança do Turista e Alertas
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-070 | Como turista, quero ver telefones de emergência e orientações mesmo sem internet | Precache no 1º acesso; acesso em 1 toque; disclaimer de canais oficiais | M |
| US-071 | Como gestor, quero publicar um alerta emergencial rapidamente (clima, interdição) | Publicação em < 2 min; segmentação por região/idioma; destaque no app | M |
| US-072 | Como gestor, quero ver áreas com mais ocorrências para agir preventivamente | Mapa de ocorrências por gravidade/período; agregado | S |

## Épico EP-09 — Plataforma, Offline e Segurança
| ID | User Story | Critérios de Aceite | Pri. |
|---|---|---|---|
| US-080 | Como turista, quero instalar o app na tela inicial | Manifest válido; prompt de instalação; ícone e splash | M |
| US-081 | Como turista, quero saber quando estou offline e a data dos dados | Banner de status; timestamp de sincronização | M |
| US-082 | Como titular de dados, quero exportar e excluir meus dados | Self-service; export JSON/PDF; exclusão anonimiza mantendo agregados | M |
| US-083 | Como admin, quero 2FA obrigatório e sessões seguras | TOTP; revogação; bloqueio por tentativas | M |
| US-084 | Como equipe técnica, quero API pública documentada para integrações futuras | OpenAPI publicado; auth por token; rate limit; logs de uso | S |

---

# 11. Use Cases (detalhados)

## UC-01 — Busca inteligente de atrativos
- **Ator:** Turista | **Pré-condição:** app aberto (online ou offline com dados sincronizados)
- **Fluxo principal:** 1) Turista abre busca. 2) Digita "cachoeira" ou pergunta em linguagem natural. 3) Sistema aplica filtros (categoria, distância, acessibilidade, preço). 4) Retorna lista ordenada por relevância/distância com fotos e notas. 5) Turista abre um atrativo.
- **Alternativo:** 3a) Linguagem natural → sistema envia consulta ao módulo IA (RAG) que retorna entidades da base oficial com fontes. 3b) Offline → busca executada sobre IndexedDB local (sem IA), com aviso.
- **Exceção:** E1) Sem resultados → sugestões de termos/categorias próximas. E2) Falha na IA → fallback para busca por palavra-chave com aviso.
- **Pós-condição:** busca registrada (anonimizada) para indicadores.

## UC-02 — Geração de roteiro personalizado por IA
- **Ator:** Turista | **Pré-condição:** online
- **Fluxo principal:** 1) Turista informa tempo disponível, orçamento, interesses, composição (crianças, mobilidade reduzida), ponto de partida. 2) Sistema consulta base oficial (atrativos abertos no período, distâncias). 3) IA monta sequência otimizada com tempos e custos estimados. 4) Turista visualiza no mapa, edita (remove/reordena). 5) Salva e opcionalmente baixa para offline.
- **Alternativo:** 2a) Preferências incompatíveis (tempo insuficiente) → sistema propõe versão reduzida. 4a) Turista regenera com ajustes ("mais natureza").
- **Exceção:** E1) IA indisponível → oferece roteiros pré-definidos equivalentes. E2) Nenhum atrativo aberto no período → sugere período alternativo.
- **Regras:** roteiro gerado por IA exibe selo; horários validados contra cadastro oficial; nunca incluir prestador não validado sem aviso.

## UC-03 — Uso offline do roteiro ("Modo Viagem")
- **Ator:** Turista | **Pré-condição:** roteiro salvo
- **Fluxo principal:** 1) Turista toca "Baixar para offline". 2) Sistema estima MB, confirma, baixa pacote (conteúdo + tiles do bbox do roteiro). 3) Em campo, sem rede, turista abre o roteiro: textos, fotos, mapa, posição GPS (GPS não depende de rede) e telefones funcionam. 4) Ao reconectar, app sincroniza versão e avaliações pendentes.
- **Alternativo:** 2a) Espaço insuficiente → oferece download "leve" (sem fotos HD). 4a) Nova versão do roteiro disponível → aviso de atualização.
- **Exceção:** E1) Download interrompido → resume do ponto de falha. E2) Pacote corrompido (hash inválido) → refaz download.
- **Pós-condição:** pacote versionado em IndexedDB/Cache; expiração LRU.

## UC-04 — Cadastro e validação de empreendedor
- **Atores:** Empreendedor, Gestor de cadastros | **Pré-condição:** empreendedor com conta
- **Fluxo principal:** 1) Empreendedor preenche wizard (dados, categoria, documentos, acessibilidade, fotos) e aceita termo. 2) Sistema cria cadastro "pendente" e notifica gestores. 3) Gestor analisa na fila. 4) Aprova → cadastro público com selo; empreendedor notificado.
- **Alternativo:** 4a) Rejeita com motivo → empreendedor corrige e reenvia. 4b) Solicita complemento → cadastro bloqueado até resposta. 4c) Suspende (irregularidade) → sai do ar com registro.
- **Exceção:** E1) Documento inválido/ilegível → rejeição automática parcial com orientação. E2) SLA > 5 dias úteis → alerta ao secretário.
- **Regras:** nada é publicado sem validação humana; trilha de auditoria em todas as transições.

## UC-05 — Moderação de avaliação
- **Atores:** Turista, Gestor de conteúdo | **Pré-condição:** avaliação submetida
- **Fluxo principal:** 1) Turista avalia (nota + comentário). 2) IA classifica sentimento e sinaliza suspeitas (ofensivo/duplicado/artificial). 3) Avaliação entra na fila. 4) Gestor aprova → pública.
- **Alternativo:** 4a) Rejeita com motivo → autor notificado. 4b) Marca como suspeita → agrega ao relatório de integridade.
- **Exceção:** E1) Avaliação offline → entra na fila de sync e segue o mesmo fluxo com flag de origem.
- **Regras:** análise de sentimento é auxiliar; decisão final humana; providências registradas.

## UC-06 — Publicação de alerta emergencial
- **Ator:** Gestor (perfil autorizado) | **Pré-condição:** ocorrência emergencial
- **Fluxo principal:** 1) Gestor abre "Novo alerta". 2) Define título, corpo, urgência, segmentação (região/idioma/interesse) e vigência. 3) Confirma pré-visualização. 4) Sistema publica no app (destaque), painel e, se opt-in, push. 5) Alerta expira automaticamente e vai para histórico.
- **Alternativo:** 2a) Alerta de teste (visível só internamente).
- **Exceção:** E1) Gestor sem permissão → bloqueio + log. E2) Falha no push → alerta in-app garantido.
- **Regras:** publicação < 2 min; disclaimer de que não substitui canais oficiais de emergência; auditoria obrigatória.

## UC-07 — Dashboard executivo
- **Ator:** Prefeito/Secretário | **Pré-condição:** login com perfil executivo
- **Fluxo principal:** 1) Abre dashboard (visão mensal default). 2) Visualiza KPIs (acessos, únicos, recorrência, tempo médio, top atrativos/roteiros/eventos/buscas). 3) Alterna comparativo (mensal/trimestral/anual). 4) Detalha por categoria/região. 5) Exporta relatório PDF.
- **Alternativo:** 3a) Seleciona período customizado.
- **Exceção:** E1) Dados insuficientes no período → gráfico com aviso de amostra baixa.
- **Regras:** apenas dados agregados/anonimizados; estimativas rotuladas com metodologia.

## UC-08 — Exercício de direitos do titular (LGPD)
- **Ator:** Usuário autenticado
- **Fluxo principal:** 1) Acessa "Privacidade". 2) Solicita exportação → recebe JSON/PDF dos dados. 3) Solicita exclusão → confirma com senha. 4) Sistema anonimiza dados pessoais, preserva agregados estatísticos, revoga sessões e confirma por e-mail.
- **Exceção:** E1) Pendências legais (ex.: ocorrência em investigação) → exclusão agendada com justificativa.
- **Regras:** prazo ≤ 15 dias; registro em auditoria (sem manter PII).

## UC-09 — Consulta via QR Code in loco
- **Ator:** Turista | **Pré-condição:** QR instalado no atrativo
- **Fluxo principal:** 1) Turista escaneia QR com câmera. 2) Abre página do atrativo (deep link) com história, áudio/audiodescrição e orientações de segurança. 3) Scan registrado para métricas.
- **Alternativo:** 2a) Offline com pacote baixado → abre versão local.
- **Exceção:** E1) QR danificado → busca manual por nome. E2) URL inválida → página de erro com contatos.

## UC-10 — Importação/carga inicial de dados
- **Ator:** Gestor de conteúdo / equipe técnica
- **Fluxo principal:** 1) Exporta template (CSV/planilha). 2) Preenche atrativos/eventos/prestadores. 3) Importa com validação linha a linha. 4) Relatório de sucesso/erros. 5) Itens importados entram como "pendentes de validação".
- **Exceção:** E1) Linhas com erro → arquivo de correção para download.
- **Regras:** seeders para ambiente de demo; importação auditada.

---

# 12. Tasks — Backlog Técnico por Sprint

> Estimativas em story points (SP) para uma equipe de 2–4 devs. Ordem otimizada para o hackathon (demo funcional no fim da Sprint 3, robustez na 4–5).

## Sprint 0 — Fundação (setup)
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-001 | Repo Git + CI (GitHub Actions: Pint, Larastan, Pest, composer audit) | — | 3 |
| T-002 | Laravel 11 + PostgreSQL 16 (PostGIS + pgvector) + Redis via Docker Compose | — | 3 |
| T-003 | Auth: Sanctum + sessão, RBAC (8 roles), 2FA TOTP, policies | US-083 | 5 |
| T-004 | Base Tabler (layout vertical) integrada ao Blade + tema visual do município | US-050 | 3 |
| T-005 | PWA shell: manifest, Service Worker (Workbox), precache do app shell | US-080 | 5 |
| T-006 | Migrations iniciais (todas as entidades da seção 8) + seeders de demo | — | 5 |
| T-007 | Auditoria (spatie/laravel-auditing) + logs estruturados | US-055 | 3 |
| T-008 | i18n pt/en/es (lang files + middleware) | US-007 | 3 |

## Sprint 1 — Turista: descoberta
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-010 | Home mobile-first com atalhos e destaques | US-001 | 3 |
| T-011 | CRUD API + páginas de atrativos (full-text tsvector, geo GiST) | US-004 | 8 |
| T-012 | Busca com filtros combináveis + empty states | US-002 | 5 |
| T-013 | Agenda de eventos com filtros e estados (alterado/cancelado) | US-006 | 5 |
| T-014 | Mapa Leaflet: posição, próximos, rotas básicas | US-012 | 5 |
| T-015 | Campos estruturados de acessibilidade + filtro de experiências acessíveis | US-005 | 3 |
| T-016 | Utilidade pública/emergência + precache offline | US-070 | 3 |
| T-017 | Galeria de mídia (WebP, srcset, alt text obrigatório) | US-004 | 3 |

## Sprint 2 — Roteiros, IA e offline
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-020 | CRUD de roteiros oficiais + editor com ordenação e mapa | US-010, US-052 | 8 |
| T-021 | Módulo IA: ingestão → chunks → embeddings (pgvector) | US-020 | 5 |
| T-022 | Assistente virtual (chat) com RAG, fontes citadas, flag IA, sem PII no log | US-020, US-021 | 8 |
| T-023 | Busca em linguagem natural (function calling → filtros estruturados) | US-003 | 5 |
| T-024 | Gerador de roteiro personalizado (validação de horários/orçamento) | US-011 | 8 |
| T-025 | "Baixar para offline": pacote versionado + tiles bbox + progresso | US-013 | 8 |
| T-026 | Fila de escrita offline (IndexedDB + Background Sync) p/ avaliações | US-031 | 5 |
| T-027 | QR Code: geração, deep link, métricas de scan | US-014 | 3 |
| T-028 | Banner offline + timestamp de sincronização + gestão de quota | US-081 | 3 |

## Sprint 3 — Empreendedor, moderação e painel
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-030 | Wizard de cadastro do empreendedor + upload de documentos | US-040 | 8 |
| T-031 | Fila de validação (aprovar/rejeitar/suspender/complementar) + SLA | US-053, UC-04 | 5 |
| T-032 | Autogestão do prestador + selo de validado + alertas de vencimento | US-042–044 | 5 |
| T-033 | Avaliações: submissão, fila, moderação, sentimento (IA auxiliar) | US-030, US-033 | 8 |
| T-034 | Ocorrências: registro do turista + gestão no painel | US-032, US-072 | 5 |
| T-035 | Alertas emergenciais com segmentação + destaque no app | US-071, UC-06 | 5 |
| T-036 | CMS completo no Tabler (atrativos, eventos, categorias, mídias, utilidade pública) | US-050, US-051 | 8 |
| T-037 | Gestão de usuários/roles + log de acessos administrativos | US-054 | 3 |

## Sprint 4 — Inteligência e relatórios
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-040 | Coleta de analytics própria (privacy-first, agregada) | US-060 | 5 |
| T-041 | Dashboard executivo (KPIs + comparativos, ApexCharts do Tabler) | US-060 | 8 |
| T-042 | Mapa de calor com supressão de células pequenas (LGPD) | US-061 | 5 |
| T-043 | Indicadores de IA (perguntas, temas, demandas não atendidas) | US-023 | 3 |
| T-044 | Indicadores econômicos e ESG com metodologia visível | US-064 | 5 |
| T-045 | Relatórios PDF/CSV filtráveis + disclaimer de captação | US-065 | 5 |
| T-046 | Alertas operacionais (cadastros desatualizados, docs vencidos, avaliações críticas) | RF-C14 | 3 |

## Sprint 5 — Hardening, acessibilidade e entrega
| Task | Descrição | US ref | SP |
|---|---|---|---|
| T-050 | Auditoria WCAG 2.1 AA (axe + testes com leitor de tela) e correções | RNF-03 | 8 |
| T-051 | Pentest básico OWASP + correções (ZAP/Burp), rate limiting, CSP | RNF-04 | 8 |
| T-052 | LGPD: consentimentos, exportação/exclusão self-service, termos | US-082, UC-08 | 5 |
| T-053 | Performance: cache, otimização de queries (N+1), CDN, budget de bundle | RNF-01 | 5 |
| T-054 | Backup/restore testado + runbook de incidente | RNF-08 | 3 |
| T-055 | OpenAPI publicado + documentação de integração | US-084 | 3 |
| T-056 | Conteúdo 360° piloto (1 atrativo) + audiodescrição | US-017 | 5 |
| T-057 | Seeders realistas p/ demo + roteiro de apresentação da banca | — | 3 |
| T-058 | Testes E2E dos 5 fluxos da banca (busca, atrativo, roteiro, IA, dashboard) | — | 5 |

---

# 13. Roadmap e Critérios de Entrega

## 13.1 Marcos
| Marco | Conteúdo | Critério de saída |
|---|---|---|
| M1 (fim Sprint 1) | Turista descobre e consome conteúdo | Busca + atrativo + eventos + mapa funcionais |
| M2 (fim Sprint 2) | IA + offline (diferencial) | Assistente RAG, roteiro por IA, roteiro 100% offline |
| M3 (fim Sprint 3) | Ecossistema completo | Empreendedor + validação + moderação + alertas |
| M4 (fim Sprint 4) | Gestão por dados | Dashboard + heatmap + relatórios |
| M5 (fim Sprint 5) | Pronto para banca | WCAG AA, segurança, E2E dos 5 fluxos, docs |

## 13.2 Roteiro da demo (banca — seção 20 do edital)
1. Turista busca "roteiro gratuito em família" (linguagem natural) → abre atrativo.
2. Gera roteiro personalizado por IA → baixa para offline → demonstra em modo avião.
3. Escaneia QR Code → conteúdo + audiodescrição.
4. Empreendedor cadastra negócio → gestor aprova na fila → selo aparece no app.
5. Gestor publica alerta emergencial → aparece no app.
6. Secretário abre dashboard → KPIs, mapa de calor → exporta PDF.
7. Mostra trilha de auditoria + selo "conteúdo gerado por IA" (governança).

## 13.3 Pós-hackathon
App nativo (consumindo a mesma API), reservas/pagamentos, push segmentado em produção, RA em escala, integrações gov (estadual/federal), programa de capacitação de empreendedores dentro da plataforma.

---

# 14. Riscos e Mitigações

| Risco | Prob. | Impacto | Mitigação |
|---|---|---|---|
| Alucinação da IA com info turística | M | Alto | RAG restrito à base validada; "não sei" fora da base; fontes citadas; selo de conteúdo IA; supervisão humana |
| Tiles de mapa com ToS que proíbe cache | M | Médio | Provedor com licença de cache ou MBTiles próprios do município; fallback: mapa simplificado vetorial offline |
| Carga inicial de dados insuficiente | M | Alto | Importação por planilha (UC-10) + seeders realistas; parceria com secretaria desde o dia 1 |
| Complexidade do offline estourar prazo | M | Alto | Offline restrito a "roteiro baixado + emergência" no MVP; fila de escrita só para avaliações |
| LGPD em analytics/localização | B | Alto | Analytics própria agregada/anonimizada; k-anonimato em heatmaps; consentimento granular; sem trackers de terceiros |
| Chave/custo de LLM | M | Médio | Cache de respostas frequentes; rate limit por sessão; modelo econômico para embeddings; fallback de busca por keyword |
| Equipe sem domínio de Laravel (background Java) | M | Médio | Stack é exigência do edital; mitigar com convenções desde o Sprint 0, Larastan/Pint, revisão cruzada e componentes prontos do ecossistema |
| Escopo inflado para a banca | A | Alto | Edital penaliza "quantidade sem coerência" — MVP focado nos 5 fluxos de demo + governança |

---

# 15. Glossário
- **PWA**: Progressive Web App — site instalável com capacidades de app (offline, push, home screen).
- **RAG**: Retrieval-Augmented Generation — IA que responde consultando uma base de dados própria antes de gerar texto.
- **RBAC**: Role-Based Access Control — permissões por perfil de usuário.
- **WCAG 2.1 AA**: padrão internacional de acessibilidade digital (nível intermediário exigido).
- **k-anonimato**: técnica que suprime dados agregados com poucos indivíduos, evitando reidentificação.
- **CAT**: Centro de Atendimento ao Turista.
- **Trade turístico**: conjunto de prestadores da cadeia do turismo (hospedagem, gastronomia, guias, agências, artesanato).

---

*Fim do PRD v1.0 — documento vivo; revisões devem ser registradas em versionamento junto ao código.*
