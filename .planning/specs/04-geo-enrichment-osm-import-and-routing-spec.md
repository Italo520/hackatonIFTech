# Especificação da Fase 04: Enriquecimento Geoespacial, Importação OSM e Roteirização Inteligente

## 📌 Metadados da Fase
- **ID da Fase**: `04-geo-enrichment-osm-import-and-routing`
- **Autor**: Antigravity AI & Desenvolvedor
- **Status**: `ESPECIFICAÇÃO_CONCLUÍDA`
- **Data**: 14 de Agosto de 2026
- **Pontuação de Ambiguidade Global**: `0.08` (Metas Atingidas: Claridade e Requisitos Estritos)

---

## 🎯 1. Objetivo e Visão Geral

Esta fase tem como propósito transformar as capacidades geoespaciais da plataforma **Destino Inteligente**, aproveitando a riqueza de dados abertos do **OpenStreetMap (OSM)**, **Overpass API**, **Nominatim** e do mecanismo de rotas **OSRM (Open Source Routing Machine)**.

### Pilares Fundamentais:
1. **Comando de Importação em Massa OSM (`php artisan turismo:import-osm {municipio_id}`)**:
   - Consulta a Overpass API do OpenStreetMap para o bounding box ou nome do município informado.
   - Extrai e mapeia pontos de interesse turísticos (`tourism=*`, `historic=*`, `amenity=*`, `leisure=*`).
   - Popula a tabela `atrativos` como status `pendente` (rascunho) ou `ativo` com dados de endereço, coordenadas, horários (`opening_hours`), acessibilidade (`wheelchair`) e tags de contato.
   - Evita duplicações comparando proximidade geográfica (< 50 metros) e similaridade de nome.

2. **Auto-completar de Endereço e Coordenadas GPS no Painel Administrativo**:
   - Integração nos modais/formulários de cadastro e edição de Atrativos e Eventos (`admin.atrativos` e `admin.eventos`).
   - Campo de busca assistida com debounce de 350ms consultando `/api/v1/location/search?q=...`.
   - Ao selecionar um local retornado pelo Nominatim, os campos `endereco`, `bairro`, `lat` e `lng` são preenchidos automaticamente, com opção de ajuste fino no mapa.

3. **Traçado de Linhas e Navegação Real (OSRM GeoJSON Polyline) nos Roteiros PWA**:
   - Na página de detalhe do roteiro (`/roteiro/{id}`), as paradas são conectadas no mapa Leaflet não mais por linhas retas estáticas, mas por uma rota real de ruas/caminhos calculada via OSRM (`/route/v1/driving` ou `/route/v1/walking`).
   - Renderização visual da polyline no mapa com estilo moderno e indicação de distância total e tempo estimado de deslocamento real entre paradas.

---

## 🏗️ 2. Arquitetura Técnica e Contratos de Dados

### 2.1. Serviço e Comando de Importação OSM
- **Arquivo**: `app/Console/Commands/ImportOsmAtrativosCommand.php`
- **Assinatura**: `php artisan turismo:import-osm {municipio_id} {--status=pendente} {--radius=15000}`
- **Consulta Overpass QL**:
  ```overpassql
  [out:json][timeout:25];
  (
    node["tourism"~"attraction|museum|viewpoint|gallery|theme_park"](around:{{radius}},{{lat}},{{lng}});
    node["historic"~"monument|memorial|church|ruins|castle"](around:{{radius}},{{lat}},{{lng}});
    node["leisure"~"park|nature_reserve"](around:{{radius}},{{lat}},{{lng}});
    way["tourism"~"attraction|museum"](around:{{radius}},{{lat}},{{lng}});
  );
  out center tags;
  ```
- **Mapeamento de Categorias**:
  - `tourism=museum` ou `historic=*` $\rightarrow$ Categoria 'Cultura' / 'História'
  - `natural=beach` ou `tourism=viewpoint` $\rightarrow$ Categoria 'Praias & Rios' ou 'Natureza'
  - `amenity=restaurant|cafe` $\rightarrow$ Categoria 'Gastronomia'
  - Fallback: Categoria Geral do Município.

