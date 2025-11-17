<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentaContableController;
use App\Http\Controllers\AsientoContableController;
use App\Http\Controllers\DashboardController;


Route::middleware(['auth'])->group(function () {
    // Rutas para cliente
    Route::resource('clientes', ClienteController::class);
    // Restaurar cliente
    Route::patch('clientes/{cliente}/restore', [ClienteController::class, 'restore'])->name('clientes.restore');

    // Rutas para cuentas contables
    Route::resource('cuentas_contables', CuentaContableController::class);

    // Rutas para asientos contables
    Route::resource('asiento_contable', AsientoContableController::class);

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
