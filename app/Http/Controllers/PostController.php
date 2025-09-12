<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostManagement\StorePostRequest;
use App\Http\Requests\PostManagement\UpdatePostRequest;
use App\Models\User;
use App\Models\Post;
use App\Notifications\PostInteraction;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Post::with(['user.profile', 'likes'])
                    ->latest()
                    ->paginate(10);
        
        return view('Posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Load the post with relationships
        $post->load(['user.profile', 'likes', 'comments.user.profile']);
        
        // Get related posts or recent posts for the feed
        $posts = Post::with(['user.profile', 'likes'])
                    ->where('id', '!=', $post->id)
                    ->latest()
                    ->take(5)
                    ->get();
        
        return view('Posts.show', compact('post', 'posts'));
    }

    public function create()
    {
        return view('Posts.create');
    }

public function store(Request $request, Post $post)
{
    $request->validate([
        'content' => 'required|string|max:500',
        'parent_id' => 'nullable|exists:comments,id'
    ]);

    $comment = $post->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->content,
        'parent_id' => $request->parent_id,
    ]);

    // إذا كان طلب AJAX
    if($request->ajax()){
        return response()->json([
            'status' => 'success',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'user_image' => $comment->user->profile?->profile_image
                                 ? asset('storage/'.$comment->user->profile->profile_image)
                                 : asset('images/default-avatar.png'),
                'user_profile' => route('profile.public', $comment->user->id)
            ]
        ]);
    }

    return redirect()->back();
}


    public function edit(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('Posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully!');
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        
        if ($user->likes()->where('post_id', $post->id)->exists()) {
            $user->likes()->detach($post->id);
            $status = 'unliked';
        } else {
            $user->likes()->attach($post->id);
            $status = 'liked';
        }
        
        return response()->json([
            'status' => $status,
            'likesCount' => $post->likes()->count()
        ]);
    }
}


