---
mapped_date: 2026-08-13
---

# Convenções de Código

## Estilo de Código
- **Padrão da Linguagem**: Segue as convenções padrão do PHP (PSR-12), típicas do Laravel 11.
- **Nomenclatura**: 
  - Classes/Models: `PascalCase` (ex: `User`)
  - Métodos/Funções: `camelCase` (ex: `calculateTotal`)
  - Tabelas do Banco de Dados: `snake_case`, plural (ex: `users`)
  - Variáveis: `camelCase` ou `snake_case` dependendo do contexto (geralmente `camelCase` em PHP, `snake_case` em interações com o DB).

## Padrões de Arquitetura
- **Controllers Finos (Thin) / Services Encorpados (Fat)**: A presença da pasta `app/Services/` implica que lógicas de negócio complexas devem ser extraídas dos controllers para classes de serviços dedicadas.
- **Documentação de API**: Usa o Scribe (`knuckleswtf/scribe`) para gerar a documentação da API a partir de docblocks e definições de rotas.
- **Auditoria de Models**: Models que requerem rastreamento devem implementar a trait de auditoria do pacote `owen-it/laravel-auditing`.

## Tratamento de Erros
- Tratados primariamente pelo Exception Handler integrado do Laravel. Respostas de API geralmente devem retornar estruturas JSON consistentes para os erros.
