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
      $authUser = User::find($request->authUserId);
      $followedUser = User::find($request->userId);
      //toggle follow
      $authUser->toggleFollow($followedUser);
      $username = $followedUser->name;
      //handling response based on the user is followiing another user or not
      if($authUser->isFollowing($followedUser)){
        return response()->json(["message"=>"You are following ". $username]);
      }else{
        return response()->json(["message"=>"You have unfollowed ". $username]);
      }
    }
}
