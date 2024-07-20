<?php

use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

Route::middleware(AdminAuth::class)->group(function() {

});
