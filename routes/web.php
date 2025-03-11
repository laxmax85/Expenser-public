<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DefaultController;
use App\Http\Middleware\EssentialCookie;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/dashboard', [DefaultController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth', 'verified', EssentialCookie::class)->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
});

Route::get('/about', [DefaultController::class, 'about']);

require __DIR__ . '/auth.php';
