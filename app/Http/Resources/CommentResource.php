<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "userId" => $this->user_id,
            "postId" => $this->post_id,
            "commentContent" => $this->comment_content,
            "createdDate" => date_format($this->created_at,"d-M-Y/h:i A"),
        ];
    }
}
