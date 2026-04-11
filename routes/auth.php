<?php

use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
});

Route::middleware('auth')->post('logout', [AdminLoginController::class, 'logout'])->name('logout');
