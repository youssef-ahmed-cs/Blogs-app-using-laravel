@extends('Layouts.app')

@section('title', 'Post Details')

@section('content')

    <div class="container py-5" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                ← Back to Posts
            </a>

            @if(auth()->id() === $posts->user_id)
                <div>
                    <a href="{{ route('posts.edit', $posts->id) }}" class="btn btn-primary me-2">Edit</a>
                    <form action="{{ route('posts.destroy', $posts->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- بوست --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $posts->title }}</h4>
            </div>
            <div class="card-body">
                <p class="mb-3">{{ $posts->description }}</p>

                @php
                    $imagePath = $posts->image ?? $posts->image_path ?? null;
                @endphp
                @if($imagePath)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $imagePath) }}"
                             alt="{{ $posts->title }} image"
                             class="img-fluid rounded shadow"
                             style="max-height: 450px; object-fit: contain; width: auto; max-width: 100%;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="alert alert-warning mt-2" style="display: none;">
                            <i class="fas fa-exclamation-triangle"></i> Image could not be loaded
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted">👁 Views: <b>{{ $posts->views }}</b></p>
                        <form action="{{ route('posts.toggleLike', $posts->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm {{ $posts->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-primary' }}">
                                {{ $posts->isLikedBy(auth()->user()) ? '♥ Unlike' : '♥ Like' }}
                            </button>
                            <span class="ms-2">{{ $posts->likes()->count() }} Likes</span>
                        </form>
                    </div>
                    <div class="col-md-6 border-start">
                        <h6 class="text-muted">Created by:</h6>
                        <p class="mb-1"><b>{{ $posts->user_creator->name ?? 'NOT FOUND' }}</b></p>
                        <p class="mb-1 text-muted">{{ $posts->user_creator->email ?? 'NOT FOUND' }}</p>
                        <p class="mb-0 small text-muted">{{ $posts->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                @if($posts->likes()->count())
                    <div class="mt-3 pt-3 border-top">
                        <span class="fw-bold">Liked by:</span>
                        @foreach($posts->likes as $like)
                            <span class="badge bg-info text-dark me-1">
                            {{ $like->user->name ?? 'Unknown' }}
                        </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Comments</h5>
            </div>
            <div class="card-body">
                @if($posts->comments->isEmpty())
                    <p class="text-muted">No comments yet.</p>
                @else
                    @foreach($posts->comments->where('parent_id', null) as $comment)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2">{{ $comment->content }}</p>

                            <button class="btn btn-sm btn-outline-primary"
                                    onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('d-none')">
                                Reply
                            </button>

                            <form action="{{ route('posts.comments.store', $posts->id) }}" method="POST"
                                  class="mt-2 d-none" id="reply-form-{{ $comment->id }}">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea class="form-control mb-2" name="content" rows="2"
                                          placeholder="Write a reply..."></textarea>
                                <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                            </form>

                            @foreach($comment->replies as $reply)
                                <div class="ms-4 mt-2 p-2 border rounded bg-white">
                                    <strong>{{ $reply->user->name ?? 'Anonymous' }}</strong>
                                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                    <p class="mb-0">{{ $reply->content }}</p>
                                </div>
                            @endforeach

                            @if(auth()->id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this comment?')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                @endif

                <form action="{{ route('posts.comments.store', $posts->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">Add a Comment</label>
                        <textarea class="form-control" id="content" name="content" rows="3"
                                  placeholder="Write your comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>

@endsection
