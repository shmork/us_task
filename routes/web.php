<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
use Inertia\Inertia;

//Route::get('/', function () {
//
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', [LinkController::class, 'index'])->name('links.index');
    Route::post('/shorten', [LinkController::class, 'store'])->name('links.store');

    Route::delete('/delete/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::get('/links/clicks', [LinkController::class, 'getClicks'])->name('links.getClicks');
});

Route::get('/link/{code}', [LinkController::class, 'redirect'])->name('links.redirect');
