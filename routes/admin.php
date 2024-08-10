<?php

use App\Http\Controllers\Admin\CreateTestMedController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->middleware(['auth', 'verified', AdminAuth::class])->group(function() {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('createtestmed', [CreateTestMedController::class, 'create'])
                ->name('createtestmed');

    Route::post('createtestmed', [CreateTestMedController::class, 'store'])
                ->name('createtestmed');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/info/{id}', [AdminController::class, 'show'])->name("info");
    Route::delete('/del/{user}', [AdminController::class, 'del'])->name("del");
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});
