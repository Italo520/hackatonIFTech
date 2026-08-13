# Roteiro de Demo para a Banca (Hackathon)

Siga os passos abaixo para demonstrar os 5 fluxos principais exigidos no edital. O sistema deve estar rodando via Docker (`docker compose up -d`) e com o banco semeado (`php artisan migrate:fresh --seed`).

## 1. Turista busca em linguagem natural
- **Ação:** Abra o app mobile, acesse o chat IA. Digite "roteiro gratuito em família".
- **Comprovação:** A IA responde citando os atrativos da base oficial. Veja o selo "[Conteúdo Gerado por IA]".

## 2. Turista gera roteiro e baixa para offline
- **Ação:** No app, clique em "Gerar Roteiro por IA". Após a geração, clique em "Baixar para Offline".
- **Comprovação:** Mostre a barra de progresso (tiles baixando). Ative o *Modo Avião* no dispositivo e mostre que fotos, mapas e detalhes do roteiro continuam visíveis.

## 3. QR Code in loco
- **Ação:** Aponte a câmera para o QR Code de demonstração.
- **Comprovação:** O app intercepta o Deep Link e abre imediatamente a tela rica do Atrativo (com áudio/audiodescrição disponíveis).

## 4. Empreendedor e Selo do Município
- **Ação (Empreendedor):** Faça login como empreendedor e crie um novo negócio anexando um documento (via `/parceiro/cadastro`).
- **Ação (Gestor):** Em outra aba, abra o Painel Gestor (`/admin/prestadores/fila`), veja o cadastro pendente e aprove.
- **Comprovação:** Volte ao painel do Empreendedor e mostre o "Selo de Validado" ativado.

## 5. Dashboards, LGPD e Alertas (Secretário)
- **Ação (Alertas):** No painel Admin, crie um alerta de urgência "Emergência". Mostre ele aparecendo no app do turista.
- **Ação (Heatmap & KPIs):** Acesse a Home do Admin (`/admin`), mostre os gráficos e o Heatmap respeitando supressão k-anonimato (LGPD).
- **Ação (LGPD Export):** Mostre o turista exportando e/ou deletando seus dados via formulário de Privacidade.
