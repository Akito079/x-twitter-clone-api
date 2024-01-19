<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Filters\PostFilter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\File;
use App\Http\Resources\PostCollection;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {$filter = new PostFilter();
        $postFilter = $filter->transform($request);
        $posts = Post::where($postFilter);
        return new PostCollection($posts->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = [
            "content" => $request->content,
            "user_id" => $request->userId,
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
        return new PostResource(Post::create($data));
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
        return response()->json([
            "message" => "A post has been deleted",
        ]);
    }
}
