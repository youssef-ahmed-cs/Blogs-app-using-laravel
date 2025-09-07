@extends('layouts.app')

@section('title')
    Edit
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                <b><b>Edit Post</b></b>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('posts.update',$post->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{$post['title']}}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  required>{{$post['description']}}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="post_creator" class="form-label">Post Creator</label>
                        <select name="post_creator" id="post_creator" class="form-select">
                            @foreach($users as $user)
                                <option
                                    @selected($post->user_id === $user->id) value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Update Post</button>
                </form>
            </div>
        </div>

@endsection
