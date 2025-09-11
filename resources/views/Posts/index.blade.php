@extends('Layouts.app')

@section('title', ' Posts')

@section('content')
<div class="container mt-4">

    <!-- إنشاء بوست جديد -->
    <div class="create-post card shadow-sm mb-4 fade-in border-0">
        <div class="card-body d-flex align-items-start">
            <img src="{{ auth()->user()->profile && auth()->user()->profile->profile_image 
                ? asset('storage/' . auth()->user()->profile->profile_image) 
                : asset('images/default-avatar.png') }}" 
                class="rounded-circle border me-3" width="50" height="50" alt="User Avatar">

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
                @csrf
                    <input type="text" name="title" class="form-control mb-2" placeholder="عنوان البوست">
                <textarea name="description" class="form-control mb-2 rounded-3 shadow-sm" rows="2" placeholder="بماذا تفكر؟"></textarea>
                <input type="file" name="image_post" class="form-control mb-2 rounded-3 shadow-sm">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">نشر</button>
                </div>
            </form>
        </div>
    </div>

    <!-- عرض البوستات -->
    @foreach($posts as $post)
    <div class="post-card card shadow-sm mb-4 border-0">

        <!-- هيدر البوست -->
        <div class="post-header d-flex align-items-center p-3 border-bottom">
            <a href="{{ route('profile.public', $post->user->id) }}" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="{{ $post->user->profile && $post->user->profile->profile_image 
                    ? asset('storage/' . $post->user->profile->profile_image) 
                    : asset('images/default-avatar.png') }}" 
                    class="rounded-circle border me-2" width="50" height="50" alt="User Avatar"> 
                <div>
                    <strong>{{ $post->user->name ?? 'مستخدم محذوف' }}</strong><br>
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </a>

            <!-- منيو 3 نقط -->
            @can('update', $post)
                <div class="dropdown ms-auto">
                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">✏️ تعديل</a></li>
                        <li>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">🗑️ حذف</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endcan
        </div>

        <!-- النص -->
        @if($post->description)
            <div class="post-body px-3 pt-2">
                <p class="mb-2 fs-6">{{ $post->description }}</p>
            </div>
        @endif

<!-- الصورة في البوست -->
@if($post->image_post)
    <div class="post-img text-center">
        <img src="{{ asset('storage/' . $post->image_post) }}" 
             class="post-image img-thumbnail" 
             data-bs-toggle="modal" 
             data-bs-target="#imageModal{{ $post->id }}" 
             alt="Post Image">
    </div>

    <!-- المودال -->
    <div class="modal fade" id="imageModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $post->image_post) }}" class="img-fluid" alt="Full Image">
                </div>
            </div>
        </div>
    </div>
@endif



<!-- الأكشنز -->
<div class="post-actions d-flex flex-column text-muted border-top p-2">

    <div class="d-flex justify-content-around mb-2">
        <!-- Like -->
        <button 
            class="btn btn-light flex-fill mx-1 rounded-3 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" 
            data-post-id="{{ $post->id }}">
            <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i> 
            <span class="like-count">{{ $post->likes->count() }}</span>
        </button>

        <!-- Comment -->
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-light flex-fill mx-1 rounded-3">
            <i class="bi bi-chat"></i> {{ $post->comments->count() ?? 0 }}
        </a>
    </div>

<div class="d-flex justify-content-around">
    <!-- Share Dropdown -->
    <div class="dropdown flex-fill mx-1">
        <button class="btn btn-light w-100 rounded-3 dropdown-toggle" type="button" id="shareDropdown{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-share"></i> Share
        </button>
        <ul class="dropdown-menu" aria-labelledby="shareDropdown{{ $post->id }}">
            <!-- Copy Link -->
            <li>
                <a class="dropdown-item copy-link-btn" href="#" data-url="{{ route('posts.show', $post->id) }}">
                    📋 نسخ رابط
                </a>
            </li>

            <!-- WhatsApp -->
            <li>
                <a class="dropdown-item" target="_blank" href="https://wa.me/?text={{ urlencode(route('posts.show', $post->id)) }}">
                    📱 مشاركة على واتساب
                </a>
            </li>

            <!-- Repost -->
            <li>
                <form action="{{ route('posts.store') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="repost_id" value="{{ $post->id }}">
                    <button type="submit" class="dropdown-item text-success">
                        🔄 Repost
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>


    </div>
    @endforeach
</div>
@endsection

@push('styles')
<style>
        /* الكارت الأساسي */
    .post-card {
        max-width: 600px;
        margin: 0 auto 20px;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }
    .post-card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
    }

.post-card img.post-image {
    width: 100%;
    height: 350px; /* حجم ثابت */
    object-fit: cover; /* يقص من الصورة ويخليها مظبوطة */
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.post-card img.post-image:hover {
    transform: scale(1.02);
}


.modal-content img {
    max-height: 90vh;   /* داخل البوب أب */
    object-fit: contain;
}

    .btn-light {
        background: #f8f9fa;
        border: 1px solid #eee;
        transition: 0.2s;
    }

    .btn-light:hover {
        background: #e9ecef;
    }
        /* الأكشنز */
    .post-actions {
        border-top: 1px solid #eee;
        padding: 10px 0;
        font-size: 14px;
    }
    .post-actions .action-btn {
        cursor: pointer;
        transition: color 0.2s ease, transform 0.2s ease;
    }
    .post-actions .action-btn:hover {
        color: #1877f2;
        transform: scale(1.1);
    }

    /* create post */
    .create-post {
        border-radius: 12px;
    }
    .create-post textarea {
        border-radius: 20px;
        resize: none;
    }
        /* النص */
    .post-card p {
        font-size: 15px;
        line-height: 1.5;
    }
    .post-actions .btn-light {
    background: #f8f9fa;
    border: 1px solid #eee;
    transition: 0.2s;
}
.post-actions .btn-light:hover {
    background: #e9ecef;
}
.post-actions .btn-success {
    background: #28a745;
    color: #fff;
    transition: 0.2s;
}
.post-actions .btn-success:hover {
    background: #218838;
}

</style>
@endpush

<script>
document.addEventListener('click', function(e) {
    if (e.target.closest('.copy-link-btn')) {
        e.preventDefault();
        const url = e.target.closest('.copy-link-btn').dataset.url;
        navigator.clipboard.writeText(url);
        // ما فيش alert
    }
});
    // Like button already موجود
    if (e.target.closest('.like-btn')) {
        const btn = e.target.closest('.like-btn');
        const postId = btn.dataset.postId;
        const likeCountSpan = btn.querySelector('.like-count');
        const icon = btn.querySelector('i');

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
                btn.classList.add('text-danger');
                icon.classList.replace('bi-heart', 'bi-heart-fill');
            } else {
                btn.classList.remove('text-danger');
                icon.classList.replace('bi-heart-fill', 'bi-heart');
            }
            likeCountSpan.textContent = data.likesCount;
        })
        .catch(err => console.error(err));
    }
});
</script>

<script>
document.addEventListener('click', function(e) {
    if (e.target.closest('.copy-link-btn')) {
        e.preventDefault();
        const url = e.target.closest('.copy-link-btn').dataset.url;
        navigator.clipboard.writeText(url).then(() => {
            alert('تم نسخ رابط البوست ✅');
        });
    }
});
</script>
