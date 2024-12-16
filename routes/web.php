<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');

Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard-servant', function () {
        return view('cms.dashboard.dashboard-servant');
    })->name('dashboard-servant');

    Route::get('dashboard-employe', function () {
        return view('cms.dashboard.dashboard-employe');
    })->name('dashboard-employe');

    Route::get('dashboard', function () {
        return view('cms.dashboard.dashboard');
    })->name('dashboard');
});