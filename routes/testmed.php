<?php

use App\Http\Controllers\Auth\RegisteredTestMedController;
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

});
