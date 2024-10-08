<?php

use App\Http\Controllers\Admin\CreateTestMedController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->middleware(['auth', 'auth.session', 'verified', AdminAuth::class])->group(function() {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('createtestmed', [CreateTestMedController::class, 'create'])
                ->name('createtestmed');

    Route::post('createtestmed', [CreateTestMedController::class, 'store'])
                ->name('createtestmed');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::get('/users/{user}/confirm' , [UserController::class, 'confirm'])->name('users.confirm');
});


Route::get('/admingraph', [DashboardController::class, 'getData'])->middleware(['auth', 'auth.session', 'verified', AdminAuth::class])->name('admingraph');
