<?php

use App\Http\Controllers\Auth\RegisteredTestMedController;
use App\Http\Controllers\Profile\RegistryTestMedController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\TestMed\CreateTestController;
use App\Http\Controllers\TestMed\TestController;
use App\Http\Middleware\AjaxRedirect;
use App\Http\Middleware\RegistrationRedirect;
use App\Http\Middleware\RegistrationStatus;
use App\Http\Middleware\TestCreationRedirect;
use App\Http\Middleware\TestCreationStatus;
use App\Http\Middleware\TestMedAuth;
use Illuminate\Support\Facades\Route;

Route::name('testmed.')->prefix('testmed')->middleware(['auth', 'verified', TestMedAuth::class, RegistrationRedirect::class, TestCreationStatus::class])->group(function() {

    Route::get('/', function () {
        return view('testmed.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('testmed.dashboard');
    })->name('dashboard');

    Route::get('register', [RegisteredTestMedController::class, 'create'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class)
            ->name('register');

    Route::post('register', [RegisteredTestMedController::class, 'store'])
            ->middleware(RegistrationStatus::class)
            ->withoutMiddleware(RegistrationRedirect::class);

    Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

    Route::get('registry', [RegistryTestMedController::class, 'edit'])
            ->name('registry.edit');

    Route::patch('registry', [RegistryTestMedController::class, 'update'])
            ->name('registry.update');

    Route::get('createtest', [CreateTestController::class, 'create'])
            ->name('createtest');

    Route::post('createtest', [CreateTestController::class, 'store']);

    Route::resource('tests', TestController::class)->only([
        'index',
    ]);

    Route::get('createteststructure', [CreateTestController::class, 'createtest'])
            ->middleware(TestCreationRedirect::class)
            ->withoutMiddleware(TestCreationStatus::class)
            ->name('createteststructure');

    Route::post('createteststructure/confirmcreation', [CreateTestController::class, 'storeTest'])
            ->middleware(TestCreationRedirect::class)
            ->withoutMiddleware(TestCreationStatus::class)
            ->name('createteststructure.confirmcreation');

    Route::delete('createteststructure', [CreateTestController::class, 'destroy'])
            ->middleware(TestCreationRedirect::class)
            ->withoutMiddleware(TestCreationStatus::class)
            ->name('createteststructure.destroy');

    Route::middleware([TestCreationRedirect::class, AjaxRedirect::class])->name('createteststructure.ajax.')->prefix('createteststructure/ajax')->withoutMiddleware(TestCreationStatus::class)->group(function() {
        //Ajax Route
        Route::get('addsectionquestionbutton', [CreateTestController::class, 'createAddSectionQuestionButton'])
                ->name('addsectionquestionbutton');

        Route::get('addsection', [CreateTestController::class, 'createsection'])
                ->name('addsection');

        Route::post('addsection', [CreateTestController::class, 'storesection'])
                ->name('addsection');

        Route::get('addquestion', [CreateTestController::class, 'createquestion'])
                ->name('addquestion');

        Route::post('addquestion', [CreateTestController::class, 'storequestion'])
                ->name('addquestion');

        Route::post('addmultiplequestion', [CreateTestController::class, 'storemultiplequestion'])
                ->name('addmultiplequestion');

        Route::post('addvaluequestion', [CreateTestController::class, 'storevaluequestion'])
                ->name('addvaluequestion');

        Route::post('addopenquestion', [CreateTestController::class, 'storeOpenQuestion'])
                ->name('addopenquestion');

        Route::post('addmultipleselectionquestion', [CreateTestController::class, 'storeMultipleSelectionQuestion'])
                ->name('addmultipleselectionquestion');

        Route::post('cancelquestion', [CreateTestController::class, 'cancelquestion'])
                ->name('cancelquestion');

        Route::get('createtree', [CreateTestController::class, 'createTree'])
                ->name('createtree');

        Route::get('createdeletemodifybutton', [CreateTestController::class, 'createDeleteModifyButton'])
                ->name('createdeletemodifybutton');

        Route::post('deleteelement', [CreateTestController::class, 'deleteElement'])
                ->name('deleteelement');

        Route::post('createelementmodify', [CreateTestController::class, 'createElementModify'])
                ->name('createelementmodify');

        Route::post('updatetest', [CreateTestController::class, 'updateTest'])
                ->name('updatetest');

        Route::post('updatesection', [CreateTestController::class, 'updateSection'])
                ->name('updatesection');

        Route::post('updatevaluequestion', [CreateTestController::class, 'updateValueQuestion'])
                ->name('updatevaluequestion');

        Route::post('updatemultiplequestion', [CreateTestController::class, 'updateMultipleQuestion'])
                ->name('updatemultiplequestion');

        Route::post('updateopenquestion', [CreateTestController::class, 'updateOpenQuestion'])
                ->name('updateopenquestion');

        Route::post('updatemultipleselectionquestion', [CreateTestController::class, 'updateMultipleSelectionQuestion'])
                ->name('updatemultipleselectionquestion');

        Route::get('multiplequestionitem', [CreateTestController::class, 'createMultipleQuestionItem'])
                ->name('multiplequestionitem');

        Route::get('valuequestionitem', [CreateTestController::class, 'createValueQuestionItem'])
                ->name('valuequestionitem');

        Route::get('multipleselectionquestionitem', [CreateTestController::class, 'createMultipleSelectionQuestionItem'])
                ->name('multipleselectionquestionitem');
    });
});
