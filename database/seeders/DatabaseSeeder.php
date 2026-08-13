<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\UtilidadePublica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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

        // 2. Municipio (Single instance for the destination)
        $municipio = Municipio::create([
            'nome' => 'Cidade Destino Inteligente',
            'uf' => 'TS',
            'tema_visual' => 'default',
        ]);

        // 3. Categories
        $catPraia = Categoria::create(['nome' => 'Praias', 'slug' => 'praias', 'icone' => 'beach', 'tipo' => 'atrativo']);
        $catHistoria = Categoria::create(['nome' => 'Histórico', 'slug' => 'historico', 'icone' => 'museum', 'tipo' => 'atrativo']);
        $catNatureza = Categoria::create(['nome' => 'Natureza', 'slug' => 'natureza', 'icone' => 'tree', 'tipo' => 'atrativo']);

        // 4. 30 Atrativos
        for ($i = 1; $i <= 30; $i++) {
            $cat = collect([$catPraia, $catHistoria, $catNatureza])->random();
            Atrativo::create([
                'municipio_id' => $municipio->id,
                'categoria_id' => $cat->id,
                'nome' => 'Atrativo Turístico ' . $i,
                'descricao' => 'Descrição detalhada do atrativo ' . $i . '. É um lugar incrível e imperdível para visitar.',
                'endereco' => 'Rua do Atrativo, ' . $i,
                'tempo_medio_visita' => rand(30, 180),
                'status' => 'ativo',
                'acessibilidade' => rand(0, 1) ? ['cadeirante', 'cego'] : [],
                'validado_por' => $admin->id,
                'validado_em' => now(),
            ]);
        }

        // 5. 10 Eventos
        for ($i = 1; $i <= 10; $i++) {
            Evento::create([
                'nome' => 'Evento Cultural ' . $i,
                'descricao' => 'Grande evento acontecendo na cidade.',
                'inicio' => now()->addDays(rand(1, 30)),
                'fim' => now()->addDays(rand(1, 30))->addHours(4),
                'gratuito' => (bool)rand(0, 1),
                'status' => rand(0, 10) > 1 ? 'ativo' : 'cancelado',
            ]);
        }

        // 6. Utilidade Pública
        UtilidadePublica::create(['nome' => 'Polícia Militar', 'telefone' => '190', 'ordem' => 1]);
        UtilidadePublica::create(['nome' => 'SAMU', 'telefone' => '192', 'ordem' => 2]);
        UtilidadePublica::create(['nome' => 'Corpo de Bombeiros', 'telefone' => '193', 'ordem' => 3]);
        UtilidadePublica::create(['nome' => 'Defesa Civil', 'telefone' => '199', 'ordem' => 4]);
        UtilidadePublica::create(['nome' => 'Centro de Atendimento ao Turista (CAT)', 'telefone' => '0800-123456', 'ordem' => 5]);
    }
}
