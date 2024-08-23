<?php

use App\Http\Controllers\Auth\RegisteredMedController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\RegistryMedController;
use App\Http\Controllers\Med\PatientController;
use App\Http\Controllers\PatientController as ControllersPatientController;
use App\Http\Controllers\PatientMedicalrecordController;
use App\Http\Middleware\MedAuth;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use Illuminate\Support\Facades\Route;

Route::name('med.')->prefix('med')->middleware(['auth', 'verified', MedAuth::class, RegistrationRedirect::class])->group(function () {

    Route::get('/', function () {
        return view('med.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('med.dashboard');
    })->name('dashboard');

    Route::get('register', [RegisteredMedController::class, 'create'])
        ->middleware(RegistrationStatus::class)
        ->withoutMiddleware(RegistrationRedirect::class)
        ->name('register');

    Route::post('register', [RegisteredMedController::class, 'store'])
        ->middleware(RegistrationStatus::class)
        ->withoutMiddleware(RegistrationRedirect::class);

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('registry', [RegistryMedController::class, 'edit'])
        ->name('registry.edit');

    Route::patch('registry', [RegistryMedController::class, 'update'])
        ->name('registry.update');

    Route::resource('patients', PatientController::class);
    Route::resource('patients.medicalrecords', PatientMedicalrecordController::class);
});
