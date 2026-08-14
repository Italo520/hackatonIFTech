<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestador;
use App\Models\Atrativo;
use App\Models\Evento;
use App\Models\Categoria;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmpreendedorController extends Controller
{
    public function create()
    {
        $municipios = Municipio::all();
        return view('empreendedor.cadastro', compact('municipios'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tipo' => 'required|string',
            'nome_negocio' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:50',
            'endereco' => 'nullable|string|max:255',
            'municipio_id' => 'nullable|exists:municipios,id',
        ];

        if (!Auth::check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class];
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        if (!Auth::check()) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'empreendedor',
            ]);
            Auth::login($user);
        } else {
            $user = Auth::user();
            if ($user->role !== 'super_admin' && !str_starts_with($user->role, 'gestor')) {
                $user->update(['role' => 'empreendedor']);
            }
        }

        $prestador = Prestador::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tipo' => $validated['tipo'],
                'dados' => [
                    'nome_negocio' => $validated['nome_negocio'],
                    'telefone' => $validated['telefone'] ?? '',
                    'endereco' => $validated['endereco'] ?? '',
                    'municipio_id' => $validated['municipio_id'] ?? 1,
                ],
                'documentos' => ['doc' => $validated['documento']],
                'status' => 'pendente',
                'selo_validado' => false,
                'ultima_atualizacao' => now(),
            ]
        );

        return redirect()->route('empreendedor.dashboard')->with('success', 'Cadastro enviado com sucesso! Seus dados estão em análise pela Secretaria de Turismo.');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('status', 'Faça login para acessar o painel do parceiro.');
        }

        $user = Auth::user();
        $prestador = Prestador::where('user_id', $user->id)->first();
        
        $categorias = Categoria::all();
        $municipios = Municipio::all();

        return view('empreendedor.dashboard', compact('prestador', 'categorias', 'municipios'));
    }

    public function storeAtrativo(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
            'endereco' => 'nullable|string|max:255',
            'tempo_medio_visita' => 'nullable|integer',
        ]);

        $prestador = Prestador::where('user_id', Auth::id())->first();
        $municipioId = $prestador?->dados['municipio_id'] ?? 1;

        Atrativo::create([
            'municipio_id' => $municipioId,
            'categoria_id' => $validated['categoria_id'],
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'endereco' => $validated['endereco'] ?? '',
            'tempo_medio_visita' => $validated['tempo_medio_visita'] ?? 60,
            'status' => 'rascunho',
        ]);

        return redirect()->route('empreendedor.dashboard')->with('success', 'Atrativo/Serviço enviado com sucesso em estado de rascunho/análise!');
    }
}
