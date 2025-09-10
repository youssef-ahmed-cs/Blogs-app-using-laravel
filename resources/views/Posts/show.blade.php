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

<form action="{{ route('comments.store', $post->id) }}" method="POST">
    @csrf
    <input type="text" name="content" class="form-control" placeholder="اكتب تعليقك..." required>
    <button type="submit" class="btn btn-primary mt-2">إضافة</button>
</form>

            <!-- عرض التعليقات -->
            @forelse($post->comments as $comment)
                <div class="comment-item fade-in mb-3">
                    <div class="d-flex">
<a href="{{ route('profile.public', $comment->user->id) }}">
    <img src="{{ $comment->user->profile && $comment->user->profile->profile_image 
                    ? asset('storage/' . $comment->user->profile->profile_image) 
                    : asset('images/default-avatar.png') }}" 
         class="rounded-circle me-2" width="40" height="40" alt="User Avatar">
</a>

                        <div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // لايك AJAX
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const postId = this.dataset.postId;
            const likeCountSpan = this.querySelector('.like-count');
            const icon = this.querySelector('i');

            fetch(`/posts/${postId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'liked') {
                    this.classList.add('text-danger');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    this.classList.remove('text-danger');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
                likeCountSpan.textContent = data.likesCount;
            });
        });
    });
});
</script>
@endpush
