# Instruções para agentes

## Contexto do projeto

Este é um projeto Laravel com PostgreSQL/PostGIS, Redis, Blade, Vite e Tailwind. O plano de execução do produto está em `PLAN.md`, na raiz do repositório.

## Fluxo obrigatório de sprints

1. Leia este arquivo e depois leia `PLAN.md` integralmente antes de alterar o código.
2. Identifique a primeira sprint ou tarefa ainda não concluída no checklist de `PLAN.md`.
3. Execute somente uma sprint por vez. Não avance para sprints posteriores.
4. Se houver uma tarefa marcada como concluída, não a refaça sem justificativa.
5. Antes de implementar, apresente um plano curto e liste os arquivos que provavelmente serão alterados.
6. Implemente apenas o escopo da sprint atual e seus critérios de aceite.
7. Adicione ou atualize testes sempre que houver comportamento novo.
8. Atualize o checklist de `PLAN.md` somente quando a tarefa estiver realmente implementada e validada.
9. Ao concluir, abra um pull request com o título `feat: concluir Sprint N` ou `fix: concluir T-XXX`.
10. Se houver ambiguidade, dependência ausente, falha de teste ou alteração destrutiva necessária, pare sem marcar a tarefa como concluída e documente o bloqueio no PR.

## Validação obrigatória

Execute os comandos que forem aplicáveis ao escopo. Nunca declare a sprint concluída apenas porque uma parte do código compila.

```bash
composer validate --no-check-publish
composer install --no-interaction --prefer-dist
npm ci
php artisan test
npm run build
```

Se o ambiente não tiver banco de dados ou serviços Docker disponíveis, execute as validações estáticas e de frontend possíveis, informe claramente o que não foi executado e não oculte a limitação.

## Convenções

- Preserve a arquitetura e os padrões já existentes.
- Use Laravel Form Requests, Policies, Actions e Services quando forem compatíveis com a estrutura do projeto.
- Não introduza dependências sem necessidade.
- Não altere contratos públicos, banco de produção, pipelines ou permissões sem justificativa explícita.
- Mantenha textos de interface em português brasileiro quando não houver requisito contrário.
- Não faça merge do próprio pull request.

## Segurança

- Nunca grave tokens, senhas, chaves ou arquivos `.env` no repositório.
- Não execute comandos destrutivos ou migrações irreversíveis sem autorização.
- Trate dados de usuários de acordo com os requisitos de LGPD descritos no plano.
- Revise todas as mudanças antes de criar o PR.
