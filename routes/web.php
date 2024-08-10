<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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



Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');
Route::post('/register', [AuthController::class, 'doRegis'])->name('doRegis');
Route::get('/logout', [AuthController::class, 'doLogout'])->middleware(['auth'])->name('doLogout');

Route::middleware('auth')->group(function () {
    
        Route::get('/', [AppController::class, 'index'])->name('home');
        Route::get('/explore', [AppController::class, 'explore'])->name('explore');
        Route::get('/profile/{id}', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('update-profile');

        Route::post('/post/add', [ProfileController::class, 'post'])->name('addPost');
        Route::post('/post/comment', [ProfileController::class, 'comment'])->name('add-comment');
        Route::get('/comments/{postId}', [AppController::class, 'getComments'])->name('comments.get');
        
        Route::post('/post/delete', [ProfileController::class, 'softDelete'])->name('delete-post');
        
        Route::post('/follow', [FollowController::class, 'follow'])->name('follow');
        Route::post('/unfollow', [FollowController::class, 'unfollow'])->name('unfollow');

        Route::post('/like', [LikeController::class, 'like'])->name('like');
        Route::post('/unlike', [LikeController::class, 'unlike'])->name('unlike');
        Route::get('/likes/count/{postId}', [LikeController::class, 'countLikes'])->name('likes.count');
        Route::get('/check-like-status/{postId}', [LikeController::class, 'checkLikeStatus']);

});

