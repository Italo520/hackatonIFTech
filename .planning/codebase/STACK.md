---
mapped_date: 2026-08-13
---

# Stack do Código-Fonte

## Tecnologias Principais
- **Linguagem**: PHP 8.2+
- **Framework**: Laravel 11.31
- **Arquitetura**: Aplicação MVC (Model-View-Controller)

## Dependências de Backend
- **Laravel Sanctum** (`^4.0`): Autenticação de API
- **Laravel Auditing** (`^14.0`): Auditoria/rastreamento de Models
- **Scribe** (`^5.11`): Gerador de documentação de API (ambiente dev)
- **Tinker** (`^2.9`): REPL iterativo

## Stack de Frontend
- **Ferramenta de Build**: Vite (`^6.0.11`)
- **Framework de UI**: Bootstrap 5 (`^5.3.3`)
- **Cliente HTTP**: Axios (`^1.7.4`)
- **Componentes**: Popper.js (`^2.11.8`)

## Infraestrutura e Configuração
- **Conteinerização**: Docker & Laravel Sail (`^1.26`)
- **Banco de Dados**: PostgreSQL (via `docker-compose.yml`) ou SQLite (via configuração padrão `.env.example`)
- **Cache / Filas**: Redis (imagem `alpine` no docker-compose)
