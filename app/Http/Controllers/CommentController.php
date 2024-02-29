<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

use App\Http\Resources\CommentCollection;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CommentCollection(Comment::orderBy("created_at","desc")->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        return new CommentResource(Comment::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($postId)
    {
       return new CommentResource(Comment::where('post_id',$postId)->get());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
    }
}
