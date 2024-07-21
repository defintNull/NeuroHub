<?php

use App\Http\Controllers\Auth\RegisteredMedController;
use App\Http\Middleware\MedAuth;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use Illuminate\Support\Facades\Route;

Route::name('med.')->prefix('med')->middleware(['auth', MedAuth::class, RegistrationRedirect::class])->group(function() {

    Route::get('/dashboard', function () {
        return view('med.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('register', [RegisteredMedController::class, 'create'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class)
            ->name('register');

    Route::post('register', [RegisteredMedController::class, 'store'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class);

});
