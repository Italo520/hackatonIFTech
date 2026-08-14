<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ExplorarController;
use App\Http\Controllers\Web\AtrativoWebController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\EmpreendedorController;
use App\Http\Controllers\Web\Admin\AlertaController;
use App\Http\Controllers\Web\Admin\PrestadorValidationController;
use App\Http\Controllers\Web\Admin\RelatorioController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PWA Turista (Público)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('pwa.home');
})->name('pwa.home');

Route::get('/explorar', [ExplorarController::class, 'index'])->name('pwa.explorar');
Route::get('/atrativo/{id}', [AtrativoWebController::class, 'show'])->name('pwa.atrativo');

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

Route::get('/privacidade', function () {
    return view('pwa.privacidade');
})->name('pwa.privacidade');

// QR Code Redirecionamento
Route::get('/qr/{hash}', [QrCodeController::class, 'resolve'])->name('qr.resolve');

/*
|--------------------------------------------------------------------------
| Parceiro / Empreendedor
|--------------------------------------------------------------------------
*/
Route::get('/parceiro/cadastro', [EmpreendedorController::class, 'create'])->name('empreendedor.create');
Route::post('/parceiro/cadastro', [EmpreendedorController::class, 'store'])->name('empreendedor.store');
Route::get('/parceiro/painel', [EmpreendedorController::class, 'dashboard'])->name('empreendedor.dashboard');

/*
|--------------------------------------------------------------------------
| Painel de Gestão & Administração (Bootstrap 5)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.index');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Módulos de Gestão
Route::get('/admin/atrativos', [AdminController::class, 'atrativos'])->name('admin.atrativos.index');
Route::get('/admin/eventos', [AdminController::class, 'eventos'])->name('admin.eventos.index');
Route::get('/admin/roteiros', [AdminController::class, 'roteiros'])->name('admin.roteiros.index');
Route::get('/admin/alertas', [AlertaController::class, 'index'])->name('admin.alertas.index');
Route::post('/admin/alertas', [AlertaController::class, 'store'])->name('admin.alertas.store');
Route::get('/admin/auditoria', [AdminController::class, 'auditoria'])->name('admin.auditoria.index');
Route::get('/admin/prestadores', [PrestadorValidationController::class, 'index'])->name('admin.prestadores.index');
Route::put('/admin/prestadores/{id}', [PrestadorValidationController::class, 'update'])->name('admin.prestadores.update');

// Relatórios & Heatmap
Route::get('/admin/heatmap-data', [AdminController::class, 'heatmapData'])->name('admin.heatmap');
Route::get('/admin/relatorios/exportar', [RelatorioController::class, 'exportCsv'])->name('admin.relatorios.export');

// Empreendedor / Negócios
Route::get('/admin/meus-negocios', [EmpreendedorController::class, 'dashboard'])->name('admin.meus-negocios');

/*
|--------------------------------------------------------------------------
| Perfil do Usuário
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
