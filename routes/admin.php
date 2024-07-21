<?php

use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->prefix('admin')->middleware(AdminAuth::class)->group(function() {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['verified'])->name('dashboard');

});
