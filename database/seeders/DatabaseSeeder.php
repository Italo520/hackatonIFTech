<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\UtilidadePublica;
use App\Models\Roteiro;
use App\Models\RoteiroItem;
use App\Models\QrCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        config(['audit.console' => false]);
        User::unsetEventDispatcher();

        // 1. Roles & Users Demo
        $roles = [
            'super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 
            'gestor_cadastros', 'atendente', 'empreendedor', 'turista'
        ];
        
        foreach ($roles as $role) {
            User::updateOrCreate(
                ['email' => $role . '@demo.com'],
                [
                    'name' => 'Demo ' . ucfirst(str_replace('_', ' ', $role)),
                    'password' => Hash::make('password'),
                    'role' => $role,
                ]
            );
        }
        
        $admin = User::where('role', 'gestor_conteudo')->first();

        // 2. Municípios Reais
        $municipioBonito = Municipio::firstOrCreate(
            ['nome' => 'Bonito'],
            ['uf' => 'MS', 'tema_visual' => 'default']
        );

        $municipioJampa = Municipio::firstOrCreate(
            ['nome' => 'João Pessoa'],
            ['uf' => 'PB', 'tema_visual' => 'praia']
        );

        $municipioRecife = Municipio::firstOrCreate(
            ['nome' => 'Recife'],
            ['uf' => 'PE', 'tema_visual' => 'cultura']
        );

        $municipioNatal = Municipio::firstOrCreate(
            ['nome' => 'Natal'],
            ['uf' => 'RN', 'tema_visual' => 'praia']
        );

        $municipioSP = Municipio::firstOrCreate(
            ['nome' => 'São Paulo'],
            ['uf' => 'SP', 'tema_visual' => 'urbano']
        );

        // 3. Categorias Realistas
        $catAventura = Categoria::firstOrCreate(['slug' => 'aventura'], ['nome' => 'Aventura & Trilhas', 'icone' => 'bi-bicycle', 'tipo' => 'atrativo']);
        $catAgua = Categoria::firstOrCreate(['slug' => 'rios'], ['nome' => 'Praias, Rios e Piscinas', 'icone' => 'bi-water', 'tipo' => 'atrativo']);
        $catGrutas = Categoria::firstOrCreate(['slug' => 'grutas'], ['nome' => 'Monumentos & Natureza', 'icone' => 'bi-geo', 'tipo' => 'atrativo']);
        $catGastronomia = Categoria::firstOrCreate(['slug' => 'gastronomia'], ['nome' => 'Gastronomia Regional', 'icone' => 'bi-cup-hot', 'tipo' => 'servico']);
        $catHospedagem = Categoria::firstOrCreate(['slug' => 'hospedagem'], ['nome' => 'Hospedagem', 'icone' => 'bi-house-heart', 'tipo' => 'servico']);
        $catCultura = Categoria::firstOrCreate(['slug' => 'cultura'], ['nome' => 'História & Cultura', 'icone' => 'bi-bank', 'tipo' => 'atrativo']);

        // 4. Atrativos Realistas com Coordenadas GPS e Fotos Reais
        $atrativosData = [
            // --- JOÃO PESSOA (PB) ---
            [
                'municipio' => $municipioJampa,
                'categoria' => $catAgua,
                'nome' => 'Praia de Tambaú',
                'descricao' => 'Uma das praias urbanas mais famosas de João Pessoa, com águas mornas, calçadão movimentado, feirinha de artesanato e passeios de catamarã para Picãozinho.',
                'historia' => 'O coração turístico da capital paraibana, com excelente infraestrutura e vida noturna.',
                'endereco' => 'Av. Almirante Tamandaré, Tambaú, João Pessoa - PB',
                'tempo' => 180,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -7.1147,
                'lng' => -34.8239,
                'foto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catGrutas,
                'nome' => 'Farol do Cabo Branco',
                'descricao' => 'O ponto mais oriental das Américas continentais onde o sol nasce primeiro. Vista panorâmica espetacular do oceano atlântico e falésias.',
                'historia' => 'Inaugurado em 1972 com formato triangular único que simboliza uma planta de sisal.',
                'endereco' => 'Ponta do Seixas, Cabo Branco, João Pessoa - PB',
                'tempo' => 60,
                'acessibilidade' => ['cadeirante'],
                'lat' => -7.1477,
                'lng' => -34.7963,
                'foto' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catAgua,
                'nome' => 'Piscinas Naturais dos Seixas',
                'descricao' => 'Aquários naturais formados por corais na maré baixa com águas cristalinas repletas de peixes coloridos, ideal para mergulho livre e stand up paddle.',
                'historia' => 'Formação de recifes de corais protegida acessível por embarcações credenciadas.',
                'endereco' => 'Praia dos Seixas, João Pessoa - PB',
                'tempo' => 150,
                'acessibilidade' => ['deficiencia_auditiva'],
                'lat' => -7.1597,
                'lng' => -34.7877,
                'foto' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catCultura,
                'nome' => 'Centro Cultural São Francisco',
                'descricao' => 'Um dos mais importantes complexos barrocos do Brasil, com igreja, convento, claustro com azulejaria portuguesa e rico acervo de arte sacra.',
                'historia' => 'Construído a partir de 1589 pela Ordem Franciscana, tombado pelo IPHAN.',
                'endereco' => 'Praça São Francisco, Centro Histórico, João Pessoa - PB',
                'tempo' => 90,
                'acessibilidade' => ['cadeirante'],
                'lat' => -7.1155,
                'lng' => -34.8864,
                'foto' => 'https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catGastronomia,
                'nome' => 'Mangai João Pessoa',
                'descricao' => 'Templo da culinária nordestina, famoso pelo buffet com dezenas de pratos típicos como carne de sol na nata, baião de dois e queijo coalho.',
                'historia' => 'Fundado na Paraíba, tornou-se referência nacional em gastronomia regional.',
                'endereco' => 'Av. Edson Ramalho, 696, Manaíra, João Pessoa - PB',
                'tempo' => 90,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -7.1067,
                'lng' => -34.8315,
                'foto' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catGastronomia,
                'nome' => 'Nau Frutos do Mar',
                'descricao' => 'Restaurante contemporâneo especializado em frutos do mar com arquitetura arrojada e pratos premiados com camarões e peixes nobres.',
                'historia' => 'Nascido em João Pessoa e expandido para várias capitais do país.',
                'endereco' => 'R. Lupércio Branco, 130, Manaíra, João Pessoa - PB',
                'tempo' => 90,
                'acessibilidade' => ['cadeirante'],
                'lat' => -7.1189,
                'lng' => -34.8302,
                'foto' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
            ],

            // --- BONITO (MS) ---
            [
                'municipio' => $municipioBonito,
                'categoria' => $catAgua,
                'nome' => 'Flutuação no Rio Sucuri',
                'descricao' => 'Uma das águas mais cristalinas do planeta Terra. Flutuação tranquila em meio a piraputangas, dourados e vegetação exuberante.',
                'historia' => 'O Rio Sucuri é famoso por sua nascente e pela visibilidade de mais de 40 metros.',
                'endereco' => 'Fazenda São Geraldo, Rodovia Bonito - São Geraldo, Bonito - MS',
                'tempo' => 120,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -21.2642,
                'lng' => -56.5516,
                'foto' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catGrutas,
                'nome' => 'Gruta do Lago Azul',
                'descricao' => 'Monumento natural e cartão-postal do ecoturismo brasileiro, uma caverna com um lago subterrâneo de coloração azul cobalto fascinante.',
                'historia' => 'Descoberta em 1924 por índios Terena, a gruta é tombada pelo IPHAN e abriga fósseis da megafauna pré-histórica.',
                'endereco' => 'Rodovia MS 382, Km 20, Bonito - MS',
                'tempo' => 90,
                'acessibilidade' => [],
                'lat' => -21.1469,
                'lng' => -56.5861,
                'foto' => 'https://images.unsplash.com/photo-1499244571948-7cc805602889?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catAventura,
                'nome' => 'Bóia Cross no Rio Formoso',
                'descricao' => 'Aventura em bóias individuais descendo corredeiras e cachoeiras refrescantes pelas águas calmas do Rio Formoso.',
                'historia' => 'Atividade tradicional que combina emoção ecológica e segurança profissional.',
                'endereco' => 'Parque Ecológico Rio Formoso, Bonito - MS',
                'tempo' => 60,
                'acessibilidade' => ['deficiencia_auditiva'],
                'lat' => -21.1895,
                'lng' => -56.4523,
                'foto' => 'https://images.unsplash.com/photo-1533230491024-e22d9976da28?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catGastronomia,
                'nome' => 'Casa do João',
                'descricao' => 'Restaurante gastronômico premiado de Bonito, especializado em peixes nobres do pantanal como Traíra sem espinho e Pintado ao Urucum.',
                'historia' => 'Fundado pela família de Seu João, tornou-se ponto de encontro gastronômico imperdível.',
                'endereco' => 'Rua Cel. Nélson Felício dos Santos, Centro, Bonito - MS',
                'tempo' => 120,
                'acessibilidade' => ['cadeirante', 'cego'],
                'lat' => -21.1275,
                'lng' => -56.4831,
                'foto' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
            ],

            // --- RECIFE & OLINDA (PE) ---
            [
                'municipio' => $municipioRecife,
                'categoria' => $catCultura,
                'nome' => 'Marco Zero e Recife Antigo',
                'descricao' => 'Praça histórica na beira do cais com a Rosa dos Ventos de Cícero Dias, esculturas de Brennand e casarios coloniais holandeses.',
                'historia' => 'Ponto de fundação da cidade e centro das celebrações do frevo e do maracatu pernambucano.',
                'endereco' => 'Praça Barão do Rio Branco, Recife Antigo, Recife - PE',
                'tempo' => 120,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -8.0631,
                'lng' => -34.8711,
                'foto' => 'https://images.unsplash.com/photo-1548625149-fc4a29cf7092?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioRecife,
                'categoria' => $catAgua,
                'nome' => 'Praia de Boa Viagem',
                'descricao' => 'A mais famosa praia urbana de Pernambuco, com extensa faixa de areia, arrecifes naturais que formam piscinas mornas e calçadão icônico.',
                'historia' => 'Símbolo da modernização recifense ao longo do século XX.',
                'endereco' => 'Av. Boa Viagem, Recife - PE',
                'tempo' => 180,
                'acessibilidade' => ['cadeirante'],
                'lat' => -8.1276,
                'lng' => -34.9022,
                'foto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ],

            // --- NATAL (RN) ---
            [
                'municipio' => $municipioNatal,
                'categoria' => $catGrutas,
                'nome' => 'Dunas de Genipabu',
                'descricao' => 'Parque ecológico com dunas móveis monumentais, lagoas de água doce e passeios com emoção de buggy e dromedários.',
                'historia' => 'Um dos cartões postais mais fotografados do litoral potiguar.',
                'endereco' => 'Extremoz / Litoral Norte, Natal - RN',
                'tempo' => 240,
                'acessibilidade' => [],
                'lat' => -5.7003,
                'lng' => -35.1972,
                'foto' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioNatal,
                'categoria' => $catAgua,
                'nome' => 'Praia de Ponta Negra e Morro do Careca',
                'descricao' => 'A principal praia de Natal com a emblemática duna de 120 metros cercada por vegetação e mar excelente para banho.',
                'historia' => 'O Morro do Careca é área de preservação permanente e símbolo turístico do Rio Grande do Norte.',
                'endereco' => 'Av. Erivan França, Ponta Negra, Natal - RN',
                'tempo' => 180,
                'acessibilidade' => ['cadeirante'],
                'lat' => -5.8856,
                'lng' => -35.1722,
                'foto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ],

            // --- SÃO PAULO (SP) ---
            [
                'municipio' => $municipioSP,
                'categoria' => $catCultura,
                'nome' => 'MASP - Museu de Arte de São Paulo',
                'descricao' => 'Ícone da arquitetura moderna de Lina Bo Bardi com vão livre de 74 metros e o acervo de arte ocidental mais importante da América Latina.',
                'historia' => 'Fundado em 1947 por Assis Chateaubriand na Avenida Paulista.',
                'endereco' => 'Av. Paulista, 1578, Bela Vista, São Paulo - SP',
                'tempo' => 150,
                'acessibilidade' => ['cadeirante', 'cego', 'deficiencia_auditiva'],
                'lat' => -23.5614,
                'lng' => -46.6559,
                'foto' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'municipio' => $municipioSP,
                'categoria' => $catGrutas,
                'nome' => 'Parque Ibirapuera',
                'descricao' => 'O maior e mais visitado parque urbano da metrópole, com lagos, pistas de caminhada, planetário e pavilhões de Oscar Niemeyer.',
                'historia' => 'Inaugurado em 1954 nas comemorações do quarto centenário de São Paulo.',
                'endereco' => 'Av. Pedro Álvares Cabral, Vila Mariana, São Paulo - SP',
                'tempo' => 180,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -23.5874,
                'lng' => -46.6576,
                'foto' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80',
            ]
        ];

        $atrativosCriados = [];
        foreach ($atrativosData as $data) {
            $atrativo = Atrativo::firstOrCreate(
                ['nome' => $data['nome'], 'municipio_id' => $data['municipio']->id],
                [
                    'categoria_id' => $data['categoria']->id,
                    'descricao' => $data['descricao'],
                    'historia' => $data['historia'],
                    'endereco' => $data['endereco'],
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                    'tempo_medio_visita' => $data['tempo'],
                    'status' => 'ativo',
                    'acessibilidade' => $data['acessibilidade'],
                    'validado_por' => $admin ? $admin->id : null,
                    'validado_em' => now(),
                ]
            );
            $atrativosCriados[] = $atrativo;

            // Criar Mídia de Foto Real
            if (!empty($data['foto'])) {
                \App\Models\Midia::firstOrCreate(
                    [
                        'entidade_type' => Atrativo::class,
                        'entidade_id' => $atrativo->id,
                        'tipo' => 'foto'
                    ],
                    [
                        'url' => $data['foto'],
                        'alt_text' => 'Foto de ' . $atrativo->nome,
                        'autor' => 'Acervo Fotográfico Municipal & OpenStreetMap',
                        'licenca' => 'CC-BY-SA',
                    ]
                );
            }
            
            // Generate QR Code
            QrCode::firstOrCreate(
                ['atrativo_id' => $atrativo->id],
                ['hash_code' => Str::random(10)]
            );
        }

        // 5. Eventos
        Evento::firstOrCreate(
            ['nome' => 'Festival de Inverno de Bonito'],
            [
                'descricao' => 'Festival anual com música, teatro, dança e artes visuais em praça pública.',
                'inicio' => now()->addDays(5),
                'fim' => now()->addDays(8),
                'gratuito' => true,
                'status' => 'ativo',
            ]
        );
        Evento::firstOrCreate(
            ['nome' => 'Feira do Produtor Rural'],
            [
                'descricao' => 'Feira tradicional de produtos locais, artesanato e gastronomia típica.',
                'inicio' => now()->addDays(2),
                'fim' => now()->addDays(2)->addHours(4),
                'gratuito' => true,
                'status' => 'ativo',
            ]
        );

        // 6. Roteiro Fixo
        $roteiro = Roteiro::firstOrCreate(
            ['titulo' => 'Bonito Essencial: 1 Dia'],
            [
                'tema' => 'Natureza e Cartões Postais',
                'duracao' => 8, // horas
                'dificuldade' => 'Media',
                'transporte' => 'Carro',
                'orcamento' => 150.00,
                'perfil' => 'Familia',
                'origem' => 'oficial',
            ]
        );

        if (isset($atrativosCriados[1]) && isset($atrativosCriados[0]) && isset($atrativosCriados[3])) {
            RoteiroItem::firstOrCreate(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[1]->id], ['ordem' => 1, 'tempo_estimado' => 90]);
            RoteiroItem::firstOrCreate(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[0]->id], ['ordem' => 2, 'tempo_estimado' => 120]);
            RoteiroItem::firstOrCreate(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[3]->id], ['ordem' => 3, 'tempo_estimado' => 120]);
        }

        // 7. Utilidade Pública
        UtilidadePublica::firstOrCreate(['nome' => 'Polícia Militar'], ['telefone' => '190', 'ordem' => 1]);
        UtilidadePublica::firstOrCreate(['nome' => 'SAMU'], ['telefone' => '192', 'ordem' => 2]);
        UtilidadePublica::firstOrCreate(['nome' => 'Corpo de Bombeiros'], ['telefone' => '193', 'ordem' => 3]);
        UtilidadePublica::firstOrCreate(['nome' => 'Hospital Municipal Darci João Bigaton'], ['telefone' => '(67) 3255-1100', 'ordem' => 4]);
        UtilidadePublica::firstOrCreate(['nome' => 'Centro de Atendimento ao Turista (CAT)'], ['telefone' => '(67) 3255-2160', 'ordem' => 5]);

        // 8. Prestadores / Empreendedores Demo
        $userEmpreendedor = User::where('email', 'empreendedor@demo.com')->first();
        if ($userEmpreendedor) {
            \App\Models\Prestador::firstOrCreate(
                ['user_id' => $userEmpreendedor->id],
                [
                    'tipo' => 'hospedagem',
                    'dados' => [
                        'nome_negocio' => 'Pousada Encanto das Águas',
                        'telefone' => '(83) 98888-7766',
                        'endereco' => 'Av. Beira Mar, 450, Cabo Branco, João Pessoa - PB',
                        'municipio_id' => $municipioJampa->id,
                    ],
                    'documentos' => ['doc' => 'CNPJ 12.345.678/0001-90 | Cadastur 15.001.234/2026'],
                    'status' => 'pendente',
                    'selo_validado' => false,
                    'ultima_atualizacao' => now(),
                ]
            );
        }
    }
}
