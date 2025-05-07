<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, "showCorrectHomepage"]);

Route::get('/post', function () {
    return view('single-post');
});

Route::post('/register', [UserController::class,"register"]);
Route::post('/login', [UserController::class,"login"]);