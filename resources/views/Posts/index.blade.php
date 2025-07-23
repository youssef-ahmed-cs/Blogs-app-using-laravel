@extends('layouts.app')

@section('title')
    Posts Index
@endsection

@section('content')
    <div class="d-flex justify-content-center gap-2 mb-4">
        <a href="{{ route('posts.create') }}" class="btn btn-success">
            Create Post
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Go to Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Posts</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-light text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Posted By</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($postsFromDB as $post)
                    <tr>
                        <td class="text-center">{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td class="text-center">
                            {{ $post->user_creator->name ?? 'User Not Found' }}
                        </td>
                        <td class="text-center">{{ $post->created_at->format('Y-m-d h:i') }}</td>
                        <td class="text-center">
                            <a class="btn btn-info btn-sm" href="{{ route('posts.show', $post->id) }}">👁 View</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('posts.edit', $post->id) }}">Edit</a>

                            <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No posts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
