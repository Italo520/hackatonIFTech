<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $alertasDefesaCivil = Alerta::ativos()->orderBy('created_at', 'desc')->take(5)->get();
        return view('pwa.home', compact('alertasDefesaCivil'));
    }
    
    public function mapa()
    {
        $alertasDefesaCivil = Alerta::ativos()->orderBy('created_at', 'desc')->take(5)->get();
        return view('pwa.mapa', compact('alertasDefesaCivil'));
    }
}
