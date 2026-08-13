<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Http\Requests\IndexEventoRequest;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexEventoRequest $request)
    {
        $query = Evento::query();

        // Status is always required according to business rules (default: ativo)
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        // T-013: Filtro por data
        if ($request->filled('data_inicio')) {
            $query->where('inicio', '>=', $request->query('data_inicio'));
        }
        if ($request->filled('data_fim')) {
            $query->where('fim', '<=', $request->query('data_fim'));
        }

        // T-013: Gratuito
        if ($request->filled('gratuito')) {
            $query->where('gratuito', $request->boolean('gratuito'));
        }

        $eventos = $query->orderBy('inicio', 'asc')->paginate(15);
        return response()->json($eventos);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evento = Evento::findOrFail($id);
        return response()->json($evento);
    }
}
