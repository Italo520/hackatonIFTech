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
