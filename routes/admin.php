<?php

use App\Http\Controllers\Med\CreateTestMedController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->middleware(['auth', AdminAuth::class])->group(function() {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('createtestmed', [CreateTestMedController::class, 'create'])
                ->name('createtestmed');

    Route::post('createtestmed', [CreateTestMedController::class, 'store']);

});
