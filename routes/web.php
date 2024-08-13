<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
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
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/reset-password', [AuthController::class, 'doReset'])->name('doReset');
Route::post('/new-password', [AuthController::class, 'newPassword'])->name('newPassword');

Route::middleware('auth')->group(function () {
        // profile
        Route::get('/', [AppController::class, 'index'])->name('home');
        Route::get('/profile/{id}', [ProfileController::class, 'index'])->name('profile');
        Route::get('/profile/{id}/post', [ProfileController::class, 'post'])->name('post');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::post('/password/update', [AppController::class, 'editPassword'])->name('edit-password');
        Route::post('/toggle-private', [ProfileController::class, 'togglePrivate'])->name('toggle.private');
        
        
        
        // post
        Route::post('/post/add', [PostController::class, 'post'])->name('addPost');
        Route::post('/post/comment', [PostController::class, 'comment'])->name('add-comment');
        Route::get('/comments/{postId}', [PostController::class, 'getComments'])->name('comments.get');
        
        Route::post('/post/edit', [PostController::class, 'editPost'])->name('edit-post');
        
        Route::post('/post/delete', [PostController::class, 'softDelete'])->name('delete-post');
        
        // follow
        Route::post('/follow', [FollowController::class, 'follow'])->name('follow');
        Route::post('/unfollow', [FollowController::class, 'unfollow'])->name('unfollow');
        Route::get('/follow/delete/{id}', [FollowController::class, 'softDeleteFollow'])->name('softDeleteFollow');

        
        // like
        Route::post('/like', [LikeController::class, 'like'])->name('like');
        Route::post('/unlike', [LikeController::class, 'unlike'])->name('unlike');
        Route::get('/likes/count/{postId}', [LikeController::class, 'countLikes'])->name('likes.count');
        Route::get('/check-like-status/{postId}', [LikeController::class, 'checkLikeStatus']);
        
        // notif
        Route::get('/notification', [AppController::class, 'notification'])->name('notification');
        Route::post('/accept', [AppController::class, 'acceptFollower'])->name('acceptFollower');

        // explore
        Route::get('/explore', [AppController::class, 'explore'])->name('explore');
});

