# Roadmap do Projeto: Destino Inteligente (Hackathon IFTech)

## 📌 Visão Geral & Marco Principal
Plataforma omnichannel e PWA para turismo inteligente, acessível, sustentável e seguro com suporte a IA e integração geoespacial.

---

## 🚀 Fases do Projeto

### Fase 01: Autenticação, RBAC, Ponto de Acesso no Header e Fluxo de Empreendedores
- [x] **Especificação**: `.planning/specs/01-auth-rbac-and-business-flow-spec.md`
- [x] **Plano**: `.planning/plans/01-auth-rbac-and-business-flow-plan.md`
- [x] **Status**: `CONCLUÍDA`
- **Entregáveis**:
  - Componente de perfil dinâmico no header para `@guest` e `@auth`
  - Gate inteligente de IA para turistas cadastrados
  - Fluxo de auto-cadastro de parceiros/empreendedores e dashboard do parceiro
  - Redirecionamento por papel e controle de acesso RBAC

### Fase 02: Painel Administrativo CRUDs, Auditoria e Alertas da Defesa Civil
- [x] **Especificação**: `.planning/specs/02-admin-crud-audit-and-civil-defense-alerts-spec.md`
- [x] **Status**: `CONCLUÍDA`
- **Entregáveis**:
  - Painel administrativo moderno em Bootstrap 5 (`/admin`)
  - CRUDs completos para Atrativos, Eventos, Roteiros, Alertas e Prestadores
  - Módulo de Alertas da Defesa Civil com broadcast no PWA e persistência no banco
  - Módulo de logs de Auditoria e trilha de conformidade
  - Homologação de prestadores com selo de qualidade

### Fase 03: Auditoria, Refatoração de UI/UX, Acessibilidade e Testes das Páginas do Turista (PWA)
- [x] **Especificação**: `.planning/specs/03-turista-pages-audit-refactor-and-tests-spec.md`
- [x] **Status**: `CONCLUÍDA`
- **Entregáveis**:
  - Auditoria completa das 11 páginas do turista (Home, Explorar, Atrativo, Eventos, Mapa, Roteiros, Roteiro Detalhe, IA, Utilidade, Privacidade, QR Code)
  - Eliminação de sobreposições de layout e blindagem da Bottom Navigation Bar
  - Acessibilidade WCAG (alto contraste, navegação por teclado, leitor de tela e TTS)
  - Suíte completa de testes automatizados (`tests/Feature/Web/PwaTouristPagesTest.php`)

### Fase 04: Enriquecimento Geoespacial, Importação OSM e Roteirização Inteligente
- [x] **Especificação**: `.planning/specs/04-geo-enrichment-osm-import-and-routing-spec.md`
- [x] **Status**: `CONCLUÍDA`
- **Entregáveis**:
  - Comando Artisan `php artisan turismo:import-osm {municipio_id}` para importação via Overpass API
  - Endpoint de roteirização `GET /api/v1/routes/directions` via OSRM com cache inteligente
  - Traçado real de rotas (GeoJSON Polyline) nas ruas no mapa do roteiro PWA (`/roteiro/{id}`)
  - Autocomplete de endereços e coordenadas GPS integrado com Nominatim no Admin
  - Testes automatizados dedicados (`tests/Feature/OsmImportCommandTest.php`, `tests/Feature/RoutingApiTest.php`)

### Fase 05: Infraestrutura, PostgreSQL em Produção e CI/CD Estabilizado
- [x] **Status**: `CONCLUÍDA`
- **Entregáveis**:
  - PostgreSQL provisionado e conectado em produção no Coolify (`destino-postgres`)
  - Migrations 100% universais e compatíveis com PostgreSQL nativo
  - Remoção do volume storage persistente obsoleto `/app/database`
  - Seeding idempotente de atrativos, categorias, municípios, roteiros e eventos
  - Produção online e validada em `https://iftech.italohub.cloud`
