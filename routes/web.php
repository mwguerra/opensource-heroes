<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/search', [SearchController::class, 'getMoreUsers'])->name('get-more-users');

    Route::get('/dashboard', [HeroController::class, 'index'])->name('heroes.index');
    Route::post('/heroes/selection', [HeroController::class, 'update'])->name('heroes.selection.update');
    Route::delete('/heroes/{hero}', [HeroController::class, 'destroy'])->name('heroes.destroy');
});
