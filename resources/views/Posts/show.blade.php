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
                    <img src="{{ $post->user->profile && $post->user->profile->profile_image 
                                ? asset('storage/' . $post->user->profile->profile_image) 
                                : asset('images/default-avatar.png') }}" 
                         class="rounded-circle me-2" width="45" height="45" alt="User Avatar">
                </a>
                <div>
                    <strong>{{ $post->user->name ?? 'مستخدم محذوف' }}</strong><br>
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>

            <!-- النص -->
            @if($post->title)
                <h5>{{ $post->title }}</h5>
            @endif
            <p class="mb-2">{{ $post->description }}</p>

            <!-- الصورة -->
            @if($post->image_post)
                <div class="post-img mb-2">
                    <img src="{{ asset('storage/' . $post->image_post) }}" 
                         class="img-fluid rounded">
                </div>
            @endif

            <!-- الأكشنز -->
            <div class="d-flex justify-content-around text-muted post-actions">
                <!-- لايك -->
                <button 
                    class="btn btn-link p-0 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" 
                    data-post-id="{{ $post->id }}">
                    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    <span class="like-count">{{ $post->likes->count() }}</span>
                </button>

                <!-- تعليقات -->
                <span><i class="bi bi-chat"></i> {{ $post->comments->count() }}</span>

                <!-- مشاركة -->
                <span><i class="bi bi-share"></i> مشاركة</span>
            </div>
        </div>
    </div>

    <!-- التعليقات -->
    <div class="card shadow-sm comment-section">
        <div class="card-header fw-bold">💬 التعليقات ({{ $post->comments->count() }})</div>
        <div class="card-body">

            <!-- إضافة تعليق جديد -->
            @auth
            <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-3">
                @csrf
                <div class="d-flex">
                    <img src="{{ auth()->user()->profile?->profile_image 
                        ? asset('storage/'.auth()->user()->profile->profile_image) 
                        : 'https://via.placeholder.com/32x32.png?text=U' }}" 
                         alt="Profile" width="32" height="32" class="rounded-circle me-2">
                    <div class="flex-grow-1">
                        <input type="text" name="content" class="form-control rounded-pill" 
                               placeholder="اكتب تعليقك..." required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mt-2">إضافة</button>
            </form>
            @endauth

            <!-- عرض التعليقات مع Reply -->
            @forelse($post->comments as $comment)
            <div class="comment-item fade-in mb-3">
                <div class="d-flex">
                    <a href="{{ route('profile.public', $comment->user->id) }}">
                        <img src="{{ $comment->user->profile && $comment->user->profile->profile_image 
                                    ? asset('storage/' . $comment->user->profile->profile_image) 
                                    : asset('images/default-avatar.png') }}" 
                             class="rounded-circle me-2" width="40" height="40" alt="User Avatar">
                    </a>

                    <div class="flex-grow-1">
                        <a href="{{ route('profile.public', $comment->user->id) }}" 
                           class="fw-bold text-decoration-none text-dark">
                            {{ $comment->user->name ?? 'مستخدم' }}
                        </a>
                        <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                        <p class="mb-1">{{ $comment->content }}</p>

                        @can('delete', $comment)
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                        </form>
                        @endcan

                        @auth
                        <!-- Reply Button -->
                        <button class="btn btn-sm btn-link reply-btn text-primary mt-1">Reply</button>

                        <!-- Reply Form -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="reply-form d-none mt-1">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="d-flex">
                                <input type="text" name="content" class="form-control form-control-sm rounded-pill me-2" placeholder="اكتب رد..." required>
                                <button type="submit" class="btn btn-primary btn-sm">Send</button>
                            </div>
                        </form>
                        @endauth

                        <!-- Reply Thread -->
                        <div class="reply-thread ms-4 mt-2"></div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted">لا توجد تعليقات بعد.</p>
            @endforelse

        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.reply-btn { cursor: pointer; transition: color 0.2s, transform 0.2s; }
.reply-btn:hover { color: #0d6efd; transform: scale(1.1); }
.reply-thread .comment-item { border-left: 2px solid #ddd; padding-left: 10px; margin-top: 8px; }
.comment-item.fade-in.animate__animated.animate__fadeIn { animation-duration: 0.5s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Like AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const likeCountSpan = this.querySelector('.like-count');
            const icon = this.querySelector('i');

            fetch(`/posts/${postId}/toggle-like`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'liked'){
                    this.classList.add('text-danger');
                    icon.classList.remove('bi-heart'); icon.classList.add('bi-heart-fill');
                } else {
                    this.classList.remove('text-danger');
                    icon.classList.remove('bi-heart-fill'); icon.classList.add('bi-heart');
                }
                likeCountSpan.textContent = data.likesCount;
            });
        });
    });

    // Reply toggle
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const form = btn.closest('.comment-item').querySelector('.reply-form');
            form.classList.toggle('d-none');
            form.querySelector('input[name="content"]').focus();
        });
    });

    // AJAX Reply submission
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const input = form.querySelector('input[name="content"]');
            const content = input.value.trim();
            if(!content) return;

            const postId = '{{ $post->id }}'; 
            const parentId = form.querySelector('input[name="parent_id"]').value;

            fetch('/posts/' + postId + '/comments', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content, parent_id: parentId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    const div = document.createElement('div');
                    div.classList.add('comment-item','animate__animated','animate__fadeIn','mt-2');
                    div.innerHTML = `
                        <img src="${data.comment.user_image}" class="rounded-circle me-2" width="32" height="32">
                        <div class="flex-grow-1">
                            <div class="bg-light rounded p-2">
                                <strong>${data.comment.user_name}</strong>
                                <p class="mb-0">${data.comment.content}</p>
                            </div>
                            <small class="text-muted">الآن</small>
                        </div>
                    `;
                    form.closest('.comment-item').querySelector('.reply-thread').appendChild(div);
                    input.value = '';
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endpush
