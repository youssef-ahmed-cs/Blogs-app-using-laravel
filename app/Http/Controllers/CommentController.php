<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentManagement\StoreCommentRequest;
use App\Http\Requests\CommentManagement\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $comments = Comment::with('user')->latest()->paginate(5);
        return view('Comments.index', ['comments' => $comments]);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);
        $data = $request->validated();
        $comment->update($data);
        return back()->with('success', 'Comment updated successfully');
    }

    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $request->validated();
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
            'parent_id' => $request->input('parent_id'),
            'post_id' => $post->id,
        ]);

        if ($post->user_creator && $post->user_creator->id !== Auth::id()) {  // notify user creator{
            $post->user_creator->notify(new CommentNotification($comment));
        }

        return to_route('posts.show', $post->id)->with('success', 'Comment created successfully');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully');
    }
}
