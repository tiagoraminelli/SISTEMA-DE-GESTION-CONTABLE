<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaContableController;
use App\Http\Controllers\AsientoContableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadoFinancieroController;


Route::middleware(['auth'])->group(function () {
    // Rutas para cliente
    Route::resource('clientes', ClienteController::class);
    // Restaurar cliente
    Route::patch('clientes/{cliente}/restore', [ClienteController::class, 'restore'])->name('clientes.restore');

    // Rutas para cuentas contables
    Route::resource('cuentas_contables', CuentaContableController::class);

    // Rutas para asientos contables
    Route::resource('asiento_contable', AsientoContableController::class);

    // Estado de Situación Financiera
    Route::get('estado_financiero', [App\Http\Controllers\EstadoFinancieroController::class, 'index'])
        ->name('estado_financiero.index');

    // Estado de Resultados
    Route::get('estado_financiero/resultados', [App\Http\Controllers\EstadoFinancieroController::class, 'resultados'])
        ->name('estado_financiero.resultados');

    Route::get('estado_financiero/detalle', [EstadoFinancieroController::class, 'ecuacionDinamica'])
        ->name('estado_financiero.detalle');


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
