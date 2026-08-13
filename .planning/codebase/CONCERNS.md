---
mapped_date: 2026-08-13
---

# Problemas e Débito Técnico

## Segurança
- Certifique-se de que a `APP_KEY` e outras credenciais sensíveis não sejam colocadas (hardcoded) acidentalmente no `.env.example` ou submetidas ao controle de versão.
- Verifique a configuração do Sanctum quanto à proteção CSRF adequada, caso seja usado com SPA (Single Page Application), ou quanto ao armazenamento seguro dos tokens, caso seja usado com mobile ou APIs externas.

## Infraestrutura
- O `docker-compose.yml` usa volumes locais (`destino_inteligente_pgsql`, `destino_inteligente_redis`), o que significa que os dados são persistentes localmente, mas podem ser deletados se os volumes forem expurgados (pruned).

## Maturidade do Projeto
- O projeto possui um `PRD-IFtech.md` e um `PLAN.md` na raiz, sugerindo que ele está sendo planejado ativamente ou que se encontra nas fases iniciais de desenvolvimento.
- O arquivo `migrations.txt` e o diretório/binário `destino_inteligente` na raiz podem ser resquícios de arquivos soltos ou backups temporários, que devem ser limpos ou ignorados no `.gitignore`.
