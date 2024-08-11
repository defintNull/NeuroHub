<?php

use App\Http\Controllers\Admin\CreateTestMedController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Profile\ProfileController;
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

    Route::get('/info/{id}', [UserController::class, 'show'])->name("users.show");
    Route::delete('/del/{user}', [UserController::class, 'destroy'])->name("users.destroy");
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});
