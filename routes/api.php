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
});
