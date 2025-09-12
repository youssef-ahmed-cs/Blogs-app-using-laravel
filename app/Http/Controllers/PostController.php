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
    $posts = Post::with(['user.profile']) // user + profile
                 ->withCount(['likes', 'comments']) // عدد اللايكات والكومنتات
                 ->latest()
                 ->paginate(10);

    return view('Posts.index', compact('posts'));
}


    public function show(Post $post)
    {
        // Load the post with relationships and counts
        $post->load([
                'user.profile',
                'likes',
                'comments.user.profile',
                'comments.replies.user.profile',
            ])
             ->loadCount(['likes', 'comments']);
        
        // Get related posts or recent posts for the feed
        $posts = Post::with(['user.profile', 'likes'])
                    ->withCount(['likes', 'comments'])
                    ->where('id', '!=', $post->id)
                    ->latest()
                    ->take(5)
                    ->get();
        
        // Increment view count when accessing the post directly
        $post->increment('views');
        
        return view('Posts.show', compact('post', 'posts'));
    }
    
    /**
     * Record a view for the post
     */
    public function recordView(Post $post)
    {
        $post->increment('views');
        
        return response()->json([
            'success' => true,
            'views' => $post->views
        ]);
    }
    
    /**
     * Handle post sharing
     */
    public function share(Request $request, Post $post)
    {
        // Validate request
        $request->validate([
            'platform' => 'required|string|in:facebook,twitter,whatsapp,telegram,copy'
        ]);
        
        // Record the share activity
        $post->increment('shares');
        
        // Get the share URL based on the platform
        $shareUrl = route('posts.show', $post);
        $postTitle = $post->title ?: substr($post->description, 0, 60) . '...';
        
        $urls = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shareUrl),
            'twitter' => 'https://twitter.com/intent/tweet?text=' . urlencode($postTitle) . '&url=' . urlencode($shareUrl),
            'whatsapp' => 'https://api.whatsapp.com/send?text=' . urlencode($postTitle . ' ' . $shareUrl),
            'telegram' => 'https://t.me/share/url?url=' . urlencode($shareUrl) . '&text=' . urlencode($postTitle),
            'copy' => $shareUrl,
        ];
        
        return response()->json([
            'success' => true, 
            'url' => $urls[$request->platform],
            'shares' => $post->shares
        ]);
    }
    
    /**
     * Share preview for social media
     */
    public function sharePreview(Post $post)
    {
        return view('Posts.share-preview', compact('post'));
    }
    
    /**
     * Reshare a post as a new post with a quote
     */
    public function reshare(Request $request, Post $post)
    {
        // Validate request
        $request->validate([
            'quote' => 'nullable|string|max:500',
        ]);
        
        // Create a new post as a reshare
        $newPost = new Post();
        $newPost->user_id = auth()->id();
        $newPost->title = $request->has('title') ? $request->title : null;
        $newPost->description = $post->title ?? ''; // Use original post title as description
        $newPost->quote = $request->quote ?? ''; // Store the quote in the dedicated field
        $newPost->original_post_id = $post->id;
        $newPost->is_reshare = true;
        $newPost->save();
        
        // Increment the share count on the original post
        $post->increment('shares');
        
        // Notify the original post author if they're not the same as the resharing user
        if ($post->user_id != Auth::id()) {
            $post->user->notify(new \App\Notifications\PostInteraction(
                Auth::user(),
                $post,
                'reshare'
            ));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Post reshared successfully',
            'redirect' => route('posts.show', $newPost)
        ]);
    }

    public function create()
    {
        return view('Posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_post'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        Post::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'description'=> $request->description,
            'image_post' => $request->hasFile('image_post')
                ? $request->file('image_post')->store('posts', 'public')
                : null,
        ]);

        return redirect()->back()->with('success', 'تم نشر البوست بنجاح 🎉');
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
            'content' => $request->input('content'),
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


