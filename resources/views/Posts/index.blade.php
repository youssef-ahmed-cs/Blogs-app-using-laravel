@extends('layouts.app')

@section('title', 'Blogging')

@section('content')
    <div class="container py-5" style="max-width: 1100px;">

        {{-- أزرار التحكم --}}
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
            <a href="{{ route('posts.create') }}" class="btn btn-success">
                + Create Post
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                Dashboard
            </a>
            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                Notifications
            </a>
        </div>

        {{-- جدول البوستات --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📑 All Posts</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Title</th>
                        <th style="width: 20%;">Posted By</th>
                        <th style="width: 20%;">Created At</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center fw-bold">{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td class="text-center">{{ $post->user_creator->name ?? 'User Not Found' }}</td>
                            <td class="text-center">{{ $post->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <a class="btn btn-info btn-sm" href="{{ route('posts.show', $post->id) }}">
                                    View
                                </a>
                                @if(auth()->id() === $post->user_id)
                                    <a class="btn btn-primary btn-sm" href="{{ route('posts.edit', $post->id) }}">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('posts.destroy', $post->id) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this post?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No posts found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
