<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //recation count
        $post = Post::find($this->id);
        $reactantFacade = $post->viaLoveReactant();
        $reactionTotal = $reactantFacade->getReactionTotal();
        $totalWeight = $reactionTotal->getCount();

        //determine if the login user has reacted to a post or not
        $user = User::find($request->user()->id);
        $post = Post::find($this->id);
        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $post->viaLoveReactant();
        $isReacted = $reacterFacade->hasReactedTo($post);

        return [
            "id"=> $this->id,
            "content"=>$this->content,
            "userId" => $this->user_id,
            "userName" => Post::find($this->id)->user->name,
            "userNickname" => Post::find($this->id)->user->nick_name,
            "media" =>$this->media == null ? "" :explode(",",$this->media),
            "reactions" => $totalWeight,
            "reactStatus" => $isReacted,
            "comments" => count(Post::find($this->id)->comments),
            "commentDeatils" => CommentResource::collection($this->whenLoaded("comments")),
            "createdDate" => date_format($this->created_at,"d-M-Y/h:i A"),
        ];
    }
    public static $wrap = 'posts';
}
