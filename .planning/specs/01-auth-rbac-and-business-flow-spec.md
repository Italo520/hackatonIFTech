# Especificação Técnica: Autenticação, RBAC, Gate de IA e Fluxo de Empreendedores

**Status:** Aprovado para Planejamento  
**Ambiguity Score:** 0.05 / 1.00 (Passou no Gate de Especificação)  
**Data:** 14/08/2026  

---

## 1. Visão Geral e Objetivos de Negócio

O sistema **Destino Inteligente** atende a múltiplos perfis de usuários na mesma plataforma, unindo a experiência do turista (PWA mobile-first) e a gestão pública/privada (Painel Administrativo Bootstrap 5):
1. **Turista / Cidadão Anônimo**: Navega livremente pelas atrações, mapa interativo, eventos, roteiros e utilidades públicas sem necessidade de cadastro prévio.
2. **Turista Autenticado (`role: turista`)**: Realiza cadastro simples (Nome, E-mail, Senha) para desbloquear o **Chat Inteligente com IA** e salvar preferências/roteiros.
3. **Super Administrador (`role: super_admin`)**: Tem acesso irrestrito ao painel de gestão (`/admin`), controle de usuários, auditoria completa, relatórios de impacto, heatmaps e configurações gerais.
4. **Gestor Municipal (`role: gestor_conteudo`, `gestor_cadastros`, `secretario`, `prefeito`)**: Tem acesso ao painel de gestão (`/admin`) para homologar atrativos, gerenciar eventos oficiais, disparar alertas à população e **analisar/validar credenciamentos de empreendedores** na Fila de Prestadores (`/admin/prestadores`).
5. **Empreendedor / Parceiro Local (`role: empreendedor`)**: Cadastra-se por rota dedicada (`/parceiro/cadastro`), preenche informações do seu negócio, submete documentos e cria atrativos/eventos em estado de `rascunho`/`pendente`. Após aprovação pelo Gestor, recebe o **Selo de Qualidade Turística** e seus atrativos tornam-se públicos no app e recomendados pela IA.

---

## 2. Matriz de Perfis e Permissões (RBAC)

| Perfil | Acesso PWA Turista | Chat com IA (`/ia`) | Painel Gestão (`/admin`) | Painel Parceiro (`/parceiro/painel`) | Fila de Aprovação (`/admin/prestadores`) |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Visitante Anônimo (Guest)** | ✅ Total | ❌ Bloqueado (Convite) | ❌ Bloqueado (403/Login) | ❌ Bloqueado (Login) | ❌ Bloqueado |
| **Turista (`turista`)** | ✅ Total | ✅ Liberado | ❌ Bloqueado (403/Login) | ❌ Bloqueado | ❌ Bloqueado |
| **Empreendedor (`empreendedor`)** | ✅ Total | ✅ Liberado | ❌ Bloqueado | ✅ Liberado (Rascunhos & Aprovados) | ❌ Bloqueado |
| **Gestor (`gestor_conteudo` / etc)** | ✅ Total | ✅ Liberado | ✅ Total (`/admin`) | ✅ Visualiza como Gestor | ✅ Homologa e Concede Selo |
| **Super Admin (`super_admin`)** | ✅ Total | ✅ Liberado | ✅ Total + Auditoria | ✅ Total | ✅ Homologa e Concede Selo |

---

## 3. Requisitos Funcionais Detalhados

### 3.1. Ponto de Acesso no Header da Aplicação PWA (`layouts/pwa.blade.php`)
- **Para Visitante Anônimo (`@guest`)**:
  - No canto superior direito do header do PWA, exibir o botão **"Entrar"** com ícone `bi-person-circle`.
  - Ao clicar, abre dropdown/modal com:
    - Botão `Entrar na Minha Conta` (leva a `/login`).
    - Botão `Criar Conta de Turista` (leva a `/register`).
    - Link `Área do Parceiro & Empreendedor` (leva a `/parceiro/cadastro`).
- **Para Usuário Autenticado (`@auth`)**:
  - Exibir avatar/iniciais do usuário com badge indicativa do papel (`Turista`, `Gestor`, `Empreendedor`, `Super Admin`).
  - Dropdown com ações contextuais:
    - Se `super_admin` ou `gestor_*`: Botão de destaque **"Acessar Painel de Gestão"** (link para `/admin`).
    - Se `empreendedor`: Botão **"Painel do Meu Negócio"** (link para `/parceiro/painel`).
    - Se `turista`: Exibir nome, e-mail e status de Turista ativo.
    - Botão **"Sair (Logout)"** com encerramento de sessão limpo via rota segura.

