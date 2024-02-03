<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Auth\ApiAuthController;

Route::middleware(['auth:api','cors'])->get('/user', function () {
    return new UserResource(Auth::user());
});

//api resources
Route::middleware(['auth:api','cors'])->group(function(){
    Route::apiResource('posts',PostController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('comments',CommentController::class);
    //reactions
    Route::post("reactions",[ReactionController::class,"react"]);
    //followers
    Route::get('followers/{id}',[FollowerController::class,'index']);
    Route::post("followers",[FollowerController::class,"store"]);
    //logout
    Route::post('logout',[ApiAuthController::class,'logout']);
});

Route::middleware('cors')->group(function(){
    Route::post('login',[ApiAuthController::class,'login']);
    Route::post('register',[ApiAuthController::class,'register']);
});
