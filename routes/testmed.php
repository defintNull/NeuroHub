<?php

use App\Http\Controllers\Auth\RegisteredTestMedController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use App\Http\Middleware\TestMedAuth;
use Illuminate\Support\Facades\Route;

Route::name('testmed.')->prefix('testmed')->middleware(['auth', TestMedAuth::class, RegistrationRedirect::class])->group(function() {

    Route::get('/dashboard', function () {
        return view('testmed.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('register', [RegisteredTestMedController::class, 'create'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class)
            ->name('register');

    Route::post('register', [RegisteredTestMedController::class, 'store'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
