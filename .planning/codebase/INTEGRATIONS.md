---
mapped_date: 2026-08-13
---

# Integrações Externas

## Bancos de Dados
- **PostgreSQL**: Configurado no `docker-compose.yml` (banco `destino_inteligente`).
- **SQLite**: Mencionado como `DB_CONNECTION` padrão no `.env.example`.
- **Redis**: Usado para cache e filas (porta 6379 via Docker).

## APIs e Webhooks
- *Nenhuma integração de API externa específica encontrada nas configurações principais (ex: Stripe, SendGrid), embora existam chaves da AWS no `.env.example`.*

## Autenticação
- **Laravel Sanctum**: Usado para emissão de tokens de API ou autenticação de SPA (Single Page Application).

## Serviços Locais
- **E-mail**: Usa driver `log` por padrão localmente, com configuração para SMTP (porta 2525).
