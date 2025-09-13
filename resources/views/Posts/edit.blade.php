@extends('Layouts.app')

@section('title', 'Edit Post')

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
                <b>Edit Post</b>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('posts.update',$post->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ old('title', $post->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" >{{ trim(old('description', $post->description)) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_post" class="form-label">Current Image</label><br>
                        @if($post->image_post)
                            <img src="{{ asset('storage/' . $post->image_post) }}" 
                                 alt="Post Image" width="150" class="mb-2">
                        @else
                            <p>No image uploaded</p>
                        @endif
                        <input type="file" name="image_post" class="form-control mt-2">
                    </div>

                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <button type="submit" class="btn btn-info">Update Post</button>
                </form>
            </div>
        </div>
    </div>

@endsection
