@extends('Layouts.app')

@section('title', 'عرض البوست')

@section('content')
<div class="container mt-4">

    <!-- البوست -->
    <div class="post-card card shadow-sm mb-4">
        <div class="card-body">
            <!-- الهيدر -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.public', $post->user->id) }}">
                        <img src="{{ $post->user->profile?->profile_image 
                            ? asset('storage/' . $post->user->profile->profile_image) 
                            : asset('images/default-avatar.png') }}" 
                             class="rounded-circle me-2" width="50" height="50" alt="User Avatar">
                    </a>
                    <div>
                        <a href="{{ route('profile.public', $post->user->id) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $post->user->name ?? 'مستخدم محذوف' }}
                        </a><br>
                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                @auth
                @if($post->user_id === auth()->id())
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                            id="dropdownMenuButtonShow{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButtonShow{{ $post->id }}">
                        <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                            <i class="bi bi-pencil me-2"></i> Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" 
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
                @endauth
            </div>

            <!-- نص البوست -->
            @if($post->title)
                <h5>{{ $post->title }}</h5>
            @endif
            <p class="mb-2">{{ $post->description }}</p>

            <!-- صورة البوست -->
            @if($post->image_post)
                <div class="post-img mb-3 text-center">
                    <img src="{{ asset('storage/' . $post->image_post) }}" class="img-fluid rounded" style="max-height:400px; object-fit:cover;">
                </div>
            @endif

            <!-- Post Stats -->
            <div class="d-flex justify-content-between align-items-center text-muted mb-3">
                <div class="stats-like-count">
                    @if($post->likes_count > 0)
                        <i class="bi bi-heart-fill text-danger me-1"></i> 
                        <span>{{ $post->likes_count }} {{ $post->likes_count == 1 ? 'like' : 'likes' }}</span>
                    @else
                        <span class="text-muted">No likes yet</span>
                    @endif
                </div>
                <div>
                    @if($post->comments_count > 0)
                        <i class="bi bi-chat-dots me-1"></i>
                        <span>{{ $post->comments_count }} {{ $post->comments_count == 1 ? 'comment' : 'comments' }}</span>
                    @endif
                </div>
            </div>

            <!-- أزرار الإعجاب والتعليقات والمشاركة -->
            <div class="d-flex justify-content-around border-top pt-2 text-muted post-actions">
                @auth
                <button class="btn btn-light p-2 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" data-post-id="{{ $post->id }}">
                    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i> 
                    <span class="like-text">{{ $post->isLikedBy(auth()->user()) ? 'Liked' : 'Like' }}</span>
                    <span class="like-count ms-1">({{ $post->likes_count }})</span>
                </button>
                @else
                <button class="btn btn-light p-2 require-auth" data-action="like">
                    <i class="bi bi-heart"></i> 
                    <span>Like</span>
                    <span class="like-count ms-1">({{ $post->likes_count }})</span>
                </button>
                @endauth
                
                <button class="btn btn-light p-2">
                    <i class="bi bi-chat"></i> 
                    <span>Comment ({{ $post->comments_count }})</span>
                </button>
                
                <button class="btn btn-light p-2 share-btn">
                    <i class="bi bi-share"></i> Share
                </button>
            </div>
            </div>
        </div>
    </div>

    <!-- التعليقات -->
    <div class="card shadow-sm comment-section mb-4">
        <div class="card-header fw-bold">💬 التعليقات ({{ $post->comments->count() }})</div>
        <div class="card-body">

            <!-- إضافة تعليق جديد -->
            @auth
            <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-3 new-comment-form">
                @csrf
                <div class="d-flex">
                    <img src="{{ auth()->user()->profile?->profile_image 
                        ? asset('storage/'.auth()->user()->profile->profile_image) 
                        : asset('images/default-avatar.png') }}" 
                         alt="Profile" width="35" height="35" class="rounded-circle me-2">
                    <input type="text" name="content" class="form-control rounded-pill" placeholder="اكتب تعليقك..." required>
                    <button type="submit" class="btn btn-primary btn-sm ms-2">إضافة</button>
                </div>
            </form>
            @endauth

            <!-- عرض التعليقات مع الردود -->
            <div class="comments-list">
