<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    // checking followers
    public function  index($id){
        $user= User::find($id);
        $followers = $user->followers;
        return response()->json(["followers"=>$followers]);
    }

    //toggle following
    public function store(Request $request){
      //getting the users from request
      $authUser = User::find($request->user()->id);
      $followedUser = User::find($request->userId);
      //toggle follow
      $authUser->toggleFollow($followedUser);
      //handling response based on the user is followiing another user or not
      if($authUser->isFollowing($followedUser)){
        return response()->json(["message"=>true]);
      }else{
        return response()->json(["message"=>false]);
      }
    }
}
