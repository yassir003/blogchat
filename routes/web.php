<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


// User Routes

Route::get('/', [UserController::class, "showCorrectHomepage"]);
// Route::get('/poste', function () {
//     return view('single-post');
// });
Route::post('/register', [UserController::class,"register"]);
Route::post('/login', [UserController::class,"login"]);
Route::post('/logout', [UserController::class,"logout"]);


// Post Routes

Route::get('/create-post', [PostController::class, "showCreateForm"]);
Route::post('/create-post', [PostController::class, "createpost"]);
Route::get('/post/{post}', [PostController::class, "showSinglePost"]);
