<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\VooController;
use App\Http\Controllers\PassageiroController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [ReservaController::class, 'index'])->name('home');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    // Dashboard na raiz para autenticados
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Reservas
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

    // Passageiros
    Route::get('/passageiros/create', [PassageiroController::class, 'create'])->name('passageiros.create');
    Route::post('/passageiros', [PassageiroController::class, 'store'])->name('passageiros.store');
    Route::get('/passageiros/{id}/edit', [PassageiroController::class, 'edit'])->name('passageiros.edit');
    Route::put('/passageiros/{id}', [PassageiroController::class, 'update'])->name('passageiros.update');
    Route::delete('/passageiros/{id}', [PassageiroController::class, 'destroy'])->name('passageiros.destroy');
});
