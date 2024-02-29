<?php

namespace App\Http\Controllers;

use App\Http\Resources\HashtagResource;
use App\Models\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
    public function index(){
        $hashtag = Hashtag::get();
        return HashtagResource::collection($hashtag);
    }

    public function show(Hashtag $hashtag){
        $hashDetail = request()->query("includePosts");
        if(isset($hashDetail)) {
          return new HashtagResource($hashtag->loadMissing("posts"));
        }
        return new HashtagResource($hashtag);
    }
}
