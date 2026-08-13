---
mapped_date: 2026-08-13
---

# Testes

## Framework
- **Ferramenta Principal**: PHPUnit (`^11.0`).
- **Ferramentas Adicionais**: Mockery (`^1.6`) para mocks avançados, Faker (`^1.23`) para gerar dados de teste fictícios.

## Estrutura
- `tests/Unit/`: Testes isolados para classes, métodos ou lógicas puras.
- `tests/Feature/`: Testes mais abrangentes para funcionalidades da aplicação, endpoints HTTP e interações com o banco de dados.

## Testes de Banco de Dados
- O arquivo `docker-compose.yml` monta um script `create-testing-database.sql`, indicando que um banco de dados dedicado de testes é inicializado para rodar testes de features (funcionalidades) sem poluir os dados do ambiente de desenvolvimento local.

## Cobertura e Integração Contínua (CI)
- Rodados localmente via `php artisan test` ou `./vendor/bin/phpunit`.