### 2.2. Endpoint e Serviço de Roteirização OSRM
- **Rota Backend**: `GET /api/v1/routes/directions`
  - **Parâmetros**: `coordinates` (ex: `lng1,lat1;lng2,lat2;lng3,lat3`), `mode` (`walking` ou `driving`).
  - **Comportamento**: Consulta `https://router.project-osrm.org/route/v1/{mode}/{coordinates}?overview=full&geometries=geojson` com cache de 24h por rota para economizar requisições e garantir resiliência offline.
  - **Retorno JSON**:
    ```json
    {
      "success": true,
      "distance_km": 14.8,
      "duration_minutes": 28,
      "geojson": {
        "type": "LineString",
        "coordinates": [[-34.8239, -7.1147], ...]
      }
    }
    ```

### 2.3. Componente de Autocomplete no Admin
- **Blade Component / Script**: `resources/views/components/admin/location-autocomplete.blade.php` ou script injetado em `admin/atrativos/index.blade.php` e `admin/eventos/index.blade.php`.
- **Comportamento**:
  - Input com dropdown flutuante listando resultados da API `/api/v1/location/search?q=...`.
  - Ao clicar em um resultado, preenche automaticamente os inputs `endereco`, `lat` e `lng` do formulário ativo.

---

## 📐 3. Critérios de Aceitação e Falsificabilidade

1. **Importação OSM via CLI**:
   - Executar `php artisan turismo:import-osm {municipio_id}` em um município cadastrado (ex: João Pessoa/PB ou Bonito/MS).
   - O comando deve retornar sumário com total de nós encontrados, atrativos importados com sucesso e atrativos ignorados por duplicação.
   - Os novos registros devem ter latitude e longitude válidas e estar associados ao `municipio_id`.

2. **Autocomplete no Painel Administrativo**:
   - Abrir o modal "Novo Atrativo" ou "Novo Evento" no painel `/admin/atrativos` ou `/admin/eventos`.
   - Digitar 3 letras (ex: `Tambaú` ou `Cabo Branco`) e verificar a exibição da lista suspensa com opções do Nominatim.
   - Clicar em uma opção deve preencher instantaneamente os inputs `lat`, `lng` e `endereco`.

3. **Traçado Real OSRM no PWA**:
   - Abrir `/roteiro/101` no PWA.
   - O mapa Leaflet deve carregar a polyline azul traçando a rota real pelas ruas entre a Praia de Tambaú, Piscinas dos Seixas e Farol do Cabo Branco.
   - Caso a API OSRM esteja indisponível, o sistema deve aplicar fallback gracioso para polyline direta entre as coordenadas sem quebrar a tela.

4. **Automação de Testes (PHPUnit)**:
   - Criar `tests/Feature/OsmImportCommandTest.php` e `tests/Feature/RoutingApiTest.php`.
   - 100% dos testes da suíte (`php artisan test`) devem passar com sucesso.

---

## 📊 4. Matriz de Ambiguidade e Validação

| Dimensão | Score (0 a 1) | Justificativa / Mitigação |
|---|---|---|
| **Escopo & Fronteiras** | `0.05` | 3 entregas bem delimitadas (Comando Artisan, Autocomplete Admin, Traçado OSRM PWA). |
| **Arquitetura de Dados** | `0.08` | Reutiliza tabelas `atrativos`, `municipios` e a API `/api/v1/location/` já existente. |
| **Tratamento de Falhas / Offline** | `0.10` | Fallbacks claros com cache Redis/File e linhas diretas caso serviços externos oscilem. |
| **Impacto no Usuário Final** | `0.08` | Melhora expressiva na experiência do turista (rotas reais) e do gestor público (cadastro rápido). |
| **Pontuação Global Ponderada** | **`0.08`** | **Aprovado para Implementação Imediata** (Meta $\le 0.20$). |
