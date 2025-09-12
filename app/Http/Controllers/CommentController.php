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

public function toggleLike(Post $post)
{
    $user = auth()->user();

    if ($post->likes()->where('user_id', $user->id)->exists()) {
        $post->likes()->where('user_id', $user->id)->delete();
        $status = 'unliked';
    } else {
        $post->likes()->create(['user_id' => $user->id]);
        $status = 'liked';

        // إرسال notification عند Like
        if($post->user_id !== $user->id){
            $post->user->notify(new CommentNotification($user, $post, null, 'like'));
        }
    }

    return response()->json([
        'status' => $status,
        'likesCount' => $post->likes()->count(),
    ]);
}

    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $post = Post::findOrFail($postId);

        $user = Auth::user();
        
        // Create the comment using the fillable fields
        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => $user->id,
            'post_id' => $post->id,
            'parent_id' => $request->parent_id
        ]);
        
        // Send notification to post author if they're not the commenter
        if($post->user_id !== $user->id) {
            $post->user->notify(new CommentNotification($comment));
        }

        return redirect()->back()->with('success', 'Comment added successfully ✅');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        if ($comment->user_id !== $user->id) {
            abort(403, 'You are not allowed to delete this comment');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully ❌');
    }

}
