<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

    Route::get('top', [PostsController::class, 'index'])->name('top');

    Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');

    Route::get('profile', [ProfileController::class, 'profile']);

    // プロフィール編集フォーム表示
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // プロフィール情報（アイコン含む）更新
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('follow-list', [UsersController::class, 'followList'])->name('users.followList');
    Route::get('follower-list', [UsersController::class, 'followerList'])->name('users.followerList');

    Route::get('/posts/{post}/edit_data', [PostsController::class, 'editData'])->name('posts.editData');

    Route::patch('/posts/{post}', [PostsController::class, 'update'])->name('posts.update');

    Route::delete('/posts/{post}', [PostsController::class, 'destroy'])->name('posts.destroy');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');

    Route::get('/search', [UsersController::class, 'search'])->name('users.search');

    Route::post('/users/{user}/follow', [UsersController::class, 'follow'])
         ->name('users.follow');

    Route::delete('/users/{user}/unfollow', [UsersController::class, 'unfollow'])
          ->name('users.unfollow');

    Route::get('/users/{user}', [UsersController::class, 'showProfile'])->name('users.showProfile');

});
