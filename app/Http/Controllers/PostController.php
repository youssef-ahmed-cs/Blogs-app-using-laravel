<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostManagement\StorePostRequest;
use App\Http\Requests\PostManagement\UpdatePostRequest;
use App\Models\User;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Post::class);
        $postsFromDB = Post::with('user_creator')->orderBy('views')->paginate(10);
        return view('Posts.index', ['postsFromDB' => $postsFromDB]);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        $post->increment('views');
        $posts = Post::with(['comments.user', 'likes.user'])->find($post->id);
        return view('Posts.show', ['posts' => $posts]);
    }

    public function create()
    {
        $users = User::all();
        return view('Posts.create', ['users' => $users]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);
        Post::create($request->validated());
        return to_route('posts.index');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $users = User::all();
        return view('Posts.edit', ['users' => $users, 'post' => $post]);
    }

    public function update(UpdatePostRequest $request, $id): RedirectResponse
    {
        $this->authorize('update', Post::find($id));
        $data = $request->validated();
        $singlePost = Post::findOrFail($id);
        $singlePost->update($data);
        return to_route('posts.show', $id);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        $post->delete();
        return to_route('posts.index');
    }

    public function storeComment(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        if ($post->user_creator && $post->user_creator->id !== Auth::id()) {
            $post->user_creator->notify(new CommentNotification($comment));
        }

        return redirect()->route('posts.show', $post->id)->with('success', 'Comment added successfully');
    }

    public function toggleLike(Post $post): RedirectResponse
    {
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }

}
