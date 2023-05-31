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

Route::controller(UserController::class)->group(function () {
  // View
  Route::get('/', 'homePageView');
  Route::get('/profile/{user:username}', 'profileView');
  Route::get('/manage-avatar', 'manageAvatarView')->middleware('mustBeLogged');
  Route::get('/profile/{user:username}/followers', 'profileFollowersView');
  Route::get('/profile/{user:username}/following', 'profileFollowingView');
  
  // Actions
  Route::post('/register', 'registerUser')->middleware('guest');
  Route::post('/login', 'loginUser')->middleware('guest');
  Route::post('/logout', 'logoutUser')->middleware('mustBeLogged');
  Route::post('/manage-avatar', 'storeNewAvatar')->middleware('mustBeLogged');
});

Route::controller(BlogController::class)->group(function () {
  // View
  Route::get('/create-post', 'createPostView')->middleware('mustBeLogged');
  Route::get('/post/{post}', 'showSinglePostView');
  Route::get('/post/{post}/edit', 'showEditPostView')->middleware('can:update,post');
  // Actions
  Route::post('/publish-post', 'publishPost')->middleware('mustBeLogged');
  Route::delete('/post/{post}', 'deletePost')->middleware('can:delete,post');
  Route::put('/post/{post}', 'updatePost')->middleware('can:update,post');
});

Route::controller(FollowController::class)->group(function () {
  // Actions
  Route::post('/follow/{user:username}', 'followUser')->middleware('mustBeLogged');
  Route::post('/unfollow/{user:username}', 'unfollowUser')->middleware('mustBeLogged');
});
