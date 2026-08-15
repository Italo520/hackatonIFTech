# 🏛️ Destino Inteligente — Plataforma de Gestão e Inteligência Turística Municipal
> **Desafio Destino Turístico Municipal:** Transformando territórios em experiências turísticas inteligentes por meio da Inteligência Artificial, Inovação e Governança Orientada por Dados.  
> **Patrocinador Oficial:** Máxima Tecnologia LTDA

---

## 📌 Visão Geral do Projeto

O **Destino Inteligente** é um ecossistema digital integrado desenvolvido em conformidade com as diretrizes do Desafio Destino Turístico Municipal. O sistema conecta em uma única plataforma:
1. **O Turista:** Através de um Progressive Web App (PWA) moderno, responsivo e intuitivo, com busca por linguagem natural, mapas interativos (Leaflet + OpenStreetMap), roteirização real ponto a ponto (OSRM) e Assistente Virtual com IA Generativa e RAG (Retrieval-Augmented Generation) contextualizado com dados oficiais e salvaguardas LGPD.
2. **Os Empreendedores Locais:** Área dedicada para credenciamento de pousadas, restaurantes, artesãos e guias turísticos, estimulando a economia criativa e a obtenção do Selo Oficial Municipal.
3. **A Gestão Pública (Prefeito, Secretário de Turismo e Equipes Técnicas):** Painel administrativo completo com indicadores em tempo real, mapas de calor analíticos, gestão de alertas de defesa civil, fila de homologação de prestadores, relatórios para captação de recursos e trilha de auditoria governamental imutável.

---

## 📐 Arquitetura Tecnológica e Padrões

O projeto foi concebido seguindo estritamente as tecnologias e padrões estabelecidos no item 23 do Edital:

- **Backend & Framework:** PHP 8.2+ e Laravel 11 (Padrão MVC, Eloquent ORM, Migrations & Seeders estruturados).
- **Banco de Dados:** PostgreSQL 16 (com suporte a GIN Indexes, busca vetorial/textual e relacionamentos 100% normalizados) / SQLite para testes locais rápidos.
- **Frontend & Interface:** Bootstrap 5.3 (Mobile-First, Design System consistente com tokens de cores e componentes acessíveis), Blade Templates, JavaScript ES6+ e Bootstrap Icons.
- **Mapas & Roteirização:** Leaflet.js, OpenStreetMap (OSM Nominatim) e OSRM (Open Source Routing Machine) para rotas reais (carro, caminhada, bicicleta).
- **Inteligência Artificial:** Google Gemini 3.5-flash com arquitetura RAG (Retrieval-Augmented Generation), guardrails rígidos anti-alucinação, anonimização LGPD de PII (Personally Identifiable Information) e streaming SSE em tempo real.
- **Segurança & Auditoria:** Controle de acesso baseado em papéis (RBAC com 8 perfis), senhas com hash Bcrypt, proteção contra CSRF/XSS/SQL Injection, Headers de Segurança com Content Security Policy (CSP), soft deletes e trilha de auditoria completa via `owen-it/laravel-auditing`.

---

## 👥 Perfis de Acesso e Credenciais de Demonstração

O sistema possui controle de acesso refinado (RBAC) com permissões customizadas por papel:

| Perfil | E-mail de Demonstração | Senha Padrão | Área de Acesso |
|---|---|---|---|
| **Super Administrador** | `super_admin@demo.com` | `password` | Acesso total ao sistema, auditoria e configurações |
| **Prefeito Municipal** | `prefeito@demo.com` | `password` | Dashboard Executivo, KPIs estratégicos e relatórios |
| **Secretário de Turismo** | `secretario@demo.com` | `password` | Gestão de atrativos, eventos, alertas e relatórios |
| **Gestor de Cadastros** | `gestor_cadastros@demo.com` | `password` | Validação e concessão de selo para prestadores |
| **Gestor de Conteúdo** | `gestor_conteudo@demo.com` | `password` | Gestão do catálogo turístico, eventos e roteiros |
| **Atendente CAT** | `atendente@demo.com` | `password` | Consulta de ocorrências, alertas e utilidades |
| **Empreendedor Local** | `empreendedor@demo.com` | `password` | Painel do parceiro (`/parceiro/painel`) e submissão de atrativos |
| **Turista (Público)** | `turista@demo.com` | `password` | Portal PWA, chat com IA, roteiros e mapas |

---

## 🚀 Guia de Instalação e Execução

### Opção 1: Execução com Docker & Docker Compose (Recomendado para Produção)

