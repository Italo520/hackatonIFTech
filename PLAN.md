# Plano de Execução - Plataforma Destino Turístico Inteligente

## Fase 0 - Planejamento

### A. Árvore de Diretórios do Projeto
```text
/app
├── app/
│   ├── Actions/            # Lógica de negócio reutilizável
│   ├── Console/            # Comandos customizados (importação, etc)
│   ├── Exceptions/         # Tratamento centralizado de erros
│   ├── Http/
│   │   ├── Controllers/    # Controladores (Web e API)
│   │   ├── Middleware/     # i18n, segurança, etc.
│   │   └── Requests/       # Validação de formulários (Form Requests)
│   ├── Models/             # Modelos Eloquent
│   ├── Policies/           # Regras de autorização
│   └── Services/           # Serviços complexos (IA, Integrações)
├── bootstrap/              # Inicialização do Laravel
├── config/                 # Configurações do sistema
├── database/
│   ├── factories/          # Criação de dados falsos para testes/seeders
│   ├── migrations/         # Esquema do banco de dados (em ordem)
│   └── seeders/            # Carga de dados iniciais
├── public/
│   ├── build/              # Assets compilados (Vite)
│   ├── assets/             # Imagens, fontes estáticas
│   ├── sw.js               # Service Worker principal (Workbox)
│   └── manifest.json       # PWA manifest
├── resources/
│   ├── css/                # SCSS/CSS (Bootstrap 5, tema)
│   ├── js/                 # Javascript (PWA, offline logic, Leaflet)
│   ├── lang/               # Arquivos de idioma (pt-BR, en, es)
│   └── views/              # Templates Blade
│       ├── components/     # Componentes Blade reutilizáveis
│       ├── layouts/        # Layouts base (painel Tabler, app shell PWA)
│       ├── admin/          # Views do Painel de Gestão (Tabler)
│       └── pwa/            # Views do App do Turista
├── routes/
│   ├── api.php             # Rotas da API REST (com documentação)
│   ├── web.php             # Rotas web (Painel, PWA)
│   └── console.php         # Rotas de comandos artisan
├── storage/                # Logs, uploads locais, cache de views
├── tests/
│   ├── Feature/            # Testes de ponta a ponta e integração (Pest)
│   └── Unit/               # Testes unitários (Pest)
├── .env.example            # Exemplo de variáveis de ambiente
├── docker-compose.yml      # Configuração Docker (Postgres, Redis, PHP)
├── composer.json           # Dependências PHP
├── package.json            # Dependências JS (Vite, Bootstrap, Tabler, Leaflet, Workbox)
├── README.md               # Instruções de setup e arquitetura
└── PLAN.md                 # Este documento
```

### B. Lista de Migrations (Ordem de Criação)
A ordem é crítica devido às chaves estrangeiras.

1.  `0001_01_01_000000_create_users_table` (Modificada para incluir role, 2fa_secret, idioma)
2.  `0001_01_01_000001_create_cache_table` (Padrão Laravel 11)
3.  `0001_01_01_000002_create_jobs_table` (Padrão Laravel 11)
4.  `2024_08_13_000010_create_municipios_table` (id, nome, uf, bbox_geo(PostGIS Polygon), tema_visual, config)
5.  `2024_08_13_000020_create_categorias_table` (id, nome, slug, icone, tipo)
6.  `2024_08_13_000030_create_atrativos_table` (id, municipio_id, categoria_id, nome, descricao, historia, endereco, geo(PostGIS Point), horarios, tempo_medio_visita, precos, contatos, acessibilidade(jsonb), restricoes, seguranca, status, validado_por, validado_em) - Inclui índice full-text (tsvector) e geo (GiST).
7.  `2024_08_13_000040_create_midias_table` (Polimórfica: atrativos, eventos, roteiros)
8.  `2024_08_13_000050_create_eventos_table` (Agenda)
9.  `2024_08_13_000060_create_prestadores_table` (user_id FK)
10. `2024_08_13_000070_create_roteiros_table` (geo PostGIS LineString)
11. `2024_08_13_000080_create_roteiro_itens_table` (Pivô: roteiro_id, atrativo_id, ordem)
12. `2024_08_13_000090_create_avaliacoes_table` (Polimórfica, user_id, sentimento_ia)
13. `2024_08_13_000100_create_ocorrencias_table`
14. `2024_08_13_000110_create_alertas_table`
15. `2024_08_13_000120_create_notificacoes_table` (user_id FK, alerta_id FK)
16. `2024_08_13_000130_create_qrcodes_table` (atrativo_id FK)
17. `2024_08_13_000140_create_assistant_logs_table` (Métricas IA)
18. `2024_08_13_000150_create_consentimentos_table` (LGPD)
19. `2024_08_13_000160_create_embeddings_table` (pgvector: vector(1536), polimórfica)
20. `2024_08_13_000170_create_sync_packets_table` (Controle offline)
*(Pacote `spatie/laravel-auditing` cuidará da tabela `audit_logs`)*

### C. Mapa de Rotas (Web + API)

