<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function react(Request $request)
    {
        $user = User::find($request->userId);
        $post = Post::find($request->postId);
        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $post->viaLoveReactant();
        $isReacted = $reacterFacade->hasReactedTo($post);
        if (!$isReacted) {
            $reacterFacade->reactTo($post, 'Like');
            return response()->json(["message" => "You have  reacted to a post"]);
        } else {
            $reacterFacade->unreactTo($post, 'Like');
            return response()->json(["message"=>"You have unreacted to the post"]);
        }
    }
}
