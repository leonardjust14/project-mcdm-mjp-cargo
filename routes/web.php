<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/alternatives', [AlternativeController::class, 'index'])->name('alternatives.index');
Route::get('/alternatives/create', [AlternativeController::class, 'create'])->name('alternatives.create');
Route::post('/alternatives', [AlternativeController::class, 'store'])->name('alternatives.store');
Route::get('/alternatives/{alternative}/edit', [AlternativeController::class, 'edit'])->name('alternatives.edit');
Route::put('/alternatives/{alternative}', [AlternativeController::class, 'update'])->name('alternatives.update');
Route::delete('/alternatives/{alternative}', [AlternativeController::class, 'destroy'])->name('alternatives.destroy');

Route::get('/results', [ResultController::class, 'index'])->name('results.index');
