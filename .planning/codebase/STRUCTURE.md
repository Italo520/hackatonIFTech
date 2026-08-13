---
mapped_date: 2026-08-13
---

# Estrutura de Diretórios

## Diretórios Raiz
- `app/`: Lógica central da aplicação (Models, Http, Services, Providers).
- `bootstrap/`: Inicialização (bootstrapping) do framework e cache de configuração.
- `config/`: Arquivos de configuração da aplicação.
- `database/`: Migrations, factories e seeders.
- `docker/`: Arquivos de configuração customizados do Docker (ex: scripts de init do PostgreSQL).
- `public/`: Raiz do servidor web, contém `index.php` e os assets compilados.
- `resources/`: Assets não compilados (CSS/JS) e views (Blade).
- `routes/`: Definições de rotas da aplicação (`api.php`, `web.php`, `console.php`).
- `storage/`: Logs, templates Blade compilados, uploads de arquivos.
- `tests/`: Testes unitários (Unit) e de funcionalidade (Feature).

## Localizações Importantes
- **`app/Services/`**: Camada de lógica de negócio.
- **`app/Http/`**: Controllers e Middleware.
- **`app/Models/`**: Models do Eloquent.
- **`composer.json` & `package.json`**: Dependências de backend e frontend.
- **`docker-compose.yml`**: Definição da infraestrutura local (PostgreSQL, Redis).
