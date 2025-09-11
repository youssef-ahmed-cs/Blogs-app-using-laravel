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

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('Posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->increment('views'); 

        // جلب الكومنتات واللايكات + صاحبهم
        $post->load(['comments.user', 'likes.user']);

        return view('Posts.show', compact('post'));
    }

    public function create()
    {
        return view('Posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = auth()->id();
        $post->save();

        return redirect()->route('posts.index')->with('success', 'تم نشر البوست بنجاح 🎉');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('Posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $post->update($request->validated());
        
        return redirect()->route('posts.show', $post)->with('success', 'تم تحديث البوست بنجاح');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();
        
        return redirect()->route('posts.index')->with('success', 'تم حذف البوست بنجاح');
    }

    public function repost($postId)
{
    $original = Post::findOrFail($postId);

    // إنشاء نسخة جديدة للمستخدم الحالي
    $repost = $original->replicate(); // تنسخ كل الحقول ما عدا الـ ID
    $repost->user_id = auth()->id(); // ملكية البوست الجديد للمستخدم الحالي
    $repost->created_at = now();
    $repost->updated_at = now();
    $repost->save();

    return redirect()->back()->with('success', 'تم إعادة نشر البوست ✅');
}

}


