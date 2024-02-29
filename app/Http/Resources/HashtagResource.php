<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HashtagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "name" => $this->name,
            "postCount" => count($this->posts),
            "postDetails" => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
