<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestador;

class EmpreendedorController extends Controller
{
    public function create()
    {
        return view('empreendedor.cadastro');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string',
            'nome_negocio' => 'required|string',
            'documento' => 'required|string', // Could be PDF in real life, string for MVP mock
        ]);

        Prestador::create([
            'user_id' => 1, // Mock authenticated user for demo
            'tipo' => $data['tipo'],
            'dados' => ['nome_negocio' => $data['nome_negocio']],
            'documentos' => ['doc' => $data['documento']],
            'status' => 'pendente'
        ]);

        return redirect()->route('empreendedor.dashboard')->with('success', 'Cadastro enviado e pendente de validação.');
    }

    public function dashboard()
    {
        // Mock user id 1
        $prestador = Prestador::where('user_id', auth()->id())->first();
        return view('empreendedor.dashboard', compact('prestador'));
    }
}
