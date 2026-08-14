<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::with('criador')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.alertas.index', compact('alertas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'corpo' => 'required|string',
            'urgencia' => 'required|string|in:info,aviso,urgente',
            'contato_emergencia' => 'nullable|string|max:255',
            'responsavel' => 'nullable|string|max:255',
            'duracao_horas' => 'nullable|integer|min:1|max:720',
        ]);

        $duracaoHoras = !empty($data['duracao_horas']) ? (int) $data['duracao_horas'] : 24;
        $validoAte = now()->addHours($duracaoHoras);

        Alerta::create([
            'titulo' => $data['titulo'],
            'corpo' => $data['corpo'],
            'urgencia' => $data['urgencia'],
            'contato_emergencia' => $data['contato_emergencia'] ?? 'Defesa Civil 199 / SAMU 192',
            'responsavel' => $data['responsavel'] ?? 'Defesa Civil & Gestão Municipal',
            'duracao_horas' => $duracaoHoras,
            'valido_ate' => $validoAte,
            'status' => 'ativo',
            'criado_por' => auth()->id()
        ]);

        return redirect()->route('admin.alertas.index')->with('success', 'Alerta / Comunicado oficial publicado com sucesso!');
    }

    public function destroy($id)
    {
        $alerta = Alerta::findOrFail($id);
        $alerta->delete();

        return redirect()->route('admin.alertas.index')->with('success', 'Alerta / Comunicado oficial excluído com sucesso.');
    }
}
