<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
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
Route::get('/', [HomeController::class, 'index'])->name('pwa.home');

Route::get('/explorar', [ExplorarController::class, 'index'])->name('pwa.explorar');
Route::get('/atrativo/{id}', [AtrativoWebController::class, 'show'])->name('pwa.atrativo');

Route::get('/eventos', function () {
    return view('pwa.eventos');
})->name('pwa.eventos');

Route::get('/mapa', [HomeController::class, 'mapa'])->name('pwa.mapa');

Route::get('/utilidade', function () {
    return view('pwa.utilidade');
})->name('pwa.utilidade');

Route::get('/roteiros', [\App\Http\Controllers\Web\RoteiroWebController::class, 'index'])->name('pwa.roteiros');
Route::get('/roteiro/{id}', [\App\Http\Controllers\Web\RoteiroWebController::class, 'show'])->name('pwa.roteiro');

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

Route::middleware(['auth', 'role:empreendedor,super_admin'])->group(function () {
    Route::get('/parceiro/painel', [EmpreendedorController::class, 'dashboard'])->name('empreendedor.dashboard');
    Route::post('/parceiro/atrativo', [EmpreendedorController::class, 'storeAtrativo'])->name('empreendedor.atrativo.store');
    Route::get('/admin/meus-negocios', [EmpreendedorController::class, 'dashboard'])->name('admin.meus-negocios');
});

/*
|--------------------------------------------------------------------------
| Painel de Gestão & Administração (Bootstrap 5)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin,prefeito,secretario,gestor_conteudo,gestor_cadastros,atendente'])->group(function () {
    // 1. Dashboard & Mapa de Calor (Super Admin, Prefeito, Secretário, Gestor Conteúdo, Gestor Cadastros, Atendente)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/heatmap-data', [AdminController::class, 'heatmapData'])->name('admin.heatmap');

    // 2. Gestão Turística: Atrativos, Eventos, Roteiros (Super Admin, Secretário, Gestor de Conteúdo)
    Route::middleware('role:super_admin,secretario,gestor_conteudo')->group(function () {
        // Atrativos
        Route::get('/admin/atrativos', [AdminController::class, 'atrativos'])->name('admin.atrativos.index');
        Route::post('/admin/atrativos', [AdminController::class, 'storeAtrativo'])->name('admin.atrativos.store');
        Route::put('/admin/atrativos/{id}', [AdminController::class, 'updateAtrativo'])->name('admin.atrativos.update');
        Route::delete('/admin/atrativos/{id}', [AdminController::class, 'destroyAtrativo'])->name('admin.atrativos.destroy');
        Route::patch('/admin/atrativos/{id}/status', [AdminController::class, 'toggleStatusAtrativo'])->name('admin.atrativos.toggle-status');

        // Eventos
        Route::get('/admin/eventos', [AdminController::class, 'eventos'])->name('admin.eventos.index');
        Route::post('/admin/eventos', [AdminController::class, 'storeEvento'])->name('admin.eventos.store');
        Route::put('/admin/eventos/{id}', [AdminController::class, 'updateEvento'])->name('admin.eventos.update');
        Route::delete('/admin/eventos/{id}', [AdminController::class, 'destroyEvento'])->name('admin.eventos.destroy');

        // Roteiros
        Route::get('/admin/roteiros', [AdminController::class, 'roteiros'])->name('admin.roteiros.index');
        Route::post('/admin/roteiros', [AdminController::class, 'storeRoteiro'])->name('admin.roteiros.store');
        Route::delete('/admin/roteiros/{id}', [AdminController::class, 'destroyRoteiro'])->name('admin.roteiros.destroy');
    });

    // 3. Validação de Parceiros / Prestadores (Super Admin, Secretário, Gestor de Cadastros)
    Route::middleware('role:super_admin,secretario,gestor_cadastros')->group(function () {
        Route::get('/admin/prestadores', [PrestadorValidationController::class, 'index'])->name('admin.prestadores.index');
        Route::put('/admin/prestadores/{id}', [PrestadorValidationController::class, 'update'])->name('admin.prestadores.update');
    });

    // 4. Alertas & Defesa Civil (Super Admin, Prefeito, Secretário)
    Route::middleware('role:super_admin,prefeito,secretario')->group(function () {
        Route::get('/admin/alertas', [AlertaController::class, 'index'])->name('admin.alertas.index');
        Route::post('/admin/alertas', [AlertaController::class, 'store'])->name('admin.alertas.store');
        Route::delete('/admin/alertas/{id}', [AlertaController::class, 'destroy'])->name('admin.alertas.destroy');
    });

    // 5. Relatórios Executivos em CSV (Super Admin, Prefeito, Secretário)
    Route::middleware('role:super_admin,prefeito,secretario')->group(function () {
        Route::get('/admin/relatorios/exportar', [RelatorioController::class, 'exportCsv'])->name('admin.relatorios.export');
    });

    // 6. Auditoria & Logs do Sistema (Super Admin)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/auditoria', [AdminController::class, 'auditoria'])->name('admin.auditoria.index');
    });

    // 7. Gestão de Usuários & Matriz RBAC (Super Admin Exclusivo)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios.index');
        Route::post('/admin/usuarios', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');
        Route::put('/admin/usuarios/{id}/role', [AdminController::class, 'updateRoleUsuario'])->name('admin.usuarios.update-role');
        Route::delete('/admin/usuarios/{id}', [AdminController::class, 'destroyUsuario'])->name('admin.usuarios.destroy');
    });

    // 8. Documentação do Projeto & Swagger (Super Admin)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/documentacao', [AdminController::class, 'documentacao'])->name('admin.documentacao');
        Route::get('/admin/swagger', [AdminController::class, 'swagger'])->name('admin.swagger');
    });
});

// Rotas públicas para Swagger UI / Documentação OpenAPI
Route::get('/docs/swagger', [AdminController::class, 'swagger'])->name('docs.swagger');
Route::get('/api/documentation', [AdminController::class, 'swagger'])->name('api.documentation');

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
