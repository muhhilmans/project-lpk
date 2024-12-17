<?php

use App\Http\Controllers\User\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin|superadmin|owner']], function () {
    Route::get('/dashboard', function () {
        return view('cms.dashboard.dashboard');
    })->name('dashboard');
});

Route::group(['middleware' => ['role:superadmin|owner']], function () {
    Route::resource('users-admin', AdminController::class)->except('create');
});