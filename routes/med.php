<?php

use App\Http\Controllers\Auth\RegisteredMedController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\RegistryMedController;
use App\Http\Controllers\Med\PatientController;
use App\Http\Controllers\Med\PatientMedicalrecordController;
use App\Http\Controllers\Med\VisitAdministrationController;
use App\Http\Controllers\Med\VisitController;
use App\Http\Middleware\AjaxRedirect;
use App\Http\Middleware\InterviewRedirect;
use App\Http\Middleware\MedAuth;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use App\Http\Middleware\VisitRedirect;
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
    Route::get('/patients/{patient}/confirm-delete', [PatientController::class, 'confirmDelete'])->name('patients.confirm-delete');
    Route::resource('patients.medicalrecords', PatientMedicalrecordController::class);

    /* Route::resource('visits', VisitController::class); */
    Route::get('/visits/create/{patient}', [VisitController::class, 'create'])->name('visits.create');
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
    Route::get('/visits/{visit}/edit', [VisitController::class, 'edit'])->name('visits.edit');
    Route::patch('/visit/{visit}', [VisitController::class, 'update'])->name('visits.update');
    Route::delete('/visits/{visit}', [VisitController::class, 'destroy'])->name('visits.destroy');

    Route::get('/patients/{patient}/visits', [VisitController::class, 'show'])->name('visits.show');



    Route::name('visitadministration.')->prefix('visitadministration/')->middleware([VisitRedirect::class])->group(function() {
        Route::get("/", [VisitAdministrationController::class, 'create']);

        Route::get("controlpanel", [VisitAdministrationController::class, 'create'])
                ->name("controlpanel");

        Route::post("controlpanel/newinteriew", [VisitAdministrationController::class, 'createNewInterview'])
                ->name("controlpanel.newinterview");

        Route::get("testselector", [VisitAdministrationController::class, 'createTestSelector'])
                ->name("testselector");

        Route::post("testselector", [VisitAdministrationController::class, 'storeTestSelector'])
                ->name("testselector");

        Route::middleware([InterviewRedirect::class])->group(function() {
            Route::get('testcompilation', [VisitAdministrationController::class, 'createTestCompilation'])
                    ->name('testcompilation');

            Route::delete('interviewdestroy', [VisitAdministrationController::class, 'destroyInterview'])
                    ->name('interviewdestroy');

            Route::middleware(AjaxRedirect::class)->name('ajax.')->prefix('ajax/')->group(function() {
                //Ajax routes
                Route::get('createtree', [VisitAdministrationController::class, 'createTree'])
                        ->name('createtree');

                Route::get('createnodeinput', [VisitAdministrationController::class, 'createNodeCompilation'])
                        ->name('createnodeinput');

                Route::post('storenode', [VisitAdministrationController::class, 'storeNode'])
                        ->name('storenode');

                Route::get('updatenode', [VisitAdministrationController::class, 'createUpdateNode'])
                        ->name('updatenode');

                Route::post('updatenode', [VisitAdministrationController::class, 'updateNode'])
                        ->name('updatenode');
            });

            Route::get('endinterview', [VisitAdministrationController::class, 'createInterviewEndPage'])
                    ->name('endinterview');

            Route::post('endinterview', [VisitAdministrationController::class, 'updateInterview'])
                    ->name('endinterview');
        });
    });

});
