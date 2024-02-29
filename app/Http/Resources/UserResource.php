<?php

namespace App\Http\Resources;

use App\Models\User;
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
        // for follow status
        $authUser = User::find($request->user()->id);
        $user = User::find($this->id);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "nickName" => $this->nick_name,
            "isFollowed" => $authUser->isFollowing($user),
            "followers" => User::find($this->id)->followers->count(),
            "followings" => User::find($this->id)->followings->count(),
            "email" => $this->email,
            "profileImage" => $this->profile_image,
            "posts" => count($this->find($this->id)->posts),
        ];
    }
}
