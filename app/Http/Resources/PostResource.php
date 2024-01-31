<?php

namespace App\Http\Resources;

use App\Models\Post;
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

        return [
            "id"=> $this->id,
            "content"=>$this->content,
            "userId" => $this->user_id,
            "userName" => Post::find($this->id)->user->name,
            "media" =>$this->media == null ? "" :explode(",",$this->media),
            "reactions" => $totalWeight,
            "comments" => count(Post::find($this->id)->comments),
            "commentDeatils" => CommentResource::collection($this->whenLoaded("comments")),
            "createdDate" => date_format($this->created_at,"d-M-Y/h:i A"),
        ];
    }
    public static $wrap = 'posts';
}
