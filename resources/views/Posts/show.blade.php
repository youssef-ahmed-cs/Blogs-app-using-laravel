@extends('Layouts.app')

@section('title', 'عرض البوست')

@section('content')
<div class="container mt-4">

    <!-- البوست -->
    <div class="post-card card shadow-sm mb-4">
        <div class="card-body">
            <!-- الهيدر -->
            <div class="d-flex align-items-center mb-3">
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

            <!-- أزرار الإعجاب والتعليقات والمشاركة -->
            <div class="d-flex justify-content-around border-top pt-2 text-muted post-actions">
                <button class="btn btn-light p-1 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" data-post-id="{{ $post->id }}">
                    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i> <span>{{ $post->likes->count() }}</span>
                </button>
                <button class="btn btn-light p-1">
                    <i class="bi bi-chat"></i> {{ $post->comments->count() }}
                </button>
                <button class="btn btn-light p-1 share-btn">
                    <i class="bi bi-share"></i> مشاركة
                </button>
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
});

</script>
@endpush
