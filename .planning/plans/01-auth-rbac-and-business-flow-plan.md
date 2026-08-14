# Plano de Execução da Fase 01: Autenticação, RBAC, Ponto de Acesso no Header, Gate de IA e Fluxo de Empreendedores

**Referência de Especificação:** [`.planning/specs/01-auth-rbac-and-business-flow-spec.md`](file:///c:/Users/italo/Desktop/jules_session_10912906180266624816/.planning/specs/01-auth-rbac-and-business-flow-spec.md)  
**Status:** Pronto para Execução  

---

## 🎯 Tarefas de Execução

### Onda 1: Navegação & Componente de Perfil no Header do PWA
- [ ] Atualizar `resources/views/layouts/pwa.blade.php`:
  - Componente de perfil para `@guest` (Botão "Entrar" com dropdown para login, registro de turista e área de parceiros).
  - Componente de perfil para `@auth` (Avatar com iniciais, badge de papel `Turista` / `Empreendedor` / `Gestor` / `Super Admin`, link direto para `/admin` ou `/parceiro/painel`, e logout seguro).

### Onda 2: Portão Inteligente de IA (Turistas)
- [ ] Atualizar `resources/views/pwa/ia.blade.php`:
  - Se `@guest`, renderizar Card de Convite Inteligente para cadastro de turista/login e travar campo de envio.
  - Se `@auth`, liberar envio de mensagens para o Guia IA.

### Onda 3: Fluxos de Autenticação, Cadastro de Turista e Redirecionamentos Inteligentes
- [ ] Atualizar `app/Http/Controllers/Auth/RegisteredUserController.php` para atribuir `role = 'turista'` e redirecionar para `/ia`.
- [ ] Atualizar `app/Http/Controllers/Auth/AuthenticatedSessionController.php` para redirecionar conforme o papel:
  - `super_admin` / `gestor_*` -> `/admin`
  - `empreendedor` -> `/parceiro/painel`
  - `turista` -> `/` ou `/ia`
- [ ] Refinar `resources/views/auth/register.blade.php` com visual Bootstrap 5.

### Onda 4: Fluxo do Empreendedor & Homologação pelo Gestor
- [ ] Atualizar `app/Http/Controllers/Web/EmpreendedorController.php` e `resources/views/empreendedor/cadastro.blade.php` para permitir cadastro com criação de conta integrada.
- [ ] Atualizar `resources/views/empreendedor/dashboard.blade.php` com gestão de itens propostos em rascunho.
- [ ] Atualizar `app/Http/Controllers/Web/Admin/PrestadorValidationController.php` para conceder selo de qualidade e aprovar atrativos vinculados.

### Onda 5: Testes Automatizados, Validação End-to-End & Deploy
- [ ] Executar script de teste automatizado de todos os fluxos de login, RBAC, gate de IA e homologação.
- [ ] Fazer commit, push e acompanhar o deploy no Coolify.
