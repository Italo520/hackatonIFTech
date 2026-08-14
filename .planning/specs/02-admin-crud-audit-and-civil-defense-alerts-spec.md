# Especificação Funcional & Técnica (SPEC) - Fase 02

## 1. Visão Geral & Objetivos
Esta especificação consolida os requisitos para a conclusão e operacionalização plena de três eixos fundamentais do ecossistema **Destino Inteligente**:
1. **Painel de Gestão Completo**: Implementação real de CRUD (Criação, Leitura, Edição, Exclusão e Moderação) para **Atrativos**, **Eventos**, **Roteiros** e **Validação de Empreendedores / Prestadores**.
2. **Módulo de Auditoria & Trilha de Logs**: Restauração e funcionamento integral do sistema de auditoria governamental (`owen-it/laravel-auditing`), exibindo com clareza quem executou cada ação no sistema, IP, modelo alterado, diff de dados (antes vs depois) e logs de consultas da IA (LGPD).
3. **Alertas e Comunicados de Defesa Civil no PWA do Turista**: Exibição imediata e proativa dos alertas emitidos pelos gestores públicos no aplicativo do turista (Home e Mapa), com níveis de severidade visual e detalhes de segurança.

---

## 2. Personas e Casos de Uso

| Persona | Necessidade Principal | Ação no Sistema |
| :--- | :--- | :--- |
| **Gestor Municipal / Secretário** | Gerenciar o catálogo turístico oficial e homologar parceiros | Criar/editar atrativos, eventos, roteiros e aprovar cadastros de empreendedores com selo |
| **Auditor / Super Admin** | Garantir conformidade com LGPD e rastreabilidade pública | Inspecionar a trilha de auditoria completa (quem alterou, quando e o que mudou) |
| **Defesa Civil / Gestor de Risco** | Comunicar riscos climáticos e interdições aos turistas | Emitir alertas classificados por nível de urgência |
| **Turista (PWA)** | Estar informado sobre condições de segurança em tempo real | Visualizar banner de alerta da Defesa Civil e acessar detalhes |

---

## 3. Especificação Detalhada por Módulo

### 3.1. Painel de Gestão - Módulos de Conteúdo (CRUDs)

#### A. Atrativos Turísticos (`/admin/atrativos`)
- **Rotas**:
  - `GET /admin/atrativos`: Listagem com filtros por termo de busca, município e status (`ativo`, `pendente`, `inativo`).
  - `POST /admin/atrativos`: Criação de novo atrativo turístico com validação.
  - `PUT /admin/atrativos/{id}`: Edição completa dos dados.
  - `DELETE /admin/atrativos/{id}`: Exclusão com confirmação.
  - `PATCH /admin/atrativos/{id}/status`: Toggle rápido de ativação/homologação de rascunhos de empreendedores.
- **Campos do Formulário**:
  - `nome` (string, obrigatório)
  - `categoria_id` (select com categorias ativas)
  - `municipio_id` (select com municípios)
  - `descricao` (textarea)
  - `endereco` (string)
  - `lat` e `lng` (coordenadas GPS decimais)
  - `tempo_medio_visita` (minutos)
  - `status` (`ativo`, `pendente`, `inativo`)

#### B. Calendário de Eventos (`/admin/eventos`)
- **Rotas**:
  - `GET /admin/eventos`: Listagem com paginação e status.
  - `POST /admin/eventos`: Criação de novo evento.
  - `PUT /admin/eventos/{id}`: Edição de datas, local e descrição.
  - `DELETE /admin/eventos/{id}`: Exclusão de evento.
- **Campos**:
  - `nome`, `descricao`, `municipio_id`, `inicio` (datetime), `fim` (datetime), `gratuito` (boolean), `status` (`ativo`, `cancelado`, `encerrado`).

#### C. Roteiros Oficiais & Itinerários (`/admin/roteiros`)
- **Rotas**:
  - `GET /admin/roteiros`: Listagem dos roteiros com contagem de paradas.
  - `POST /admin/roteiros`: Criação de roteiro estruturado com itens associados.
  - `PUT /admin/roteiros/{id}`: Edição de dados e reordenação de atrativos.
  - `DELETE /admin/roteiros/{id}`: Exclusão de roteiro.
