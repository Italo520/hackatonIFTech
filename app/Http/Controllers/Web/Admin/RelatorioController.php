<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticEvent;
use App\Models\Atrativo;
use App\Models\AssistantLog;
use App\Models\Prestador;

class RelatorioController extends Controller
{
    public function exportCsv()
    {
        $events = AnalyticEvent::orderBy('created_at', 'desc')->take(200)->get();
        $atrativos = Atrativo::with(['categoria', 'municipio'])->get();
        $totalIA = AssistantLog::count();
        $totalPrestadores = Prestador::count();

        $csvFileName = 'relatorio_turismo_executivo_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function() use ($events, $atrativos, $totalIA, $totalPrestadores) {
            $handle = fopen('php://output', 'w');
            
            // BOM UTF-8 para compatibilidade com Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // 1. Cabeçalho Executivo
            fputcsv($handle, ['RELATÓRIO CONSOLIDADO DE GESTÃO TURÍSTICA & CAPTAÇÃO DE RECURSOS']);
            fputcsv($handle, ['Gerado em:', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, ['Interações Totais com Assistente IA:', $totalIA]);
            fputcsv($handle, ['Prestadores Locais Cadastrados:', $totalPrestadores]);
            fputcsv($handle, ['Atrativos Turísticos Monitorados:', $atrativos->count()]);
            fputcsv($handle, []);

            // 2. Tabela de Atrativos
            fputcsv($handle, ['--- CATÁLOGO DE ATRATIVOS & ACESSIBILIDADE ---']);
            fputcsv($handle, ['ID', 'Nome do Atrativo', 'Município', 'Categoria', 'Tempo Médio (min)', 'Status']);
            foreach ($atrativos as $a) {
                fputcsv($handle, [
                    $a->id,
                    $a->nome,
                    ($a->municipio?->nome ?? 'N/A') . ' - ' . ($a->municipio?->uf ?? 'BR'),
                    $a->categoria?->nome ?? 'Geral',
                    $a->tempo_medio_visita ?? 60,
                    $a->status
                ]);
            }
            fputcsv($handle, []);

            // 3. Eventos Analíticos e Telemetria
            fputcsv($handle, ['--- TELEMETRIA E EVENTOS ANALÍTICOS (LGPD ANONIMIZADA) ---']);
            fputcsv($handle, ['ID', 'Tipo de Evento', 'Entidade', 'Entidade ID', 'Data / Hora']);

            foreach ($events as $e) {
                fputcsv($handle, [
                    $e->id,
                    $e->tipo,
                    $e->entidade_type ?? 'Geral',
                    $e->entidade_id ?? '-',
                    $e->created_at ? $e->created_at->toDateTimeString() : now()->toDateTimeString()
                ]);
            }

            fputcsv($handle, []);
            // Disclaimer T-045
            fputcsv($handle, ['DISCLAIMER: Os dados extraídos apoiam o planejamento governamental e não garantem elegibilidade automática a editais de captação de recursos.']);
            fclose($handle);
        }, 200, $headers);
    }
}
