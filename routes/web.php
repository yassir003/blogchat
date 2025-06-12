<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


Route::get('/admins-only', function() {
    return 'Admin page only';
})->middleware('can:visitAdminPages');


// User Routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
// Route::get('/poste', function () {
//     return view('single-post');
// });
Route::post('/register', [UserController::class,"register"])->middleware('guest');
Route::post('/login', [UserController::class,"login"])->middleware('guest');
Route::post('/logout', [UserController::class,"logout"])->middleware('loggedIn');
Route::get('/manage-avatar', [UserController::class,'showAvatarForm'])->middleware('loggedIn');
Route::post('/manage-avatar', [UserController::class,'storeAvatar'])->middleware('loggedIn');



// Post Routes
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('loggedIn');
Route::post('/create-post', [PostController::class, "createpost"])->middleware('loggedIn');
Route::get('/post/{post}', [PostController::class, "showSinglePost"]);
Route ::delete('/post/{post}', [PostController::class, "deletePost"])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, "EditPost"])->middleware('can:update,post');


// Profile Routes
Route::get('/profile/{user:username}',[UserController::class, "profile"]);