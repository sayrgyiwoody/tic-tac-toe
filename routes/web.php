<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::redirect('/dashboard', '/games')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('games', GameController::class)
    ->only(['index', 'show', 'update']);

    Route::post('games', [GameController::class, 'store'])
    ->middleware('throttle:5,1')->name('games.store'); // Allow 5 requests per minute

    Route::post('/games/{game}/join', [GameController::class, 'join'])->name('games.join');

    Route::post('/games/{game}/reset', [GameController::class, 'reset'])->name('games.reset');
});

require __DIR__.'/auth.php';
