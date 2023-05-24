<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->middleware('auth:sanctum')->name('logout');
    });

    Route::get('profile/{part?}', [ProfileController::class, 'profile'])->where('part', 'posts|comments')->middleware('auth:sanctum')->name('profile');

    Route::apiResource('posts', PostController::class);

    Route::apiResource('posts.comments', CommentController::class)->shallow()->only('store', 'update', 'destroy');

    Route::apiResource('tags', TagController::class)->only('index', 'show');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/{part?}', [UserController::class, 'show'])->where('part', 'posts|comments')->name('users.show');
});