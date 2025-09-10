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
        $this->authorize('viewAny', Post::class);
        $posts = Post::with('user_creator')->orderBy('views')->paginate(10);
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
        $users = User::all();
        return view('Posts.create', ['users' => $users]);
    }

public function store(Request $request)
{
    $request->validate([
        'description' => 'nullable|string',
        'image_post'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

$post = new Post();
$post->title = $request->title;
$post->description = $request->description;
$post->user_id = auth()->id();


    if ($request->hasFile('image_post')) {
        $post->image_post = $request->file('image_post')->store('posts', 'public');
    }

    $post->save();

    return redirect()->route('posts.index')->with('success', 'تم نشر البوست بنجاح 🎉');
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

        if ($request->hasFile('image_post')) {
            $user = auth()->user();
            $originalName = $request->file('image_post')->getClientOriginalName();
            $filename = $user->name . '_' . $user->id . '_' . time() . '_' . $originalName;
            $path = $request->file('image_post')->storeAs('posts', $filename, 'public');
            $data['image_post'] = $path;
        }

        $singlePost->update($data);
        return to_route('posts.show', $id);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);
        $post->delete();
        return to_route('posts.index');
    }


public function toggleLike(Post $post)
{
    $user = auth()->user();

    if($post->isLikedBy($user)){
        $post->likes()->where('user_id', $user->id)->delete();
        $status = 'unliked';
    } else {
        $post->likes()->create(['user_id' => $user->id]);
        $status = 'liked';

        // إرسال notification
        if($post->user->id !== $user->id){ // ما تبعتش notification لنفسه
            $post->user->notify(new PostInteraction($user, $post, 'like'));
        }
    }

    return response()->json([
        'status' => $status,
        'likesCount' => $post->likes()->count(),
    ]);
}
}


