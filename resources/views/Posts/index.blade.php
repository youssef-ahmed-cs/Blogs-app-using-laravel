@extends('Layouts.app')

@section('title', 'Home - FaceBog')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @auth
            <!-- Create Post Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ auth()->user()->profile?->profile_image ? asset('storage/'.auth()->user()->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                             alt="Profile" width="40" height="40" class="rounded-circle me-3">
                        <div class="flex-grow-1">
                            <a href="{{ route('posts.create') }}" class="form-control text-decoration-none text-muted p-3 d-block" 
                               style="border-radius: 25px; cursor: pointer;">
                                What's on your mind, {{ auth()->user()->name }}?
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <a href="{{ route('posts.create') }}" class="btn btn-light flex-fill me-2">
                            <i class="bi bi-camera text-success"></i> Photo/Video
                        </a>
                        <a href="{{ route('posts.create') }}" class="btn btn-light flex-fill">
                            <i class="bi bi-emoji-smile text-warning"></i> Feeling/Activity
                        </a>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Posts Feed -->
            @forelse($posts as $post)
            <div class="card mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="{{ $post->user->profile?->profile_image ? asset('storage/'.$post->user->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                                 alt="Profile" width="40" height="40" class="rounded-circle me-3">
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('profile.public', $post->user->id) }}" class="text-decoration-none">
                                        {{ $post->user->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        
                        @auth
                        @if($post->user_id === auth()->id())
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                    <i class="bi bi-pencil"></i> Edit</a></li>
                                <li>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>

                <div class="card-body">
                    @if($post->title)
                    <h5>{{ $post->title }}</h5>
                    @endif
                    <p>{{ $post->description }}</p>

                    @if($post->image_post)
                    <img src="{{ asset('storage/'.$post->image_post) }}" 
                         class="img-fluid rounded mb-3" alt="Post Image">
                    @endif

                    <!-- Post Stats -->
                    <div class="d-flex justify-content-between align-items-center text-muted mb-3">
                        <span>
                            @if($post->likes_count > 0)
                            <i class="bi bi-heart-fill text-danger"></i> {{ $post->likes_count }}
                            @endif
                        </span>
                        <span>
                            @if($post->comments_count > 0)
                            {{ $post->comments_count }} comments
                            @endif
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex border-top pt-2">
                        @auth
                        <button class="btn btn-light flex-fill me-2 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}"
                                data-post-id="{{ $post->id }}">
                            <i class="bi bi-heart{{ $post->isLikedBy(auth()->user()) ? '-fill' : '' }}"></i> 
                            Like
                        </button>
                        @else
                        <button class="btn btn-light flex-fill me-2 require-auth" data-action="like">
                            <i class="bi bi-heart"></i> 
                            Like
                        </button>
                        @endauth

                        <button class="btn btn-light flex-fill me-2 {{ !auth()->check() ? 'require-auth' : '' }}" 
                                onclick="toggleComments({{ $post->id }})"
                                data-action="comment">
                            <i class="bi bi-chat"></i> Comment
                        </button>

                        <a href="{{ route('posts.show', $post) }}" class="btn btn-light flex-fill">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>

                    <!-- Comments Section -->
                    <div id="comments-{{ $post->id }}" class="mt-3" style="display: none;">
                        @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="d-flex">
                                <img src="{{ auth()->user()->profile?->profile_image ? asset('storage/'.auth()->user()->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
                                     alt="Profile" width="32" height="32" class="rounded-circle me-2">
                                <div class="flex-grow-1">
                                    <input type="text" name="content" class="form-control rounded-pill" 
                                           placeholder="Write a comment..." required>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="comment-form mb-3">
                            <div class="d-flex">
                                <div class="rounded-circle bg-secondary me-2" style="width: 32px; height: 32px;"></div>
                                <input type="text" class="form-control rounded-pill comment-input require-auth" 
                                       placeholder="Write a comment...">
                            </div>
                        </div>
                        @endauth

                        <!-- Recent Comments -->
                        @foreach($post->comments->take(3) as $comment)
                        <div class="d-flex mb-2">
                            <img src="{{ $comment->user->profile?->profile_image ? asset('storage/'.$comment->user->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
                                 alt="Profile" width="32" height="32" class="rounded-circle me-2">
                            <div class="flex-grow-1">
                                <div class="bg-light rounded p-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach

                        @if($post->comments->count() > 3)
                        <a href="{{ route('posts.show', $post) }}" class="text-primary">
                            View all {{ $post->comments->count() }} comments
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                    <h5>No posts yet</h5>
                    <p class="text-muted">Be the first to share something!</p>
                    @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
                    @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Join FaceBog</a>
                    @endauth
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function toggleComments(postId) {
    const commentsDiv = document.getElementById('comments-' + postId);
    if (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') {
        commentsDiv.style.display = 'block';
    } else {
        commentsDiv.style.display = 'none';
    }
}
</script>
@endsection
