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
        $posts = Post::with('user_creator')->orderBy('views')->paginate(10);
        return view('Posts.index', compact('posts'));
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


}
