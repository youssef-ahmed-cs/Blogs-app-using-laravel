<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{

    public function index()
    {
        $postsFromDB = Post::all();
        return view('Posts.index', ['postsFromDB' => $postsFromDB]);
    }

    function show(Post $post)
    {
        return view('Posts.show', ['post' => $post]);
    }

    function create()
    {
        $users = User::all();
        return view('Posts.create', ['users' => $users]);
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|min:3',
            'description' => 'string|max:1000|min:3',
            'post_creator' => 'required|string|exists:users,id'
        ]);

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $request->input('post_creator'),
        ]);

        return to_route('posts.index');
    }

    public function edit(Post $post)
    {
        $users = \App\Models\User::all();
        return view('Posts.edit', ['users' => $users, 'post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $singlePost = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255|min:3',
            'description' => 'string|max:1000|min:3',
            'post_creator' => 'required|string|exists:users,id'
        ]);
        $singlePost->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $request->input('post_creator')
        ]);
        return to_route('posts.show', $id);
    }

    public function destroy($id)
    {
        $singlePost = Post::findOrFail($id);
        $singlePost->delete();
        return to_route('posts.index');
    }
}
