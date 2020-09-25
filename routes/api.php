<?php

use App\Http\Controllers\
{
    AuthUserController,
    FriendRequestController,
    FriendRequestResponseController,
    PostController,
    UserController,
    UserPostController,

};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);

    Route::get('auth-user', [AuthUserController::class, 'show']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/{user}/posts', [UserPostController::class, 'index']);

    Route::post('/friend-request', [FriendRequestController::class, 'store']);
    Route::post('/friend-request-response', [FriendRequestResponseController::class, 'store']);
});
