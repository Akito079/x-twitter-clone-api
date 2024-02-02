<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "nickName" => $this->nick_name,
            "email" => $this->email,
            "followers" => $this->find($this->id)->followers()->count(),
            "profileImage" => $this->profile_image,
            "posts" => $this->find($this->id)->posts,
        ];
    }
}
