<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnalyticEvent;

class AnalyticsController extends Controller
{
    /**
     * Store a newly created analytic event in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'entidade_id' => 'nullable|integer',
            'entidade_type' => 'nullable|string',
            'metadados' => 'nullable|array',
        ]);

        AnalyticEvent::create([
            'tipo' => $data['tipo'],
            'entidade_id' => $data['entidade_id'] ?? null,
            'entidade_type' => $data['entidade_type'] ?? null,
            'metadados' => $data['metadados'] ?? null,
        ]);

        return response()->json(['message' => 'Evento registrado'], 201);
    }
}
