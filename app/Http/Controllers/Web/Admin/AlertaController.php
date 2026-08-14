<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.alertas.index', compact('alertas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'corpo' => 'required|string',
            'urgencia' => 'nullable|string',
        ]);

        Alerta::create([
            'titulo' => $data['titulo'],
            'corpo' => $data['corpo'],
            'urgencia' => $data['urgencia'] ?? 'info',
            'criado_por' => auth()->id()
        ]);

        return redirect()->to('/admin/alertas')->with('success', 'Alerta emergencial publicado.');
    }
}
