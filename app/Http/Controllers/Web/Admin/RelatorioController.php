<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticEvent;

class RelatorioController extends Controller
{
    public function exportCsv()
    {
        $events = AnalyticEvent::all();
        $csvFileName = 'relatorio_turismo_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function() use ($events) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Tipo', 'Entidade Type', 'Entidade ID', 'Data']);

            foreach ($events as $e) {
                fputcsv($handle, [
                    $e->id,
                    $e->tipo,
                    $e->entidade_type,
                    $e->entidade_id,
                    $e->created_at ? $e->created_at->toDateTimeString() : now()->toDateTimeString()
                ]);
            }

            // Disclaimer T-045
            fputcsv($handle, ['DISCLAIMER: Os dados extraídos não garantem elegibilidade a editais de captação de recursos.']);
            fclose($handle);
        }, 200, $headers);
    }
}
