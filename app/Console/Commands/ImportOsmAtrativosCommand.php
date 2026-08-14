<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Atrativo;

class ImportOsmAtrativosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'turismo:import-osm 
                            {municipio_id : ID do município no banco de dados} 
                            {--status=pendente : Status inicial dos atrativos (pendente|ativo)} 
                            {--radius=15000 : Raio de busca em metros (padrão: 15km)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa e enriquece pontos turísticos reais do OpenStreetMap via Overpass API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $municipioId = $this->argument('municipio_id');
        $status = $this->option('status') ?? 'pendente';
        $radius = (int) ($this->option('radius') ?? 15000);

        $municipio = Municipio::find($municipioId);
        if (!$municipio) {
            $this->error("Município com ID {$municipioId} não encontrado no banco de dados.");
            return 1;
        }

        $this->info("Iniciando importação de atrativos OSM para: {$municipio->nome} - {$municipio->uf}");

        // 1. Obter coordenadas centrais do município
        $center = $this->resolveMunicipioCenter($municipio);
        if (!$center) {
            $this->error("Não foi possível determinar as coordenadas geográficas de {$municipio->nome}.");
            return 1;
        }

        $lat = $center['lat'];
        $lng = $center['lng'];
        $this->info("Coordenadas centrais detectadas: Lat {$lat}, Lng {$lng} (Raio: {$radius}m)");

        // 2. Montar consulta Overpass QL
        $query = $this->buildOverpassQuery($lat, $lng, $radius);

        $this->line("Consultando Overpass API do OpenStreetMap...");
        $elements = $this->fetchOverpassData($query);

        if (empty($elements)) {
            $this->warn("Nenhum ponto de interesse turístico retornado pela Overpass API para esta região.");
            return 0;
        }

        $this->info("Total de elementos brutos encontrados no OSM: " . count($elements));

        // 3. Processar e mapear categorias
        $categorias = Categoria::all();
        $importedCount = 0;
        $skippedDuplicates = 0;
        $skippedNoName = 0;

        $rows = [];

        foreach ($elements as $el) {
            $tags = $el['tags'] ?? [];
            $nome = $tags['name'] ?? null;

            if (empty($nome)) {
                $skippedNoName++;
                continue;
            }

            $elLat = $el['lat'] ?? ($el['center']['lat'] ?? null);
            $elLng = $el['lon'] ?? ($el['center']['lon'] ?? null);

            if (is_null($elLat) || is_null($elLng)) {
                continue;
            }

            // Verificar se já existe atrativo duplicado
            if ($this->isDuplicate($municipio->id, $nome, (float)$elLat, (float)$elLng)) {
                $skippedDuplicates++;
                continue;
            }

            // Mapear Categoria
            $categoria = $this->matchCategory($tags, $categorias);

            // Mapear Acessibilidade PCD
            $acessibilidade = $this->parseAccessibility($tags);

            // Mapear Endereço
            $endereco = $this->parseAddress($tags, $municipio);

            // Mapear Descrição
            $descricao = $tags['description'] 
                ?? $tags['tourism:description'] 
                ?? $tags['historic:description'] 
                ?? ($nome . ' - Ponto de interesse turístico localizado em ' . $municipio->nome . ' (' . $municipio->uf . ').');

            // Mapear Horários
            $horarios = [];
            if (!empty($tags['opening_hours'])) {
                $horarios = ['padrao' => $tags['opening_hours']];
            }

            // Criar Atrativo no Banco
            $atrativo = Atrativo::create([
                'municipio_id' => $municipio->id,
                'categoria_id' => $categoria?->id ?? ($categorias->first()?->id ?? 1),
                'nome' => $nome,
                'descricao' => $descricao,
                'historia' => $tags['wikipedia'] ?? ($tags['wikidata'] ?? null),
                'endereco' => $endereco,
                'lat' => (float)$elLat,
                'lng' => (float)$elLng,
                'horarios' => $horarios,
                'acessibilidade' => $acessibilidade,
                'tempo_medio_visita' => 60,
                'status' => $status,
            ]);

            // Resolver foto real (Wikipedia / Wikimedia / OSM Image)
            $imageUrl = $this->resolveOsmImage($tags, $nome, $categoria?->slug);
            $atrativo->midias()->create([
                'tipo' => 'foto',
                'url' => $imageUrl,
                'alt_text' => 'Foto de ' . $nome . ' em ' . $municipio->nome,
                'autor' => $tags['image:artist'] ?? ($tags['source'] ?? 'OpenStreetMap / Wikimedia'),
                'licenca' => $tags['image:license'] ?? 'CC-BY-SA',
            ]);

            $importedCount++;
            $rows[] = [
                $atrativo->id,
                mb_strimwidth($nome, 0, 30, '...'),
                $categoria?->nome ?? 'Geral',
                number_format($elLat, 4) . ', ' . number_format($elLng, 4),
                $status
            ];
        }

        if (count($rows) > 0) {
            $this->table(['ID', 'Nome', 'Categoria', 'Coordenadas (Lat, Lng)', 'Status'], array_slice($rows, 0, 15));
            if (count($rows) > 15) {
                $this->line("... e mais " . (count($rows) - 15) . " atrativos importados.");
            }
        }

        $this->newLine();
        $this->info("=== Relatório de Importação OSM ===");
        $this->info("✓ Novos Atrativos Importados: {$importedCount}");
        $this->comment("↷ Duplicados Ignorados: {$skippedDuplicates}");
        $this->comment("↷ Sem Nome / Inválidos: {$skippedNoName}");
        $this->info("Importação concluída com sucesso!");

        return 0;
    }

    /**
     * Resolve o centro geográfico do município
     */
    protected function resolveMunicipioCenter(Municipio $municipio): ?array
    {
        // 1. Tentar pegar das coordenadas de um atrativo existente do município
        $existing = $municipio->atrativos()->whereNotNull('lat')->whereNotNull('lng')->first();
        if ($existing) {
            return ['lat' => (float)$existing->lat, 'lng' => (float)$existing->lng];
        }

        // 2. Consultar Nominatim OSM
        try {
            $query = trim($municipio->nome . ' ' . $municipio->uf . ' Brasil');
            $response = Http::withHeaders([
                'User-Agent' => 'TurismoPWA/1.0 (turismo-app@local.dev)',
            ])->timeout(8)->get('https://nominatim.openstreetmap.org/search', [
                'format' => 'jsonv2',
                'q' => $query,
                'countrycodes' => 'br',
                'limit' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $first = $response->json()[0];
                return [
                    'lat' => (float)$first['lat'],
                    'lng' => (float)$first['lon'],
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao geocodificar centro do município via Nominatim: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Monta a consulta Overpass QL
     */
    protected function buildOverpassQuery(float $lat, float $lng, int $radius): string
    {
        return "[out:json][timeout:30];
        (
          node[\"tourism\"~\"attraction|museum|viewpoint|gallery|theme_park|zoo|aquarium|information\"](around:{$radius},{$lat},{$lng});
          node[\"historic\"~\"monument|memorial|church|ruins|castle|heritage\"](around:{$radius},{$lat},{$lng});
          node[\"leisure\"~\"park|nature_reserve|water_park|garden\"](around:{$radius},{$lat},{$lng});
          node[\"natural\"~\"beach|waterfall|cave_entrance|peak\"](around:{$radius},{$lat},{$lng});
          way[\"tourism\"~\"attraction|museum|viewpoint|theme_park|zoo\"](around:{$radius},{$lat},{$lng});
          way[\"historic\"~\"monument|memorial|church|ruins|castle\"](around:{$radius},{$lat},{$lng});
          way[\"leisure\"~\"park|nature_reserve\"](around:{$radius},{$lat},{$lng});
          way[\"natural\"~\"beach\"](around:{$radius},{$lat},{$lng});
        );
        out center tags;";
    }

    /**
     * Realiza a requisição HTTP para a Overpass API
     */
    protected function fetchOverpassData(string $query): array
    {
        $endpoints = [
            'https://overpass-api.de/api/interpreter',
            'https://overpass.kumi.systems/api/interpreter',
        ];

        foreach ($endpoints as $url) {
            try {
                $response = Http::asForm()->timeout(35)->post($url, [
                    'data' => $query
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    return $json['elements'] ?? [];
                }
            } catch (\Exception $e) {
                Log::warning("Falha ao consultar endpoint Overpass {$url}: " . $e->getMessage());
            }
        }

        return [];
    }

    /**
     * Verifica duplicidade de atrativo
     */
    protected function isDuplicate(int $municipioId, string $nome, float $lat, float $lng): bool
    {
        // 1. Por nome idêntico ou muito similar no mesmo município
        $existsByName = Atrativo::where('municipio_id', $municipioId)
            ->whereRaw('LOWER(nome) = ?', [mb_strtolower(trim($nome))])
            ->exists();

        if ($existsByName) {
            return true;
        }

        // 2. Por proximidade geográfica extrema (< 50 metros)
        $candidates = Atrativo::where('municipio_id', $municipioId)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        foreach ($candidates as $cand) {
            $dist = $cand->calcularDistanciaKm($lat, $lng);
            if (!is_null($dist) && $dist < 0.05) { // menos de 50 metros
                return true;
            }
        }

        return false;
    }

    /**
     * Mapeia as tags do OSM para uma Categoria do sistema
     */
    protected function matchCategory(array $tags, $categorias): ?Categoria
    {
        $tourism = $tags['tourism'] ?? '';
        $historic = $tags['historic'] ?? '';
        $natural = $tags['natural'] ?? '';
        $leisure = $tags['leisure'] ?? '';
        $amenity = $tags['amenity'] ?? '';

        if ($natural === 'beach' || $natural === 'waterfall' || $leisure === 'water_park') {
            return $categorias->firstWhere('slug', 'rios') 
                ?? $categorias->first(fn($c) => str_contains(mb_strtolower($c->nome), 'praia') || str_contains(mb_strtolower($c->nome), 'rio'));
        }

        if ($tourism === 'museum' || $tourism === 'gallery' || !empty($historic)) {
            return $categorias->firstWhere('slug', 'cultura') 
                ?? $categorias->first(fn($c) => str_contains(mb_strtolower($c->nome), 'cultura') || str_contains(mb_strtolower($c->nome), 'hist'));
        }

        if ($leisure === 'park' || $leisure === 'nature_reserve' || $tourism === 'viewpoint' || $natural === 'peak') {
            return $categorias->firstWhere('slug', 'grutas') 
                ?? $categorias->firstWhere('slug', 'aventura')
                ?? $categorias->first(fn($c) => str_contains(mb_strtolower($c->nome), 'natureza') || str_contains(mb_strtolower($c->nome), 'trilha'));
        }

        if (in_array($amenity, ['restaurant', 'cafe', 'bar', 'food_court'])) {
            return $categorias->firstWhere('slug', 'gastronomia') 
                ?? $categorias->first(fn($c) => str_contains(mb_strtolower($c->nome), 'gastro'));
        }

        return $categorias->first();
    }

    /**
     * Extrai acessibilidade PCD a partir das tags OSM
     */
    protected function parseAccessibility(array $tags): array
    {
        $items = [];
        $wheelchair = $tags['wheelchair'] ?? '';

        if ($wheelchair === 'yes') {
            $items[] = 'cadeirante';
            $items[] = 'mobilidade_reduzida';
        } elseif ($wheelchair === 'limited') {
            $items[] = 'mobilidade_reduzida';
        }

        if (($tags['tactile_paving'] ?? '') === 'yes') {
            $items[] = 'deficiencia_visual';
        }

        return array_values(array_unique($items));
    }

    /**
     * Monta o endereço formatado a partir das tags OSM
     */
    protected function parseAddress(array $tags, Municipio $municipio): string
    {
        $parts = [];

        if (!empty($tags['addr:street'])) {
            $street = $tags['addr:street'];
            if (!empty($tags['addr:housenumber'])) {
                $street .= ', ' . $tags['addr:housenumber'];
            }
            $parts[] = $street;
        }

        if (!empty($tags['addr:suburb'])) {
            $parts[] = $tags['addr:suburb'];
        }

        $parts[] = $municipio->nome . ' - ' . $municipio->uf;

        if (!empty($tags['addr:postcode'])) {
            $parts[] = 'CEP ' . $tags['addr:postcode'];
        }

        return implode(', ', $parts);
    }

    /**
     * Resolve foto real a partir das tags OSM (Wikipedia, Wikimedia Commons, direct image)
     */
    protected function resolveOsmImage(array $tags, string $nome, ?string $categoriaSlug): string
    {
        // 1. Tag direta de imagem
        if (!empty($tags['image'])) {
            $img = $tags['image'];
            if (filter_var($img, FILTER_VALIDATE_URL)) {
                return $img;
            }
        }

        // 2. Wikipedia tag (ex: "pt:Farol do Cabo Branco")
        if (!empty($tags['wikipedia'])) {
            $wiki = $tags['wikipedia'];
            $parts = explode(':', $wiki);
            $lang = count($parts) > 1 ? $parts[0] : 'pt';
            $pageTitle = count($parts) > 1 ? $parts[1] : $parts[0];

            try {
                $wikiRes = Http::timeout(4)->get("https://{$lang}.wikipedia.org/api/rest_v1/page/summary/" . urlencode($pageTitle));
                if ($wikiRes->successful()) {
                    $json = $wikiRes->json();
                    if (!empty($json['originalimage']['source'])) {
                        return $json['originalimage']['source'];
                    }
                    if (!empty($json['thumbnail']['source'])) {
                        return $json['thumbnail']['source'];
                    }
                }
            } catch (\Exception $e) {
                // Silently fallback
            }
        }

        // 3. Fallback inteligente por categoria
        $fallbacks = [
            'rios' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'praias-e-rios' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
            'aventura' => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=1000&q=80',
            'grutas' => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=1000&q=80',
            'gastronomia' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=80',
            'cultura' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1000&q=80',
        ];

        return $fallbacks[$categoriaSlug] ?? 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1000&q=80';
    }
}
