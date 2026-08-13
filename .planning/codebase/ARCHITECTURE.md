---
mapped_date: 2026-08-13
---

# Arquitetura

## Padrão Principal
A aplicação segue a arquitetura padrão MVC (Model-View-Controller) do Laravel, incrementada com uma camada de Services (Serviços).
- **Pontos de Entrada**: 
  - `public/index.php` (Requisições HTTP Web/API)
  - `artisan` (Comandos CLI)
- **Roteamento**: Separado em `routes/web.php` (páginas web) e `routes/api.php` (endpoints de API stateless/sem estado).

## Camadas e Fluxo de Dados
1. **Roteamento**: `routes/` mapeia as requisições HTTP para os Controllers.
2. **Controllers**: Localizados em `app/Http/Controllers/` (padrão implícito do Laravel). Responsáveis por lidar com requisições e respostas HTTP.
3. **Services**: Localizados em `app/Services/`. Encapsulam a lógica de negócio, mantendo os controllers finos (thin).
4. **Models**: Localizados em `app/Models/`. Representam entidades do banco de dados usando o ORM Eloquent.
5. **Banco de Dados**: Gerenciado via migrations em `database/migrations/`.

## Abstrações Principais
- **Autenticação**: Baseada em token ou com estado (stateful) via Laravel Sanctum.
- **Auditoria**: Modificações nos models são rastreadas usando `owen-it/laravel-auditing`.
