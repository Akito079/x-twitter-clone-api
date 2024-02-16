<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Hashtag;
use App\Filters\PostFilter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\File;
use App\Http\Resources\PostCollection;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {$filter = new PostFilter();
        $postFilter = $filter->transform($request);
        $posts = Post::where($postFilter);
        return new PostCollection($posts->orderBy('created_at','desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $content = $request->content;
        $data = [
            "content" => $request->content,
            "user_id" => $request->user()->id,
        ];
        $mediaFiles = [];
        $files = $request->file("media");
        if (isset($files)) {
            foreach ($files as $file) {
                $fileNames = uniqId() . $file->getClientOriginalName();
                $file->move(public_path() . "/postImages", $fileNames);
                $mediaFiles[] = $fileNames;
            }

            $data["media"] = Arr::join($mediaFiles, ",");
        }
        $post = Post::create($data);

        // creating hashtags of a post
        $hashTags = $this->extractHashtags($content);
        if(isset($hashTags)){
            foreach ($hashTags as $hashtag) {
                $hashtagModel = Hashtag::firstOrCreate(['name' => $hashtag]);
                $post->hashtags()->attach($hashtagModel);
            }
        }
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

        $postDetail = request()->query("includeComments");
        if(isset($postDetail)) {
          return new PostResource($post->loadMissing("comments"));
        }
        return new PostResource($post);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $data = [
            "content" => $request->content,
            "user_id" => $request->userId,
        ];
        $mediaFiles = [];
        $imgfiles = $request->file("media");

        //only update files if the request have files
        if (isset($imgfiles)) {
            $oldImages = explode(",", $post->media);
        //if orginal post have images,delete them
            if (isset($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    if (File::exists(public_path() . "/postImages/" . $oldImage)) {
                        File::delete(public_path() . "/postImages/" . $oldImage);
                    }
                }
            }
        // updatig new files to database
            foreach ($imgfiles as $imgFile) {
                $fileName = uniqid() . $imgFile->getClientOriginalName();
                $imgFile->move(public_path() . "/postImages", $fileName);
                $mediaFiles[] = $fileName;
            }
            $data["media"] = Arr::join($mediaFiles, ",");
        }

        //deleting pivot hashtag
        if(isset($post->hashtags)){
            foreach($post->hashtags as $hashtag){
                $hashtag->pivot->delete();
            }
        }
        $content = $request->content;
        $hashTags = $this->extractHashtags($content);

        //updating pivot hashtag
        if(isset($hashTags)){
            foreach ($hashTags as $hashtag) {
                $hashtagModel = Hashtag::firstOrCreate(['name' => $hashtag]);
                $post->hashtags()->attach($hashtagModel);
            }
        }
        $post->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $dbImages = explode(",", $post->media);
        if (isset($dbImages)) {
            foreach ($dbImages as $dbImage) {
                if (File::exists(public_path() . "/postimages/" . $dbImage)) {
                    File::delete(public_path() . "/postImages/" . $dbImage);
                }
            }
        }
        $post->delete();
        $post->comments()->delete();
        foreach($post->hashtags as $hashtag){
           $hashtag->pivot->delete();
        }
        return response()->json([
            "message" => "A post has been deleted",
        ]);
    }

    // extract the hashtags from the inputs
    protected function extractHashtags($content){
        $pattern = '/#(\w+)/';
        preg_match_all($pattern, $content, $matches);
        return array_unique($matches[1]);
    }
}
