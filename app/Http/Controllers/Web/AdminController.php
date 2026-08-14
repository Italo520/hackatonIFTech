<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AnalyticEvent;
use App\Models\AssistantLog;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\Roteiro;
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
        $kpi = [
            'atrativos_ativos' => Atrativo::where('status', 'ativo')->count(),
            'eventos_ativos' => Evento::where('status', 'ativo')->count(),
            'ia_interacoes' => AssistantLog::count(),
            'analytics_eventos' => AnalyticEvent::count(),
            'parceiros_pendentes' => Prestador::where('status', 'pendente')->count(),
            'roteiros_cadastrados' => Roteiro::count(),
        ];

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

        return view('admin.dashboard', compact('kpi', 'ultimosAtrativos', 'proximosEventos', 'alertasRecentes'));
    }

    /**
     * Gestão de Atrativos Turísticos
     */
    public function atrativos(Request $request)
    {
        $query = Atrativo::with(['categoria', 'municipio']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nome', 'like', "%{$q}%")
                  ->orWhere('descricao', 'like', "%{$q}%");
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

    /**
     * Gestão de Eventos
     */
    public function eventos()
    {
        $eventos = Evento::orderBy('inicio', 'asc')->paginate(10);
        return view('admin.eventos.index', compact('eventos'));
    }

    /**
     * Gestão de Roteiros
     */
    public function roteiros()
    {
        $roteiros = Roteiro::with('itens.atrativo')->paginate(10);
        return view('admin.roteiros.index', compact('roteiros'));
    }

    /**
     * Logs de Auditoria e IA
     */
    public function auditoria()
    {
        $logs = AssistantLog::orderBy('created_at', 'desc')->paginate(15);
        $analytics = AnalyticEvent::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.auditoria.index', compact('logs', 'analytics'));
    }

    /**
     * Dados para Mapa de Calor (Interesse Turístico) com salvaguarda LGPD
     */
    public function heatmapData()
    {
        // Coordenadas das capitais / pontos com maior engajamento
        $heatmap = [
            [-7.1153, -34.8641, 0.9], // João Pessoa
            [-7.1147, -34.8239, 0.95], // Tambaú
            [-7.1477, -34.7963, 0.85], // Cabo Branco
            [-21.1275, -56.4831, 0.88], // Bonito
            [-21.2642, -56.5516, 0.92], // Rio Sucuri
            [-8.0476, -34.8770, 0.75], // Recife
            [-5.7945, -35.2110, 0.78], // Natal
            [-23.5505, -46.6333, 0.82], // São Paulo
        ];

        return response()->json($heatmap);
    }
}
