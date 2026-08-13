<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EmpreendedorController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\Admin\PrestadorValidationController;
use App\Http\Controllers\Web\Admin\AlertaController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mapa', [HomeController::class, 'mapa'])->name('mapa');

Route::get('/login', function () {
    return "Login mockup: user needs to be authenticated. In a real app this is the Breeze/Fortify login.";
})->name('login');

Route::middleware(['auth', 'role:empreendedor'])->prefix('parceiro')->group(function () {
    Route::get('/cadastro', [EmpreendedorController::class, 'create'])->name('empreendedor.create');
    Route::post('/cadastro', [EmpreendedorController::class, 'store'])->name('empreendedor.store');
    Route::get('/painel', [EmpreendedorController::class, 'dashboard'])->name('empreendedor.dashboard');
});

Route::middleware(['auth', 'role:gestor_cadastros,gestor_conteudo,secretario,prefeito'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/atrativos', [AdminController::class, 'atrativos'])->name('admin.atrativos');

    Route::get('/prestadores/fila', [PrestadorValidationController::class, 'index'])->name('admin.prestadores.fila');
    Route::put('/prestadores/{id}', [PrestadorValidationController::class, 'update'])->name('admin.prestadores.update');

    Route::get('/alertas', [AlertaController::class, 'index'])->name('admin.alertas.index');
    Route::post('/alertas', [AlertaController::class, 'store'])->name('admin.alertas.store');
});