@foreach($post->comments as $comment)
<div class="comment-item mb-3 p-2 rounded shadow-sm">
    <div class="d-flex align-items-start">
        <a href="{{ route('profile.public', $comment->user->id) }}">
            <img src="{{ $comment->user->profile?->profile_image 
                ? asset('storage/' . $comment->user->profile->profile_image) 
                : asset('images/default-avatar.png') }}" 
                 class="rounded-circle me-2" width="40" height="40">
        </a>
        <div class="flex-grow-1">
            <a href="{{ route('profile.public', $comment->user->id) }}" class="fw-bold text-dark text-decoration-none">
                {{ $comment->user->name }}
            </a>
            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
            <p class="mb-1">{{ $comment->content }}</p>

            @auth
            <button class="btn btn-sm btn-link text-primary mt-1 reply-btn">Reply</button>

            <form action="{{ route('comments.store', $post) }}" method="POST" class="reply-form d-none mt-2">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="d-flex">
                    <input type="text" name="content" class="form-control form-control-sm rounded-pill me-2" placeholder="اكتب رد..." required>
                    <button type="submit" class="btn btn-primary btn-sm">Send</button>
                </div>
            </form>

            <div class="reply-thread ms-4 mt-2"></div>
            @endauth
        </div>
    </div>
</div>
@endforeach

            </div>

        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.comment-item { border-left: 3px solid #eee; padding-left: 10px; margin-bottom: 10px; }
.reply-thread .comment-item { border-left: 2px solid #ddd; margin-top: 8px; padding-left: 12px; }
.reply-btn { cursor: pointer; transition: color 0.2s; }
.reply-btn:hover { color: #0d6efd; }
.comment-section .card-body { max-height: 600px; overflow-y: auto; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const form = btn.closest('.comment-item').querySelector('.reply-form');
            form.classList.toggle('d-none');
            form.querySelector('input[name="content"]').focus();
        });
    });

    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const input = form.querySelector('input[name="content"]');
            if(!input.value.trim()) return;

            const postId = '{{ $post->id }}';
            const parentId = form.querySelector('input[name="parent_id"]').value;

            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Accept':'application/json',
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({ content: input.value.trim(), parent_id: parentId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status==='success'){
                    const div = document.createElement('div');
                    div.classList.add('comment-item','p-2','rounded','shadow-sm','mt-2');
                    div.innerHTML = `
                        <a href="${data.comment.user_profile}">
                            <img src="${data.comment.user_image}" class="rounded-circle me-2" width="32" height="32">
                        </a>
                        <div class="flex-grow-1">
                            <a href="${data.comment.user_profile}" class="fw-bold text-dark text-decoration-none">
                                ${data.comment.user_name}
                            </a>
                            <p class="mb-0">${data.comment.content}</p>
                            <small class="text-muted">الآن</small>
                        </div>
                    `;
                    form.closest('.comment-item').querySelector('.reply-thread').appendChild(div);
                    input.value = '';
                    form.classList.add('d-none');
                }
            });
        });
    });

    // Like functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const icon = this.querySelector('i');
            const likeText = this.querySelector('.like-text');
            const likeCount = this.querySelector('.like-count');
            const statsLikeCount = document.querySelector('.stats-like-count');
            
            fetch(`/posts/${postId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    if (data.liked) {
                        this.classList.add('text-danger');
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                        likeText.textContent = 'Liked';
                    } else {
                        this.classList.remove('text-danger');
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                        likeText.textContent = 'Like';
                    }
                    
                    // Update like count in button
                    likeCount.textContent = `(${data.likes_count})`;
                    
                    // Update like count in stats section
                    if (statsLikeCount) {
                        if (data.likes_count > 0) {
                            statsLikeCount.innerHTML = `<i class="bi bi-heart-fill text-danger me-1"></i> <span>${data.likes_count} ${data.likes_count == 1 ? 'like' : 'likes'}</span>`;
                        } else {
                            statsLikeCount.innerHTML = `<span class="text-muted">No likes yet</span>`;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});

</script>
@endpush
