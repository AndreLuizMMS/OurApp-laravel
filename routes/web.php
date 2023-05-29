<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
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



Route::get('/admin', function () {
  return "admin only";
})->middleware('can:visitAdminPages');
// User ----------------------------------------------------

// Returns View
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login'); // view homepage


// User Querys
Route::post('/register', [UserController::class, 'register'])->middleware('guest'); // create new user
Route::post('/login', [UserController::class, 'login'])->middleware('guest'); // user login 
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLogged'); // user logout 

// Blog ----------------------------------------------------

// Returns View
Route::get('/create-post', [BlogController::class, 'createPost'])->middleware('mustBeLogged'); // create-post
Route::get('/post/{post}', [BlogController::class, 'showSinglePost']); // single-post
Route::get('/post/{post}/edit', [BlogController::class, 'showEditPost'])->middleware('can:update,post'); // edit-post form

// Post Querys
Route::post('/publish-post', [BlogController::class, 'publishPost'])->middleware('mustBeLogged'); // create post
Route::delete('/post/{post}', [BlogController::class, 'delete'])->middleware('can:delete,post'); // delete psot
Route::put('/post/{post}', [BlogController::class, 'updatePost'])->middleware('can:update,post'); // update post

// Profile ----------------------------------------------------
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
