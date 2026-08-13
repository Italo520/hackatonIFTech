<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function atrativos()
    {
        $atrativos = \App\Models\Atrativo::paginate(10);
        return view('admin.atrativos', compact('atrativos'));
    }
}
