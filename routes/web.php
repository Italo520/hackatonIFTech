<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PWA Turista (Público)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('pwa.home');
})->name('pwa.home');

Route::get('/explorar', function () {
    return view('pwa.explorar');
})->name('pwa.explorar');

Route::get('/atrativo/{id}', function ($id) {
    return view('pwa.atrativo', ['id' => $id]);
})->name('pwa.atrativo');

Route::get('/eventos', function () {
    return view('pwa.eventos');
})->name('pwa.eventos');

Route::get('/mapa', function () {
    return view('pwa.mapa');
})->name('pwa.mapa');

Route::get('/utilidade', function () {
    return view('pwa.utilidade');
})->name('pwa.utilidade');

Route::get('/roteiros', function () {
    return view('pwa.roteiros');
})->name('pwa.roteiros');

Route::get('/roteiro/{id}', function ($id) {
    return view('pwa.roteiro', ['id' => $id]);
})->name('pwa.roteiro');

Route::get('/ia', function () {
    return view('pwa.ia');
})->name('pwa.ia');

use App\Http\Controllers\QrCodeController;

// QR Code Redirecionamento
Route::get('/qr/{hash}', [QrCodeController::class, 'resolve'])->name('qr.resolve');


/*
|--------------------------------------------------------------------------
| Admin & Empreendedor (Privado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gestor (role: gestor_conteudo, gestor_cadastros)
    Route::middleware(['role:gestor_conteudo,gestor_cadastros,super_admin'])->group(function () {
        Route::get('/atrativos', function() { return view('admin.atrativos.index'); })->name('atrativos.index');
        Route::get('/eventos', function() { return view('admin.eventos.index'); })->name('eventos.index');
        Route::get('/roteiros', function() { return view('admin.roteiros.index'); })->name('roteiros.index');
        Route::get('/alertas', function() { return view('admin.alertas.index'); })->name('alertas.index');
        Route::get('/auditoria', function() { return view('admin.auditoria.index'); })->name('auditoria.index');
    });

    // Empreendedor (role: empreendedor)
    Route::middleware(['role:empreendedor,super_admin'])->group(function () {
        Route::get('/meus-negocios', function() { return view('empreendedor.index'); })->name('empreendedor.index');
    });
});

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\EmpreendedorController;
use App\Http\Controllers\Web\Admin\AlertaController;
use App\Http\Controllers\Web\Admin\PrestadorValidationController;
use App\Http\Controllers\Web\Admin\RelatorioController;

// Parceiro / Empreendedor
Route::get('/parceiro/cadastro', [EmpreendedorController::class, 'create'])->name('empreendedor.create');
Route::post('/parceiro/cadastro', [EmpreendedorController::class, 'store'])->name('empreendedor.store');
Route::get('/parceiro/painel', [EmpreendedorController::class, 'dashboard'])->name('empreendedor.dashboard');

// Admin Public / Action Endpoints
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.index');
Route::get('/admin/heatmap-data', [AdminController::class, 'heatmapData'])->name('admin.heatmap');
Route::get('/admin/relatorios/exportar', [RelatorioController::class, 'exportCsv'])->name('admin.relatorios.export');
Route::post('/admin/alertas', [AlertaController::class, 'store'])->name('admin.alertas.store');
Route::put('/admin/prestadores/{id}', [PrestadorValidationController::class, 'update'])->name('admin.prestadores.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
