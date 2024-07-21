<?php

use App\Http\Middleware\TestMedAuth;
use Illuminate\Support\Facades\Route;

Route::name('testmed.')->prefix('testmed')->middleware(['auth', TestMedAuth::class])->group(function() {

    Route::get('/dashboard', function () {
        return view('testmed.dashboard');
    })->middleware(['verified'])->name('dashboard');

});
