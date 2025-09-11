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
        $posts = Post::with(['user.profile', 'likes', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Allow everyone to view posts
        $post->increment('views'); 

        // Load comments and likes with users
        $post->load(['comments.user.profile', 'likes.user']);

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        // Only authenticated users can create posts
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please login to create posts');
        }
        
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Only authenticated users can store posts
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'description' => 'required|string|max:1000',
            'title' => 'nullable|string|max:255',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image_post')) {
            $imagePath = $request->file('image_post')->store('posts', 'public');
            $post->image_post = $imagePath;
        }

        $post->save();

        return redirect()->route('home')->with('success', 'تم نشر البوست بنجاح 🎉');
    }

    public function edit(Post $post)
    {
        // Check if user owns the post
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string|max:1000',
            'title' => 'nullable|string|max:255',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post->title = $request->title;
        $post->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image_post')) {
            // Delete old image if exists
            if ($post->image_post) {
                Storage::disk('public')->delete($post->image_post);
            }

            $imagePath = $request->file('image_post')->store('posts', 'public');
            $post->image_post = $imagePath;
        }

        $post->save();

        return redirect()->route('posts.show', $post)->with('success', 'تم تحديث البوست بنجاح');
    }

    public function destroy(Post $post)
    {
        // Check if user owns the post
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($post->image_post) {
            Storage::disk('public')->delete($post->image_post);
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'تم حذف البوست بنجاح');
    }
}


