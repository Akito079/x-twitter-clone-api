<?php

use App\Http\Controllers\FollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\ReactionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//api resources
Route::apiResource('posts',PostController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('comments',CommentController::class);
//reactions
Route::post("reactions",[ReactionController::class,"react"]);
//followers
Route::get('followers/{id}',[FollowerController::class,'index']);
Route::post("followers",[FollowerController::class,"store"]);
