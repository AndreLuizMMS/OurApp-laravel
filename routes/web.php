<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ---------------------- User --------------------------------------------------------------------------
// View
Route::get('/', [UserController::class, 'homePageView'])->name('login'); // view homepage
// Actions
Route::post('/register', [UserController::class, 'registerUser'])->middleware('guest'); // create new user
Route::post('/login', [UserController::class, 'loginUser'])->middleware('guest'); // user login 
Route::post('/logout', [UserController::class, 'logoutUser'])->middleware('mustBeLogged'); // user logout 


// ----------------------- Blog ---------------------------------------------------------------------------------
// View
Route::get('/create-post', [BlogController::class, 'createPostView'])->middleware('mustBeLogged'); // create-post
Route::get('/post/{post}', [BlogController::class, 'showSinglePostView']); // single-post
Route::get('/post/{post}/edit', [BlogController::class, 'showEditPostView'])->middleware('can:update,post'); // edit-post

// Actions
Route::post('/publish-post', [BlogController::class, 'publishPost'])->middleware('mustBeLogged'); // create post
Route::delete('/post/{post}', [BlogController::class, 'deletePost'])->middleware('can:delete,post'); // delete psot
Route::put('/post/{post}', [BlogController::class, 'updatePost'])->middleware('can:update,post'); // update post


// ---------------------- Profile -----------------------------------------------------------------------
// View
Route::get('/profile/{user:username}', [UserController::class, 'profileView']);  // profile-posts
Route::get('/manage-avatar', [UserController::class, 'manageAvatarView'])->middleware('mustBeLogged');  // manage-avatar

//Actions
Route::post('/manage-avatar', [UserController::class, 'storeNewAvatar'])->middleware('mustBeLogged'); // manage-avatar


// ----------------------- Follow -----------------------------------------------------------------------
Route::post('/follow/{user:username}', [FollowController::class, 'followUser'])->middleware('mustBeLogged'); // follow some user
Route::post('/unfollow/{user:username}', [FollowController::class, 'unfollowUser'])->middleware('mustBeLogged'); // unfollow some user