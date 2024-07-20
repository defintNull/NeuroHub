<?php

use App\Http\Middleware\TestMedAuth;
use Illuminate\Support\Facades\Route;

Route::middleware(TestMedAuth::class)->group(function() {

});
