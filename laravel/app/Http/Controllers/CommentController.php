<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use App\Http\Controllers\BaseController;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Save Comment to CoWiki-Article
     *
     * @authenticated
     *
     * @bodyParam article_id int required the Article ID of the article you want to attach your comment to.
     * @bodyParam content string required The comments message. Must not be longer than 1000 Characters.
     */
    public function store(Request $request)
    {
        if (!$this->authUser->can('create comments')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'content' => 'required|max:1000',
             'article_id' => 'required|exists:articles,id'
         ]);

        $newComment = Comment::create([
           'content' => $request->content,
           'article_id' => $request->article_id
        ]);

        return new CommentResource($newComment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove Comment from the CoWiki.
     *
     * @urlParam article_id int required the Comment ID of the comment you want to remove.
     * @authenticated
     */
    public function destroy(Comment $comment)
    {
        if (!$this->authUser->can('delete comments')) {
            return parent::abortUnauthorized();
        }

        $comment->delete();
        return new CommentResource($comment);
    }
}