### 3.2. Portão de IA (Gate de Autenticação do Chat com IA)
- **Localização**: View [`resources/views/pwa/ia.blade.php`](file:///c:/Users/italo/Desktop/jules_session_10912906180266624816/resources/views/pwa/ia.blade.php) e Controller de IA.
- **Comportamento para Visitante Anônimo**:
  - A interface exibe a identidade visual do assistente e sugestões de perguntas.
  - Ao tentar interagir no campo de texto ou clicar em um chip de sugestão, o sistema exibe o **Card de Convite Inteligente**:
    > *"💬 Converse com nosso Guia IA Oficial!*\n\n*Crie sua conta gratuita de Turista ou faça login para ter um roteiro personalizado com inteligência geográfica."*
    - Botão `Fazer Login` (`/login`).
    - Botão `Cadastre-se Grátis` (`/register`).
- **Comportamento para Usuário Autenticado (`turista`, `empreendedor`, `gestor`, `super_admin`)**:
  - Campo de input liberado com comunicação direta com a API de IA geográfica.

### 3.3. Cadastro de Turista vs Cadastro de Empreendedor
- **Cadastro de Turista (`/register`)**:
  - Formulário limpo e amigável em Bootstrap 5:
    - Nome completo.
    - E-mail.
    - Senha e confirmação.
  - Automaticamente atribui `role: 'turista'` ao usuário registrado.
  - Redireciona imediatamente para `/ia` ou para a página anterior, pronto para uso.
- **Cadastro de Empreendedor (`/parceiro/cadastro`)**:
  - Rota dedicada focada em negócios e prestadores de serviços turísticos:
    - Dados do responsável: Nome, E-mail, Senha, Telefone/WhatsApp.
    - Dados do estabelecimento: Razão Social / Nome Fantasia, Tipo (Pousada, Restaurante, Passeios, Artesanato), CNPJ/CPF, Endereço.
    - Upload ou declaração de documentos municipais/cadastur.
  - Cria o usuário com `role: 'empreendedor'` e o registro em `prestadores` com `status: 'pendente'`.
  - Redireciona para `/parceiro/painel`.

### 3.4. Ciclo de Vida e Edição do Empreendimento (Rascunho vs Aprovado)
- **Painel do Empreendedor (`/parceiro/painel`)**:
  - **Estado Pendente**:
    - Exibe banner informativo de análise pela Secretaria de Turismo.
    - Permite ao empreendedor cadastrar ou editar seus Atrativos/Serviços e Eventos vinculados.
    - Todos os itens criados recebem `status: 'rascunho'` ou `status: 'pendente'`.
  - **Estado Aprovado**:
    - Exibe badge **Selo Oficial de Qualidade Turística**.
    - Os atrativos/eventos validados passam a ter `status: 'ativo'` e são indexados no PWA do turista.
- **Fila de Homologação da Gestão (`/admin/prestadores`)**:
  - O Gestor visualiza os documentos, dados e atrativos propostos.
  - Ações disponíveis:
    - **Aprovar**: Define status do prestador para `aprovado`, ativa o selo de qualidade e publica os atrativos associados.
    - **Solicitar Complemento**: Informa motivo de pendência para o empreendedor corrigir documentos.
    - **Rejeitar**: Desativa a solicitação com justificativa.

---

## 4. Critérios de Aceitação e Testes

1. **Header do PWA**:
   - Visitante anônimo vê o botão "Entrar" e consegue navegar até `/login` e `/register`.
   - Usuário logado como Super Admin ou Gestor vê botão para o Painel Admin (`/admin`).
   - Usuário logado como Empreendedor vê botão para o Painel do Parceiro (`/parceiro/painel`).
2. **Gate do Chat IA**:
   - Usuário anônimo recebe o card/modal de convite ao tentar interagir com a IA.
   - Turista autenticado envia mensagens e recebe respostas normalmente.
3. **Fluxos de Cadastro**:
   - Registro em `/register` cria usuário com `role = 'turista'` e faz login automático.
   - Registro em `/parceiro/cadastro` cria `role = 'empreendedor'` e registro `prestador` pendente.
4. **Homologação pelo Gestor**:
   - Gestor acessa `/admin/prestadores`, aprova o credenciamento e o prestador recebe o status `aprovado` com selo refletido no painel.
