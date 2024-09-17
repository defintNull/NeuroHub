<?php

use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([GuestMiddleware::class])->get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
require __DIR__.'/med.php';
require __DIR__.'/testmed.php';
require __DIR__.'/admin.php';
