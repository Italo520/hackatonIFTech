<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestador;
use Illuminate\Http\Request;

class PrestadorValidationController extends Controller
{
    public function index()
    {
        $prestadores = Prestador::with('user')->where('status', 'pendente')->paginate(10);
        return view('admin.prestadores.fila', compact('prestadores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:aprovado,rejeitado,suspenso,complementar']);
        
        $prestador = Prestador::findOrFail($id);
        $prestador->update(['status' => $request->status, 'selo_validado' => $request->status === 'aprovado']);

        return back()->with('success', 'Status atualizado com sucesso.');
    }
}
