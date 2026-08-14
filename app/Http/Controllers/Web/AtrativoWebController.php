<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Atrativo;

class AtrativoWebController extends Controller
{
    /**
     * Exibe a página de detalhe de um atrativo turístico.
     *
     * Carrega o atrativo com suas relações (categoria, municipio, midias)
     * para evitar N+1 queries. Retorna 404 se não encontrado ou inativo.
     */
    public function show(string $id)
    {
        $atrativo = Atrativo::with(['categoria', 'municipio', 'midias'])
            ->where('status', 'ativo')
            ->findOrFail($id);

        // Imagem principal: primeira mídia do tipo "foto", ou null (o Blade usa fallback)
        $imagemPrincipal = $atrativo->midias
            ->where('tipo', 'foto')
            ->first()
            ?->url;

        // Média de avaliações se a relação existir e houver registros
        $mediaAvaliacao = $atrativo->avaliacoes()->exists()
            ? round($atrativo->avaliacoes()->avg('nota'), 1)
            : null;

        $totalAvaliacoes = $mediaAvaliacao ? $atrativo->avaliacoes()->count() : 0;

        return view('pwa.atrativo', compact(
            'atrativo',
            'imagemPrincipal',
            'mediaAvaliacao',
            'totalAvaliacoes'
        ));
    }
}
