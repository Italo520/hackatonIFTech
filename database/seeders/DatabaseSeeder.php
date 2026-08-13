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

        // 2. Municipio Real: Bonito-MS
        $municipio = Municipio::create([
            'nome' => 'Bonito',
            'uf' => 'MS',
            'tema_visual' => 'default',
        ]);

        // 3. Categorias Realistas
        $catAventura = Categoria::create(['nome' => 'Aventura', 'slug' => 'aventura', 'icone' => 'bi-bicycle', 'tipo' => 'atrativo']);
        $catAgua = Categoria::create(['nome' => 'Rios e Nascentes', 'slug' => 'rios', 'icone' => 'bi-water', 'tipo' => 'atrativo']);
        $catGrutas = Categoria::create(['nome' => 'Grutas', 'slug' => 'grutas', 'icone' => 'bi-geo', 'tipo' => 'atrativo']);
        $catGastronomia = Categoria::create(['nome' => 'Gastronomia', 'slug' => 'gastronomia', 'icone' => 'bi-cup-hot', 'tipo' => 'servico']);
        $catHospedagem = Categoria::create(['nome' => 'Hospedagem', 'slug' => 'hospedagem', 'icone' => 'bi-house-heart', 'tipo' => 'servico']);

        // 4. Atrativos Realistas
        $atrativosData = [
            [
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
                'municipio_id' => $municipio->id,
                'categoria_id' => $data['categoria']->id,
                'nome' => $data['nome'],
                'descricao' => $data['descricao'],
                'historia' => $data['historia'],
                'endereco' => $data['endereco'],
                'tempo_medio_visita' => $data['tempo'],
                'status' => 'ativo',
                'acessibilidade' => $data['acessibilidade'],
                'validado_por' => $admin->id,
                'validado_em' => now(),
                // Geo is simulated for Leaflet map logic in frontend as metadata json or simple columns
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
    }
}