- **Campos**:
  - `titulo`, `tema`, `perfil`, `transporte`, `duracao` (horas), `orcamento` (decimal), `dificuldade` (`facil`, `medio`, `dificil`), seleção ordenada de `atrativo_id` (`roteiro_itens`).

#### D. Validação e Homologação de Empreendedores (`/admin/prestadores`)
- **Rotas**:
  - `GET /admin/prestadores`: Listagem com abas *Pendentes*, *Aprovados (Selo Ativo)* e *Rejeitados*.
  - `PUT /admin/prestadores/{id}`: Alteração de status com opção de conceder `selo_validado: true` e ativação automática dos atrativos vinculados ao negócio.

---

### 3.2. Módulo de Auditoria & Trilha de Logs (`/admin/auditoria`)

- **Objetivo**: Fornecer visualização em tempo real de logs do sistema e trilha de auditoria para governança pública e conformidade com a LGPD.
- **Estrutura de Abas**:
  1. **Trilha de Auditoria do Sistema (`Audits`)**:
     - Integração com `OwenIt\Auditing\Models\Audit`.
     - Exibição de: Data/Hora, Usuário Responsável (Nome, E-mail, Cargo), IP de Origem, Ação (`created`, `updated`, `deleted`), Módulo Afetado (`Atrativo`, `Prestador`, `Alerta`, `Evento`, `Roteiro`) e Modal de Diff com Valores Anteriores vs Novos.
  2. **Logs da Inteligência Artificial (`AssistantLog`)**:
     - Rastreamento de prompts de turistas, tokens gerados, tempo de resposta e garantia de anonimização de dados sensíveis (PII scrubbing).
  3. **Eventos de Analytics (`AnalyticEvent`)**:
     - Visualizações de atrativos, buscas e engajamento.

---

### 3.3. Sistema de Alertas e Defesa Civil no PWA do Turista

- **Origem dos Dados**: Alertas emitidos pelos gestores em `/admin/alertas` (tabela `alertas`, campos: `titulo`, `corpo`, `urgencia`, `municipio_id`, `created_at`).
- **Níveis de Urgência**:
  - `aviso` (Amarelo / Informação preventiva)
  - `alerta` (Laranja / Atenção e cautela)
  - `urgente` / `emergencia` (Vermelho / Perigo iminente com animação pulse)
- **Exibição no PWA**:
  - **Página Inicial (`pwa.home`)**: Banner de alerta posicionado no topo, logo abaixo da barra de navegação/pesquisa.
  - **Página de Mapa (`pwa.mapa`)**: Chip de alerta de segurança no topo do mapa.
  - **Modal Interativo de Defesa Civil**: Ao tocar no alerta, abre um modal estilizado com o comunicado oficial, recomendações de segurança, canal de emergência (199 / 193) e opção de dispensar a notificação.
- **Endpoint API**:
  - `GET /api/v1/alertas/ativos`: Retorna JSON com os alertas vigentes para consumo reativo e offline.

---

## 4. Critérios de Aceite (Falsificabilidade)

- [ ] **Atrativos Admin**: Gestor consegue criar um novo atrativo e ele é gravado no banco de dados e visível na tabela com paginação.
- [ ] **Eventos Admin**: Gestor consegue cadastrar e editar eventos com datas válidas.
- [ ] **Roteiros Admin**: Gestor consegue criar um roteiro oficial vinculando múltiplos atrativos.
- [ ] **Validação Empreendedores**: Aprovar um prestador pendente atualiza o status para `aprovado`, ativa o selo e seus atrativos.
- [ ] **Auditoria Funcional**: Acessar `/admin/auditoria` exibe os registros da tabela `audits` com detalhes de usuário, ação e diff, sem telas em branco ou erros.
- [ ] **Alertas no PWA**: Ao cadastrar um alerta na Defesa Civil (`/admin/alertas`), o banner de alerta aparece imediatamente no PWA do turista (`/` e `/mapa`).
- [ ] **Suíte de Testes**: Todos os testes unitários e de integração existentes continuam passando com 100% de sucesso.
