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
        // 1. Roles & Users Demo
        $roles = [
            'super_admin', 'prefeito', 'secretario', 'gestor_conteudo', 
            'gestor_cadastros', 'atendente', 'empreendedor', 'turista'
        ];
        
        foreach ($roles as $role) {
            User::factory()->create([
                'name' => 'Demo ' . ucfirst($role),
                'email' => $role . '@demo.com',
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }
        
        $admin = User::where('role', 'gestor_conteudo')->first();

        // 2. Municípios Reais
        $municipioBonito = Municipio::create([
            'nome' => 'Bonito',
            'uf' => 'MS',
            'tema_visual' => 'default',
        ]);

        $municipioJampa = Municipio::create([
            'nome' => 'João Pessoa',
            'uf' => 'PB',
            'tema_visual' => 'praia',
        ]);

        // 3. Categorias Realistas
        $catAventura = Categoria::create(['nome' => 'Aventura & Trilhas', 'slug' => 'aventura', 'icone' => 'bi-bicycle', 'tipo' => 'atrativo']);
        $catAgua = Categoria::create(['nome' => 'Praias, Rios e Piscinas', 'slug' => 'rios', 'icone' => 'bi-water', 'tipo' => 'atrativo']);
        $catGrutas = Categoria::create(['nome' => 'Monumentos & Natureza', 'slug' => 'grutas', 'icone' => 'bi-geo', 'tipo' => 'atrativo']);
        $catGastronomia = Categoria::create(['nome' => 'Gastronomia Regional', 'slug' => 'gastronomia', 'icone' => 'bi-cup-hot', 'tipo' => 'servico']);
        $catHospedagem = Categoria::create(['nome' => 'Hospedagem', 'slug' => 'hospedagem', 'icone' => 'bi-house-heart', 'tipo' => 'servico']);
        $catCultura = Categoria::create(['nome' => 'História & Cultura', 'slug' => 'cultura', 'icone' => 'bi-bank', 'tipo' => 'atrativo']);

        // 4. Atrativos Realistas com Coordenadas GPS
        $atrativosData = [
            // João Pessoa - PB
            [
                'municipio' => $municipioJampa,
                'categoria' => $catAgua,
                'nome' => 'Praia de Tambaú',
                'descricao' => 'Uma das praias urbanas mais famosas de João Pessoa, com águas mornas, calçadão movimentado, feirinha de artesanato e passeios de catamarã.',
                'historia' => 'O coração turístico da capital paraibana, ponto de partida para as piscinas de Picãozinho.',
                'endereco' => 'Av. Almirante Tamandaré, Tambaú, João Pessoa - PB',
                'tempo' => 180,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -7.1147,
                'lng' => -34.8239,
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
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catAgua,
                'nome' => 'Piscinas Naturais dos Seixas',
                'descricao' => 'Aquários naturais formados por corais na maré baixa com águas cristalinas repletas de peixes coloridos, ideal para mergulho livre.',
                'historia' => 'Formação de recifes de corais protegida acessível por embarcações credenciadas.',
                'endereco' => 'Praia dos Seixas, João Pessoa - PB',
                'tempo' => 150,
                'acessibilidade' => ['deficiencia_auditiva'],
                'lat' => -7.1597,
                'lng' => -34.7877,
            ],
            [
                'municipio' => $municipioJampa,
                'categoria' => $catCultura,
                'nome' => 'Centro Cultural São Francisco',
                'descricao' => 'Um dos mais importantes complexos barrocos do Brasil, com igreja, convento, claustro e rico acervo de arte sacra.',
                'historia' => 'Construído a partir de 1589 pela Ordem Franciscana, tombado pelo IPHAN.',
                'endereco' => 'Praça São Francisco, Centro Histórico, João Pessoa - PB',
                'tempo' => 90,
                'acessibilidade' => ['cadeirante'],
                'lat' => -7.1155,
                'lng' => -34.8864,
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
            ],
            // Bonito - MS
            [
                'municipio' => $municipioBonito,
                'categoria' => $catAgua,
                'nome' => 'Flutuação no Rio Sucuri',
                'descricao' => 'Uma das águas mais cristalinas do mundo. Flutuação tranquila em meio a muita vida subaquática e vegetação exuberante.',
                'historia' => 'O Rio Sucuri é famoso por sua nascente e pela visibilidade inacreditável da água, resultado da alta concentração de calcário.',
                'endereco' => 'Fazenda São Geraldo, Rodovia Bonito - São Geraldo',
                'tempo' => 120,
                'acessibilidade' => ['cadeirante', 'deficiencia_auditiva'],
                'lat' => -21.2642,
                'lng' => -56.5516,
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catGrutas,
                'nome' => 'Gruta do Lago Azul',
                'descricao' => 'Cartão postal de Bonito, uma caverna com um lago subterrâneo de coloração azul intensa.',
                'historia' => 'Descoberta em 1924 por índios Terena, a gruta é um monumento natural tombado pelo IPHAN.',
                'endereco' => 'Rodovia MS 382, Km 20',
                'tempo' => 90,
                'acessibilidade' => [],
                'lat' => -21.1469,
                'lng' => -56.5861,
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catAventura,
                'nome' => 'Bóia Cross no Rio Formoso',
                'descricao' => 'Aventura em bóias individuais por corredeiras refrescantes no Rio Formoso.',
                'historia' => 'Atividade tradicional que mistura emoção e contato com a natureza.',
                'endereco' => 'Parque Ecológico Rio Formoso',
                'tempo' => 60,
                'acessibilidade' => ['deficiencia_auditiva'],
                'lat' => -21.1895,
                'lng' => -56.4523,
            ],
            [
                'municipio' => $municipioBonito,
                'categoria' => $catGastronomia,
                'nome' => 'Casa do João',
                'descricao' => 'Um dos restaurantes mais famosos da região, conhecido por seus pratos com peixes locais como Pintado e Pacu.',
                'historia' => 'Fundado pela família de Seu João, virou ponto de encontro obrigatório para os turistas em Bonito.',
                'endereco' => 'Rua Cel. Nélson Felício dos Santos, Centro',
                'tempo' => 120,
                'acessibilidade' => ['cadeirante', 'cego'],
                'lat' => -21.1275,
                'lng' => -56.4831,
            ]
        ];

        $atrativosCriados = [];
        foreach ($atrativosData as $data) {
            $atrativo = Atrativo::create([
                'municipio_id' => $data['municipio']->id,
                'categoria_id' => $data['categoria']->id,
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'historia' => $data['historia'],
                'endereco' => $data['endereco'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'tempo_medio_visita' => $data['tempo'],
                'status' => 'ativo',
                'acessibilidade' => $data['acessibilidade'],
                'validado_por' => $admin->id,
                'validado_em' => now(),
            ]);
            $atrativosCriados[] = $atrativo;
            
            // Generate QR Code
            QrCode::create([
                'atrativo_id' => $atrativo->id,
                'hash_code' => Str::random(10),
            ]);
        }


        // 5. Eventos
        Evento::create([
            'nome' => 'Festival de Inverno de Bonito',
            'descricao' => 'Festival anual com música, teatro, dança e artes visuais em praça pública.',
            'inicio' => now()->addDays(5),
            'fim' => now()->addDays(8),
            'gratuito' => true,
            'status' => 'ativo',
        ]);
        Evento::create([
            'nome' => 'Feira do Produtor Rural',
            'descricao' => 'Feira tradicional de produtos locais, artesanato e gastronomia típica.',
            'inicio' => now()->addDays(2),
            'fim' => now()->addDays(2)->addHours(4),
            'gratuito' => true,
            'status' => 'ativo',
        ]);

        // 6. Roteiro Fixo
        $roteiro = Roteiro::create([
            'titulo' => 'Bonito Essencial: 1 Dia',
            'tema' => 'Natureza e Cartões Postais',
            'duracao' => 8, // horas
            'dificuldade' => 'Media',
            'transporte' => 'Carro',
            'orcamento' => 150.00,
            'perfil' => 'Familia',
            'origem' => 'oficial',
        ]);

        RoteiroItem::create(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[1]->id, 'ordem' => 1, 'tempo_estimado' => 90]);
        RoteiroItem::create(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[0]->id, 'ordem' => 2, 'tempo_estimado' => 120]);
        RoteiroItem::create(['roteiro_id' => $roteiro->id, 'atrativo_id' => $atrativosCriados[3]->id, 'ordem' => 3, 'tempo_estimado' => 120]);

        // 7. Utilidade Pública
        UtilidadePublica::create(['nome' => 'Polícia Militar', 'telefone' => '190', 'ordem' => 1]);
        UtilidadePublica::create(['nome' => 'SAMU', 'telefone' => '192', 'ordem' => 2]);
        UtilidadePublica::create(['nome' => 'Corpo de Bombeiros', 'telefone' => '193', 'ordem' => 3]);
        UtilidadePublica::create(['nome' => 'Hospital Municipal Darci João Bigaton', 'telefone' => '(67) 3255-1100', 'ordem' => 4]);
        UtilidadePublica::create(['nome' => 'Centro de Atendimento ao Turista (CAT)', 'telefone' => '(67) 3255-2160', 'ordem' => 5]);

        // 8. Prestadores / Empreendedores Demo
        $userEmpreendedor = User::where('email', 'empreendedor@demo.com')->first();
        if ($userEmpreendedor) {
            \App\Models\Prestador::create([
                'user_id' => $userEmpreendedor->id,
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
            ]);
        }
    }
}