```bash
# 1. Clonar o repositório
git clone https://github.com/Italo520/hackatonIFTech.git
cd hackatonIFTech

# 2. Configurar o ambiente
cp .env.example .env

# 3. Subir os containers (Laravel + PostgreSQL 16 + Redis)
docker-compose up -d --build

# 4. Executar migrations e carga de dados de demonstração
docker-compose exec laravel.test php artisan migrate:fresh --seed

# 5. Acessar a aplicação
# PWA Turista: http://localhost:8000
# Painel Administrativo: http://localhost:8000/admin
```

### Opção 2: Execução Local (PHP + Composer + Node.js)

```bash
# 1. Instalar dependências PHP e JS
composer install
npm install && npm run build

# 2. Configurar o arquivo .env
cp .env.example .env
php artisan key:generate

# 3. Executar migrations e seeders
php artisan migrate:fresh --seed

# 4. Iniciar o servidor local
php artisan serve
```

---

## 🧪 Execução da Suíte de Testes Automatizados

O projeto conta com mais de **75 testes automatizados** cobrindo APIs, autorizações RBAC, roteirização, fluxos do PWA e logs governamentais:

```bash
# Executar toda a suíte de testes
php artisan test

# Executar apenas testes de autorização RBAC
php artisan test tests/Feature/Auth/RbacMiddlewareTest.php

# Executar testes de integração do Assistente IA
php artisan test tests/Feature/IAApiTest.php
```

---

## 📊 Mapeamento de Conformidade com os 11 Critérios do Edital

| # | Critério de Avaliação | Pontos | Como o Projeto Atende |
|---|---|---|---|
| **1** | **Funcionamento do Protótipo** | **10 pts** | PWA 100% operacional com busca de atrativos, mapas interativos, roteiros com IA, agenda de eventos, cadastro de empreendedores e painel administrativo CRUD completo. |
| **2** | **Inovação e Criatividade** | **30 pts** | RAG Real com dados do PostgreSQL, roteamento OSRM real com polyline, geocodificação OSM Nominatim com cache inteligente de 24h e integração de alertas da Defesa Civil no mapa. |
| **3** | **Experiência do Usuário (UX)** | **10 pts** | Design System Bootstrap 5 mobile-first, bottom navigation no PWA, cards com chips de categorias, loading states e painel admin com visual executivo premium. |
| **4** | **Segurança & LGPD** | **10 pts** | Middleware RBAC com 8 roles, CSP e Security Headers, sanitização de PII antes do envio à IA, página dedicada de privacidade (`/privacidade`) com consentimentos e API de exportação/exclusão. |
| **5** | **Uso da Inteligência Artificial** | **20 pts** | Gemini 3.5-flash com RAG oficial de dados do município, guardrails anti-alucinação, fallback resiliente baseado em atrativos locais e registro auditável de todas as consultas em `assistant_logs`. |
| **6** | **Qualidade dos Indicadores** | **20 pts** | Dashboard executivo com KPIs dinâmicos, mapa de calor analítico com salvaguarda LGPD (supressão para < 5 interações) e exportação de dados CSV para captação de recursos. |
| **7** | **Qualidade Técnica & Manutenibilidade** | **20 pts** | Padrão MVC rígido, 22 migrations versionadas, seeders com dados realistas, suporte dual SQLite/PostgreSQL e cobertura de testes automatizados. |
| **8** | **Viabilidade de Implantação** | **10 pts** | Dockerfile multi-stage com PHP 8.2-fpm-alpine + Nginx, docker-compose pronto para produção, uso de APIs abertas (OSM, OSRM) e baixo custo operacional. |
| **9** | **Qualidade da Documentação** | **10 pts** | README oficial detalhado, documentação de arquitetura, especificações de produto (PRD) e documentação de API interativa (Scribe). |
| **10** | **Clareza e Objetividade do Pitch** | **10 pts** | Demonstração clara da jornada do turista (da busca ao roteiro guiado), do empreendedor local e da tomada de decisão orientada por dados do gestor público. |
| **11** | **Domínio Técnico da Equipe** | **10 pts** | Decisões técnicas fundamentadas em engenharia de software sólida: arquitetura RAG, resiliência a falhas, geoprocessamento e conformidade normativa. |

---

## 📄 Licença e Créditos

Desenvolvido para o **Hackathon IFTech — Desafio Destino Turístico Municipal**  
**Patrocínio:** Máxima Tecnologia LTDA  
**Licença:** MIT
