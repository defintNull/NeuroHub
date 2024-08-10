<?php

use App\Http\Controllers\PatientController;
use App\Http\Middleware\MedAuth;
use App\Http\Middleware\RegistrationRedirect;
use Illuminate\Support\Facades\Route;

Route::name('med.')->prefix('med')->middleware(['auth', 'verified', MedAuth::class, RegistrationRedirect::class])->group(function() {
    Route::resource('patients', PatientController::class);
});
