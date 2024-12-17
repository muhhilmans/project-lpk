<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\AdminController;

Route::middleware(['web'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate'); 
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-servant', function () {
        return view('cms.dashboard.dashboard-servant');
    })->name('dashboard-servant');

    Route::get('/dashboard-employe', function () {
        return view('cms.dashboard.dashboard-employe');
    })->name('dashboard-employe');

    require __DIR__.'/role/admin.php';

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});