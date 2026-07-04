<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/r/{token}', [ShortUrlController::class, 'redirect'])->name('short.redirect');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/links/{shortUrl}/stats', [DashboardController::class, 'linkStats'])
    ->middleware('auth')
    ->where('shortUrl', '[A-Za-z0-9_-]+');

Route::middleware('auth')->group(function () {
    Route::post('/dashboard/links/{id}', [ShortUrlController::class, 'destroy'])->name('dashboard.links.destroy');
    Route::post('/dashboard/links', [ShortUrlController::class, 'store'])
        ->name('links.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