**Rotas Web (App Turista - PWA):**
- `GET /` - Home (atalhos, destaques)
- `GET /explorar` - Busca e listagem com filtros (Atrativos)
- `GET /atrativo/{slug}` - Página detalhada (com dados para offline)
- `GET /eventos` - Agenda de eventos
- `GET /roteiros` - Listagem de roteiros
- `GET /roteiro/{id}` - Detalhe do roteiro + botão "Baixar Offline"
- `GET /mapa` - Mapa global (Leaflet)
- `GET /ia` - Interface do assistente virtual (Chat)
- `GET /qr/{code}` - Deep link do QR Code (redireciona para atrativo)
- `GET /offline` - Fallback view quando não há rede e dados não estã em cache

**Rotas Web (Painel de Gestão - Tabler):**
- `GET /admin/login` - Autenticação
- `GET /admin/2fa` - Confirmação TOTP
- `GET /admin` - Dashboard executivo (KPIs)
- `GET /admin/atrativos` - CRUD (Resource)
- `GET /admin/eventos` - CRUD
- `GET /admin/roteiros` - Editor de roteiros
- `GET /admin/prestadores` - Fila de validação (Aprovar/Rejeitar)
- `GET /admin/avaliacoes` - Moderação
- `GET /admin/alertas` - Emissão de comunicados
- `GET /admin/relatorios` - Mapa de calor, PDFs
- `GET /admin/usuarios` - Gestão RBAC

**Rotas Web (Área do Empreendedor):**
- `GET /parceiro/cadastro` - Wizard inicial
- `GET /parceiro/painel` - Autogestão (atualizar horários, status do selo)

**Rotas API REST (/api/v1):**
*Todas protegidas por Sanctum ou tokens, com rate limit*
- `GET /api/v1/atrativos` - Listagem via JSON (suporte a busca full-text e geo-radius)
- `GET /api/v1/atrativos/{id}`
- `GET /api/v1/eventos`
- `GET /api/v1/roteiros/{id}/export` - Retorna pacote JSON para IndexedDB (offline)
- `POST /api/v1/ia/chat` - Endpoint do assistente RAG
- `POST /api/v1/ia/roteiro` - Gerador personalizado
- `POST /api/v1/sync/avaliacoes` - Recepção da fila do Background Sync (offline)
- `POST /api/v1/consentimentos` - Gestão LGPD
- `POST /api/v1/ocorrencias`

### D. Checklist de Tasks (Ordem de Execução por Sprint)

**Sprint 0 — Fundação (setup)**
- [ ] T-001: Repo Git + CI (GitHub Actions: Pint, Larastan, Pest, composer audit)
- [ ] T-002: Laravel 11 + PostgreSQL 16 (PostGIS + pgvector) + Redis via Docker Compose
- [ ] T-003: Auth: Sanctum + sessão, RBAC (8 roles), 2FA TOTP, policies
- [ ] T-004: Base Tabler (layout vertical) integrada ao Blade + tema visual
- [ ] T-005: PWA shell: manifest, Service Worker (Workbox), precache
- [ ] T-006: Migrations iniciais (seção 8) + seeders de demo realistas
- [ ] T-007: Auditoria (spatie/laravel-auditing) + logs
- [ ] T-008: i18n pt/en/es (lang files + middleware)

**Sprint 1 — Turista: descoberta**
- [ ] T-010: Home mobile-first
- [ ] T-011: CRUD API + páginas de atrativos (tsvector, GiST)
- [ ] T-012: Busca com filtros combináveis
- [ ] T-013: Agenda de eventos
- [ ] T-014: Mapa Leaflet básico
- [ ] T-015: Campos de acessibilidade estruturados
- [ ] T-016: Utilidade pública/emergência + precache offline
- [ ] T-017: Galeria de mídia otimizada

**Sprint 2 — Roteiros, IA e offline**
- [ ] T-020: CRUD roteiros oficiais
- [ ] T-021: Módulo IA (pgvector ingestão)
- [ ] T-022: Assistente virtual RAG (chat)
- [ ] T-023: Busca linguagem natural
- [ ] T-024: Gerador de roteiro (IA)
- [ ] T-025: "Baixar para offline" (IndexedDB + tiles)
- [ ] T-026: Fila de escrita offline (Background Sync)
- [ ] T-027: QR Code in loco
- [ ] T-028: Banner offline/status

**Sprint 3 — Empreendedor, moderação e painel**
- [ ] T-030: Wizard de cadastro do empreendedor
- [ ] T-031: Fila de validação de cadastros
- [ ] T-032: Autogestão do prestador
- [ ] T-033: Avaliações (submissão, moderação)
- [ ] T-034: Ocorrências
- [ ] T-035: Alertas emergenciais
- [ ] T-036: CMS completo no Tabler
- [ ] T-037: Gestão RBAC

**Sprint 4 — Inteligência e relatórios**
- [ ] T-040: Analytics própria (privacy-first)
- [ ] T-041: Dashboard executivo
- [ ] T-042: Mapa de calor (LGPD compliant)
- [ ] T-043: Indicadores de IA
- [ ] T-044: Indicadores econômicos/ESG
- [ ] T-045: Relatórios PDF/CSV
- [ ] T-046: Alertas operacionais

**Sprint 5 — Hardening, acessibilidade e entrega**
- [ ] T-050: Auditoria WCAG
- [ ] T-051: Pentest/Security hardening
- [ ] T-052: LGPD self-service
- [ ] T-053: Performance optimization
- [ ] T-054: Backup/restore test
- [ ] T-055: OpenAPI docs
- [ ] T-056: Conteúdo 360° piloto
- [ ] T-057: Seeders demo e DEMO.md
- [ ] T-058: Testes E2E dos fluxos de banca
