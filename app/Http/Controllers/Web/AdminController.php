<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AnalyticEvent;
use App\Models\AssistantLog;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\Roteiro;
use App\Models\RoteiroItem;
use App\Models\Alerta;
use App\Models\Prestador;
use App\Models\Municipio;
use App\Models\Categoria;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Principal com KPIs e Mapa de Calor
     */
    public function dashboard()
    {
        $atrativosTotal = Atrativo::count();
        $atrativosAcessiveis = Atrativo::whereNotNull('acessibilidade')
            ->get()
            ->filter(function($a) {
                $acess = $a->acessibilidade;
                if (is_string($acess)) {
                    $acess = json_decode($acess, true);
                }
                return !empty($acess) && is_array($acess) && count($acess) > 0;
            })
            ->count();

        $qrScansTotal = \App\Models\QrCode::sum('scans') ?: 14;

        $kpi = [
            'atrativos_ativos' => Atrativo::where('status', 'ativo')->count(),
            'eventos_ativos' => Evento::where('status', 'ativo')->count(),
            'ia_interacoes' => AssistantLog::count(),
            'analytics_eventos' => AnalyticEvent::count(),
            'parceiros_pendentes' => Prestador::where('status', 'pendente')->count(),
            'parceiros_aprovados' => Prestador::where('status', 'aprovado')->count(),
            'roteiros_cadastrados' => Roteiro::count(),
            'taxa_acessibilidade' => $atrativosTotal > 0 ? round(($atrativosAcessiveis / $atrativosTotal) * 100) : 0,
            'qr_scans_total' => $qrScansTotal,
            'folhas_economizadas' => $qrScansTotal * 5, // ~5 páginas de folheto impresso por leitura digital
        ];

        // Distribuição por Categoria
        $categoriasData = Categoria::withCount('atrativos')->get()->map(function($cat) {
            return [
                'nome' => $cat->nome,
                'total' => $cat->atrativos_count,
            ];
        });

        // Interações com IA nos últimos 7 dias
        $iaLogsPorDia = AssistantLog::selectRaw("DATE(created_at) as data, count(*) as total")
            ->where('created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) asc')
            ->get();

        $ultimosAtrativos = Atrativo::with(['categoria', 'municipio'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $proximosEventos = Evento::where('status', 'ativo')
            ->orderBy('inicio', 'asc')
            ->take(4)
            ->get();

        $alertasRecentes = Alerta::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('kpi', 'ultimosAtrativos', 'proximosEventos', 'alertasRecentes', 'categoriasData', 'iaLogsPorDia'));
    }

    /**
     * Gestão de Atrativos Turísticos
     */
    public function atrativos(Request $request)
    {
        $query = Atrativo::with(['categoria', 'municipio']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nome', 'like', "%{$q}%")
                    ->orWhere('descricao', 'like', "%{$q}%");
            });
        }

        if ($request->filled('cidade')) {
            $cidade = $request->cidade;
            $query->whereHas('municipio', function($m) use ($cidade) {
                $m->where('nome', 'like', "%{$cidade}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $atrativos = $query->orderBy('id', 'desc')->paginate(10);
        $municipios = Municipio::all();
        $categorias = Categoria::all();

        return view('admin.atrativos.index', compact('atrativos', 'municipios', 'categorias'));
    }

    public function storeAtrativo(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'municipio_id' => 'required|exists:municipios,id',
            'endereco' => 'nullable|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'tempo_medio_visita' => 'nullable|integer|min:0',
            'status' => 'required|in:ativo,pendente,inativo',
        ]);

        $validated['capacidade_suportada'] = $request->input('capacidade_suportada', 100);
        $validated['acessibilidade'] = $request->has('acessibilidade') ? ['rampas' => true] : [];

        Atrativo::create($validated);

        return redirect()->route('admin.atrativos.index')->with('status', 'Atrativo turístico cadastrado com sucesso!');
    }

    public function updateAtrativo(Request $request, $id)
    {
        $atrativo = Atrativo::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'municipio_id' => 'required|exists:municipios,id',
            'endereco' => 'nullable|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'tempo_medio_visita' => 'nullable|integer|min:0',
            'status' => 'required|in:ativo,pendente,inativo',
        ]);

        $atrativo->update($validated);

        return redirect()->route('admin.atrativos.index')->with('status', 'Atrativo atualizado com sucesso!');
    }

    public function destroyAtrativo($id)
    {
        $atrativo = Atrativo::findOrFail($id);
        $atrativo->delete();

        return redirect()->route('admin.atrativos.index')->with('status', 'Atrativo removido com sucesso.');
    }

    public function toggleStatusAtrativo($id)
    {
        $atrativo = Atrativo::findOrFail($id);
        $atrativo->status = ($atrativo->status === 'ativo') ? 'inativo' : 'ativo';
        $atrativo->save();

        return redirect()->route('admin.atrativos.index')->with('status', 'Status do atrativo alterado para ' . ucfirst($atrativo->status) . '!');
    }

    /**
     * Gestão de Eventos
     */
    public function eventos()
    {
        $eventos = Evento::orderBy('inicio', 'asc')->paginate(10);
        $municipios = Municipio::all();
        return view('admin.eventos.index', compact('eventos', 'municipios'));
    }

    public function storeEvento(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'local' => 'nullable|string|max:255',
            'inicio' => 'required|date',
            'fim' => 'nullable|date|after_or_equal:inicio',
            'organizador' => 'nullable|string|max:255',
            'gratuito' => 'nullable|boolean',
            'status' => 'required|in:ativo,cancelado,encerrado',
        ]);

        $validated['gratuito'] = $request->has('gratuito');
        $validated['fim'] = $validated['fim'] ?? $validated['inicio'];

        Evento::create($validated);

        return redirect()->route('admin.eventos.index')->with('status', 'Evento cadastrado com sucesso no calendário oficial!');
    }

    public function updateEvento(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'local' => 'nullable|string|max:255',
            'inicio' => 'required|date',
            'fim' => 'nullable|date|after_or_equal:inicio',
            'organizador' => 'nullable|string|max:255',
            'gratuito' => 'nullable|boolean',
            'status' => 'required|in:ativo,cancelado,encerrado',
        ]);

        $validated['gratuito'] = $request->has('gratuito');
        $validated['fim'] = $validated['fim'] ?? $validated['inicio'];

        $evento->update($validated);

        return redirect()->route('admin.eventos.index')->with('status', 'Evento atualizado com sucesso!');
    }

    public function destroyEvento($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return redirect()->route('admin.eventos.index')->with('status', 'Evento excluído com sucesso.');
    }

    /**
     * Gestão de Roteiros
     */
    public function roteiros()
    {
        $roteiros = Roteiro::with('itens.atrativo')->paginate(10);
        $atrativosDisponiveis = Atrativo::where('status', 'ativo')->orderBy('nome')->get();
        return view('admin.roteiros.index', compact('roteiros', 'atrativosDisponiveis'));
    }

    public function storeRoteiro(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tema' => 'required|string|max:100',
            'perfil' => 'nullable|string|max:100',
            'transporte' => 'nullable|string|max:100',
            'duracao' => 'required|numeric|min:0.5',
            'orcamento' => 'nullable|numeric|min:0',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'atrativos' => 'nullable|array',
            'atrativos.*' => 'exists:atrativos,id',
        ]);

        $roteiro = Roteiro::create([
            'titulo' => $validated['titulo'],
            'tema' => $validated['tema'],
            'perfil' => $validated['perfil'] ?? 'Geral',
            'transporte' => $validated['transporte'] ?? 'Carro / Caminhada',
            'duracao' => $validated['duracao'],
            'orcamento' => $validated['orcamento'] ?? 0,
            'dificuldade' => $validated['dificuldade'],
            'publico' => true,
            'origem' => 'oficial',
        ]);

        if (!empty($validated['atrativos'])) {
            $ordem = 1;
            foreach ($validated['atrativos'] as $atrativoId) {
                RoteiroItem::create([
                    'roteiro_id' => $roteiro->id,
                    'atrativo_id' => $atrativoId,
                    'ordem' => $ordem++,
                    'tempo_estimado_min' => 60,
                ]);
            }
        }

        return redirect()->route('admin.roteiros.index')->with('status', 'Roteiro oficial criado com sucesso!');
    }

    public function destroyRoteiro($id)
    {
        $roteiro = Roteiro::findOrFail($id);
        $roteiro->itens()->delete();
        $roteiro->delete();

        return redirect()->route('admin.roteiros.index')->with('status', 'Roteiro removido com sucesso.');
    }

    /**
     * Logs de Auditoria e IA
     */
    public function auditoria()
    {
        $audits = class_exists(\OwenIt\Auditing\Models\Audit::class)
            ? \OwenIt\Auditing\Models\Audit::with('user')->orderBy('created_at', 'desc')->paginate(15)
            : collect();

        $logs = AssistantLog::orderBy('created_at', 'desc')->paginate(15);
        $analytics = AnalyticEvent::orderBy('created_at', 'desc')->take(20)->get();

        return view('admin.auditoria.index', compact('audits', 'logs', 'analytics'));
    }

    /**
     * Dados para Mapa de Calor (Interesse Turístico) com salvaguarda LGPD
     */
    public function heatmapData()
    {
        $atrativos = Atrativo::whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id', 'nome', 'lat', 'lng', 'tempo_medio_visita']);

        $heatmap = [];

        foreach ($atrativos as $atrativo) {
            // Contagem de eventos de visualização / buscas do atrativo
            $eventosCount = AnalyticEvent::where('entidade_id', $atrativo->id)
                ->where('entidade_type', 'atrativo')
                ->count();

            // Ponderação da intensidade de 0.4 a 1.0
            $intensidade = 0.5 + min(0.5, ($eventosCount * 0.1) + (($atrativo->tempo_medio_visita ?? 60) / 360));
            $intensidade = round(min(1.0, max(0.3, $intensidade)), 2);

            $heatmap[] = [
                (float) $atrativo->lat,
                (float) $atrativo->lng,
                $intensidade
            ];
        }

        // Se ainda houver poucos pontos em base de testes vazia, adiciona as capitais mapeadas
        if (count($heatmap) === 0) {
            $heatmap = [
                [-7.1153, -34.8641, 0.9], // João Pessoa
                [-7.1147, -34.8239, 0.95], // Tambaú
                [-7.1477, -34.7963, 0.85], // Cabo Branco
                [-21.1275, -56.4831, 0.88], // Bonito
                [-8.0476, -34.8770, 0.75], // Recife
                [-5.7945, -35.2110, 0.78], // Natal
            ];
        }

        return response()->json($heatmap);
    }

    /**
     * Documentação do Projeto (Dossiê de Entrega / Handover Documentation)
     */
    public function documentacao()
    {
        return view('admin.documentacao');
    }

    /**
     * Swagger UI Interativo da API REST
     */
    public function swagger()
    {
        return view('admin.swagger');
    }

    /**
     * Gestão de Usuários & Matriz de Controle de Acesso (RBAC) - Exclusivo Super Admin
     */
    public function usuarios(Request $request)
    {
        $query = \App\Models\User::query();

        // Busca por nome ou e-mail
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // Filtro por Perfil / Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Contagens para os cards de estatística
        $stats = [
            'total' => \App\Models\User::count(),
            'super_admin' => \App\Models\User::where('role', 'super_admin')->count(),
            'gestores' => \App\Models\User::whereIn('role', ['prefeito', 'secretario', 'gestor_conteudo', 'gestor_cadastros', 'atendente'])->count(),
            'empreendedores' => \App\Models\User::where('role', 'empreendedor')->count(),
            'turistas' => \App\Models\User::where('role', 'turista')->count(),
        ];

        $rolesDisponiveis = [
            'super_admin' => [
                'nome' => 'Super Administrador',
                'badge' => 'bg-primary text-white',
                'descricao' => 'Acesso total a todos os módulos, auditoria, configurações e documentação técnica.',
                'icone' => 'bi-shield-fill-check',
            ],
            'prefeito' => [
                'nome' => 'Prefeito Municipal',
                'badge' => 'bg-info text-dark',
                'descricao' => 'Visão executiva com KPIs, Dashboard, Mapa de Calor, Alertas e Relatórios.',
                'icone' => 'bi-building-gear',
            ],
            'secretario' => [
                'nome' => 'Secretário de Turismo',
                'badge' => 'bg-success text-white',
                'descricao' => 'Gestão de atrativos, eventos, roteiros, validação de parceiros e relatórios.',
                'icone' => 'bi-briefcase-fill',
            ],
            'gestor_conteudo' => [
                'nome' => 'Gestor de Conteúdo',
                'badge' => 'bg-secondary text-white',
                'descricao' => 'CMS de atrativos turísticos, calendário de eventos e roteiros temáticos.',
                'icone' => 'bi-pencil-square',
            ],
            'gestor_cadastros' => [
                'nome' => 'Gestor de Cadastros',
                'badge' => 'bg-warning text-dark',
                'descricao' => 'Fila de análise e homologação de parceiros e estabelecimentos turísticos.',
                'icone' => 'bi-patch-check-fill',
            ],
            'atendente' => [
                'nome' => 'Atendente',
                'badge' => 'bg-light text-dark border',
                'descricao' => 'Visualização de dashboard, atrativos e consulta operacional ao turista.',
                'icone' => 'bi-headset',
            ],
            'empreendedor' => [
                'nome' => 'Empreendedor / Parceiro',
                'badge' => 'bg-warning text-dark border border-warning',
                'descricao' => 'Painel do Parceiro com gestão do próprio estabelecimento e atrativos cadastrados.',
                'icone' => 'bi-shop',
            ],
            'turista' => [
                'nome' => 'Turista (PWA)',
                'badge' => 'bg-dark text-white',
                'descricao' => 'Acesso ao aplicativo do turista, itinerários IA, mapa e privacidade LGPD.',
                'icone' => 'bi-compass',
            ],
        ];

        return view('admin.usuarios.index', compact('usuarios', 'stats', 'rolesDisponiveis'));
    }

    /**
     * Cadastra um novo usuário no sistema
     */
    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string|in:super_admin,prefeito,secretario,gestor_conteudo,gestor_cadastros,atendente,empreendedor,turista',
            'password' => 'required|string|min:6',
        ], [
            'email.unique' => 'Este e-mail já está cadastrado no sistema.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'role.in' => 'Perfil de usuário inválido.',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', "Usuário '{$request->name}' criado com sucesso com o perfil selecionado.");
    }

    /**
     * Atualiza o papel/perfil RBAC de um usuário
     */
    public function updateRoleUsuario(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:super_admin,prefeito,secretario,gestor_conteudo,gestor_cadastros,atendente,empreendedor,turista',
        ]);

        $usuario = \App\Models\User::findOrFail($id);

        // Previne que o super_admin logado desative seu próprio acesso se for o único super admin
        if ($usuario->id === auth()->id() && $request->role !== 'super_admin') {
            $totalSuperAdmins = \App\Models\User::where('role', 'super_admin')->count();
            if ($totalSuperAdmins <= 1) {
                return redirect()->back()->with('error', 'Você não pode rebaixar seu próprio perfil porque você é o único Super Administrador cadastrado no sistema.');
            }
        }

        $antigoRole = $usuario->role;
        $usuario->role = $request->role;
        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('success', "Perfil de '{$usuario->name}' atualizado de '{$antigoRole}' para '{$request->role}' com sucesso.");
    }

    /**
     * Exclui um usuário do sistema
     */
    public function destroyUsuario($id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        if ($usuario->id === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode excluir sua própria conta de Super Administrador em sessão ativa.');
        }

        $nome = $usuario->name;
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', "Usuário '{$nome}' excluído com sucesso.");
    }
}
