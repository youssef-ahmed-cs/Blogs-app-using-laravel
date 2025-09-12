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

    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $post = Post::findOrFail($postId);

        $user = Auth::user();
        
        // Ensure the parent comment (if provided) belongs to the same post
        if ($request->filled('parent_id')) {
            $parentBelongsToPost = Comment::where('id', $request->parent_id)
                ->where('post_id', $post->id)
                ->exists();
            if (!$parentBelongsToPost) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid parent comment for this post.'
                    ], 422);
                }
                return back()->withErrors(['parent_id' => 'Invalid parent comment for this post.']);
            }
        }
        
        // Create the comment using the fillable fields
        $comment = Comment::create([
            'content' => $request->input('content'),
            'user_id' => $user->id,
            'post_id' => $post->id,
            'parent_id' => $request->parent_id
        ]);
        
        // Send notification to post author if they're not the commenter
        if($post->user_id !== $user->id) {
            $post->user->notify(new CommentNotification($comment));
        }

            if ($request->expectsJson()) {
                $userProfileUrl = route('profile.public', $comment->user->id);
                $userImage = $comment->user->profile?->profile_image
                    ? asset('storage/' . $comment->user->profile->profile_image)
                    : asset('images/default-avatar.png');

                return response()->json([
                    'status' => 'success',
                    'comment' => [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user_name' => $comment->user->name,
                        'user_profile' => $userProfileUrl,
                        'user_image' => $userImage,
                        'created_human' => $comment->created_at->diffForHumans(),
                    ],
                ]);
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
