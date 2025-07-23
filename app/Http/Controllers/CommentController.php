<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $comments = Comment::with('user')->latest()->paginate(5);
        return view('Comments.index', ['comments' => $comments]);
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);
        $data = $request->validate([
            'content' => 'required|string|min:1',
        ]);
        $comment->update($data);
        return back()->with('success', 'Comment updated successfully');
    }


    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully');
    }
}
