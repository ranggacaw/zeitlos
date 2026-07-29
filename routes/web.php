<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\MatchEventController;
use App\Http\Controllers\Admin\MatchFinalScoreController;
use App\Http\Controllers\Admin\MatchLiveStatusController;
use App\Http\Controllers\Admin\MatchRosterController;
use App\Http\Controllers\Admin\MatchScoringController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicTeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PublicTeamController::class, 'dashboard'])->name('public.home');
Route::get('/players/{player}', [PublicTeamController::class, 'player'])->name('public.players.show');
Route::get('/schedule', [PublicTeamController::class, 'schedule'])->name('public.schedule');
Route::get('/roster', [PublicTeamController::class, 'roster'])->name('public.roster');
Route::get('/leaderboard', [PublicTeamController::class, 'leaderboard'])->name('public.leaderboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin-legacy')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('players', PlayerController::class)->except(['show']);

        Route::resource('matches', MatchController::class)->except(['show']);

        Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::patch('leaderboard/{player}', [LeaderboardController::class, 'update'])->name('leaderboard.update');

        Route::get('matches/{match}/roster', [MatchRosterController::class, 'index'])->name('matches.roster.index');
        Route::post('matches/{match}/roster', [MatchRosterController::class, 'store'])->name('matches.roster.store');
        Route::delete('matches/{match}/roster/{roster}', [MatchRosterController::class, 'destroy'])->name('matches.roster.destroy');

        Route::get('matches/{match}/scoring', [MatchScoringController::class, 'index'])->name('matches.scoring.index');
        Route::post('matches/{match}/live', [MatchLiveStatusController::class, 'store'])->name('matches.live.store');
        Route::post('matches/{match}/scoring/events', [MatchEventController::class, 'store'])->name('matches.scoring.events.store');
        Route::delete('matches/{match}/scoring/events/{event}', [MatchEventController::class, 'destroy'])->name('matches.scoring.events.destroy');
        Route::post('matches/{match}/scoring/final-score', [MatchFinalScoreController::class, 'store'])->name('matches.scoring.final-score.store');
    });

require __DIR__.'/auth.php';
