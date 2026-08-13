<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UtilidadePublica;

class UtilidadePublicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $utilidades = UtilidadePublica::where('ativo', true)
            ->orderBy('ordem', 'asc')
            ->get();
            
        return response()->json(['data' => $utilidades]);
    }
}
