<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\AtrativoController;

Route::prefix('v1')->group(function () {
    Route::apiResource('atrativos', AtrativoController::class)->only(['index', 'show']);
});
Route::prefix('v1')->group(function () {
    Route::apiResource('eventos', App\Http\Controllers\Api\EventoController::class)->only(['index', 'show']);
});
Route::prefix('v1')->group(function () {
    Route::get('utilidades-publicas', [App\Http\Controllers\Api\UtilidadePublicaController::class, 'index']);
});
Route::prefix('v1')->group(function () {
    Route::apiResource('roteiros', App\Http\Controllers\Api\RoteiroController::class)->only(['index', 'show']);
});
Route::prefix('v1/ia')->group(function () {
    Route::post('chat', [App\Http\Controllers\Api\IAController::class, 'chat']);
    Route::post('roteiro', [App\Http\Controllers\Api\IAController::class, 'gerarRoteiro']);
});
Route::prefix('v1')->group(function () {
    Route::get('roteiros/{id}/export', [App\Http\Controllers\Api\RoteiroController::class, 'export']);
    Route::post('sync/avaliacoes', [App\Http\Controllers\Api\SyncController::class, 'syncAvaliacoes']);
    Route::get('qr/{hash}', [App\Http\Controllers\Api\QrCodeController::class, 'scan']);
});
Route::prefix('v1')->group(function () {
    Route::post('ocorrencias', [App\Http\Controllers\Api\OcorrenciaController::class, 'store']);
});
Route::prefix('v1')->group(function () {
    Route::post('analytics', [App\Http\Controllers\Api\AnalyticsController::class, 'store']);
    Route::post('lgpd/consentimentos', [App\Http\Controllers\Api\LGPDController::class, 'salvarConsentimentos']);
    Route::post('lgpd/exportar', [App\Http\Controllers\Api\LGPDController::class, 'exportData'])->middleware('auth:sanctum');
    Route::post('lgpd/excluir', [App\Http\Controllers\Api\LGPDController::class, 'deleteData'])->middleware('auth:sanctum');
});
Route::prefix('v1/location')->group(function () {
    Route::get('/reverse', [App\Http\Controllers\Api\LocationController::class, 'reverse']);
    Route::get('/search', [App\Http\Controllers\Api\LocationController::class, 'search']);
});

Route::prefix('v1/routes')->group(function () {
    Route::get('/directions', [App\Http\Controllers\Api\RoutingApiController::class, 'directions']);
});

Route::prefix('v1/system')->group(function () {
    Route::get('setup', function (Request $request) {
        if ($request->get('key') !== config('app.key')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        try {
            config(['audit.console' => false]);
            \App\Models\User::unsetEventDispatcher();
            
            $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
                '--force' => true,
                '--seed' => true,
            ]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            
            return response()->json([
                'success' => $exitCode === 0,
                'exit_code' => $exitCode,
                'output' => $output,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 200);
        }
    });
});

