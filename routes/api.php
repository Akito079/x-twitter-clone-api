<?php

use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\ReactionController;

Route::middleware('auth:sanctum')->get('/user', function () {
    return new UserResource(Auth::user());
});

Route::post('/login',[ApiAuthController::class,'login']);
Route::post('/register',[ApiAuthController::class,'register']);

//api resources
Route::middleware('auth:sanctum')->group(function(){

    Route::post('profile',[ApiAuthController::class,"updateProfile"]);
    Route::post('/logout',[ApiAuthController::class,'logout']);
    Route::post('/changePassword',[ApiAuthController::class,"changePassword"]);
    Route::apiResource('posts',PostController::class);
    Route::apiResource('users', UserController::class);
    Route::post('comments',[CommentController::class,"store"]);
    Route::get('comments/{postId}',[CommentController::class,"show"]);
    Route::put("comments/{comment}",[CommentController::class,"update"]);
    Route::delete("comments/{comment}",[CommentController::class,"destroy"]);
    //reactions
    Route::post("reactions",[ReactionController::class,"react"]);
    //followers
    Route::get('followers/{id}',[FollowerController::class,'index']);
    Route::post("followers",[FollowerController::class,"store"]);
    // hashtags
    Route::get("hashtags",[HashtagController::class,"index"]);
    Route::get("hashtags/{hashtag}",[HashtagController::class,"show"]);
});
