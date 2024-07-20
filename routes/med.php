<?php

use App\Http\Controllers\Auth\RegisteredMedController;
use App\Http\Middleware\MedAuth;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', MedAuth::class, RegistrationRedirect::class])->group(function() {

    Route::get('medregister', [RegisteredMedController::class, 'create'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class)
            ->name('medregister');

    Route::post('medregister', [RegisteredMedController::class, 'store'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class);

});
