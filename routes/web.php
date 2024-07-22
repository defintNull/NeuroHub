<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
require __DIR__.'/med.php';
require __DIR__.'/testmed.php';
require __DIR__.'/admin.php';
