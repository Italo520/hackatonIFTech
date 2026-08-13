<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AnalyticEvent;
use App\Models\AssistantLog;
use App\Models\Atrativo;
use App\Models\Evento;

class AdminController extends Controller
{
    public function dashboard()
    {
        $kpi = [
            'atrativos_ativos' => Atrativo::where('status', 'ativo')->count(),
            'eventos_ativos' => Evento::where('status', 'ativo')->count(),
            'ia_interacoes' => AssistantLog::count(),
            'analytics_eventos' => AnalyticEvent::count(),
        ];

        return view('admin.dashboard', compact('kpi'));
    }

    public function atrativos()
    {
        $atrativos = Atrativo::paginate(10);
        return view('admin.atrativos', compact('atrativos'));
    }

    public function heatmapData()
    {
        // Suppress cells with < 5 individuals for LGPD (mock grouping logic)
        $events = AnalyticEvent::whereNotNull('geo')
                               ->select('geo') // In real life, cluster by grid
                               ->get();
        // Return dummy data for frontend Leaflet Heat plugin
        $heatmap = [
            [-14.235, -51.925, 0.5],
            [-14.240, -51.920, 0.8],
        ];

        return response()->json($heatmap);
    }
}
