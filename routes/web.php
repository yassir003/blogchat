<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;


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
Route::get('/search/{term}', [PostController::class, "search"]);


// Profile Routes
Route::get('/profile/{user:username}',[UserController::class, "profile"]);
Route::get('/profile/{user:username}/followers',[UserController::class, "profileFollowers"]);
Route::get('/profile/{user:username}/following',[UserController::class, "profileFollowing"]);



// Follow Routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('loggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('loggedIn');


// Chat Routes
Route::post('/send-chat-message', function(Request $request) {
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue'=>strip_tags($request->textvalue), 'avatar' =>auth()->user()->avatar]))->toOthers();
    return response()->noContent();

})->middleware('loggedIn');