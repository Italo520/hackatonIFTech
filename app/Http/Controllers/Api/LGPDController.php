<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AnalyticEvent;
use App\Models\Avaliacao;

class LGPDController extends Controller
{
    public function exportData(Request $request)
    {
        $user = $request->user();

        $data = [
            'perfil' => $user->toArray(),
            'avaliacoes' => Avaliacao::where('user_id', $user->id)->get()->toArray(),
            // In a real app we'd fetch other identifiable data
        ];

        return response()->json($data);
    }

    public function deleteData(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Senha incorreta'], 403);
        }

        // Anonimizar ou deletar
        // Para avaliações, apenas anonimizar mantendo para estatística
        Avaliacao::where('user_id', $user->id)->update(['user_id' => null]);

        // Soft delete user
        $user->delete();

        // Revoke tokens
        $user->tokens()->delete();

        return response()->json(['message' => 'Dados excluídos e anonimizados com sucesso']);
    }
}
