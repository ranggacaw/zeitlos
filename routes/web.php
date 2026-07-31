<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicTeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicTeamController::class, 'dashboard'])->name('public.home');
Route::get('/matches/{match}/live', [PublicTeamController::class, 'live'])->name('public.matches.live');
Route::get('/players/{player}', [PublicTeamController::class, 'player'])->name('public.players.show');
Route::get('/schedule', [PublicTeamController::class, 'schedule'])->name('public.schedule');
Route::get('/roster', [PublicTeamController::class, 'roster'])->name('public.roster');
Route::get('/leaderboard', [PublicTeamController::class, 'leaderboard'])->name('public.leaderboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin-legacy/{path?}', fn () => redirect('/admin'))
    ->where('path', '.*');

require __DIR__.'/auth.php';
