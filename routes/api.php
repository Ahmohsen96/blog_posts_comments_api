<?php


use App\Http\Controllers\Admin\AdminAuth;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
use App\Http\Controllers\Frontend\GetPostController;
use Illuminate\Support\Facades\Route;


// all these route are protected
Route::middleware('auth:sanctum')->group(function () {

        Route::get('/admins', [AdminAuth::class, 'admins']);
        Route::post('/logout', [AdminAuth::class, 'logout']);




        // posts routes

        Route::get('/posts', [PostController::class, 'index']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{id}', [PostController::class, 'edit']);
        Route::post('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'delete']);
        Route::get('/posts/{search}', [PostController::class, 'search']);

        // comments
        Route::get('/comments', [CommentController::class, 'index']);
        Route::delete('/comments/{id}', [CommentController::class, 'delete']);
    });

  Route::post('/login', [AdminAuth::class, 'login']);




Route::prefix('/front')->group(function () {
    Route::get('/all-posts', [GetPostController::class, 'index']);
    Route::get('/single-posts/{id}', [GetPostController::class, 'getPostById']);
    Route::get('/search-posts/{search}', [GetPostController::class, 'searchPost']);
    Route::get('/comments', [FrontendCommentController::class, 'getComments']);
    Route::post('/comments/{id}', [FrontendCommentController::class, 'store']);
    Route::delete('/comments/{id}', [FrontendCommentController::class, 'delete']);

});
