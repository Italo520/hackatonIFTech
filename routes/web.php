<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mapa', [HomeController::class, 'mapa'])->name('mapa');
Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/atrativos', [App\Http\Controllers\Web\AdminController::class, 'atrativos'])->name('admin.atrativos');
});
