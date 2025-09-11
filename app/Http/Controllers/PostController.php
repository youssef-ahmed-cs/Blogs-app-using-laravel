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
        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('Posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Allow everyone to view posts
        $post->increment('views'); 

        // Load comments and likes with users
        $post->load(['comments.user.profile', 'likes.user']);

        return view('Posts.show', compact('post'));
    }

    public function create()
    {
        // Only authenticated users can create posts
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please login to create posts');
        }
        
        return view('Posts.create');
    }

    public function store(Request $request)
    {
        // Only authenticated users can store posts
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'required|max:2000',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = auth()->id();

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
        $this->authorize('update', $post);
        return view('Posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'required|max:2000',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post->title = $request->title;
        $post->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image_post')) {
            // Delete old image
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
        $this->authorize('delete', $post);
        
        // Delete image if exists
        if ($post->image_post) {
            Storage::disk('public')->delete($post->image_post);
        }
        
        $post->delete();
        
        return redirect()->route('home')->with('success', 'تم حذف البوست بنجاح');
    }
}


