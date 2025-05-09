<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;

Route::get('/', [ReservaController::class, 'index'])->name('home');


// Rotas para edição e exclusão
Route::get('/reserva/{id}/edit', [ReservaController::class, 'edit'])->name('reserva.edit');
Route::get('/reserva/{id}/delete', [ReservaController::class, 'delete'])->name('reserva.delete');



