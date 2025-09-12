@extends('Layouts.app')

@section('title', 'Home - FaceBog')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
@auth
<!-- Create Post Modal Trigger -->
<div class="card mb-4">
    <div class="card-body d-flex align-items-center">
        <img src="{{ auth()->user()->profile?->profile_image 
            ? asset('storage/'.auth()->user()->profile->profile_image) 
            : 'https://via.placeholder.com/40x40.png?text=U' }}" 
            alt="Profile" width="40" height="40" class="rounded-circle me-3">

        <div class="flex-grow-1">
            <button class="form-control text-start text-muted p-3" 
                    style="border-radius: 25px; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#createPostModal">
                ماذا تفكر، {{ auth()->user()->name }}؟
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg animate__animated animate__fadeInDown">
            <div class="modal-header border-0">
                <h5 class="modal-title">إنشاء بوست جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="text" name="title" class="form-control mb-2" placeholder="عنوان البوست" required>
                    <textarea name="description" class="form-control mb-2 rounded-3" rows="3" placeholder="بماذا تفكر؟"></textarea>
                    <input type="file" name="image_post" class="form-control mt-2">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">نشر</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .modal-content {
        transition: transform 0.2s ease-in-out;
    }
    .modal-content:hover {
        transform: scale(1.02);
    }
</style>
@endpush
@endauth

            <!-- Posts Feed -->
            @forelse($posts as $post)
            <div class="card mb-4" id="post-{{ $post->id }}">
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
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                    id="dropdownMenuButton{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $post->id }}">
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
                        <div class="stats-like-count">
                            @if(($post->likes_count ?? 0) > 0)
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

                    <!-- Action Buttons -->
                    <div class="d-flex border-top pt-2">
                        @auth
<button class="btn btn-light p-1 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" 
        data-post-id="{{ $post->id }}">
    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
    <span class="like-count">{{ $post->likes->count() }}</span>
</button>


                        @else
                        <button class="btn btn-light flex-fill me-2 require-auth" data-action="like">
                            <i class="bi bi-heart"></i> 
                            <span>Like</span>
                            <span class="like-count ms-1">({{ $post->likes_count }})</span>
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
<!-- Recent Comments & Replies -->
@foreach($post->comments->take(3) as $comment)
<div class="comment-item mb-2">
    <div class="d-flex align-items-start">
        <img src="{{ $comment->user->profile?->profile_image ? asset('storage/'.$comment->user->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
             alt="Profile" width="32" height="32" class="rounded-circle me-2">
        <div class="flex-grow-1">
            <div class="bg-light rounded p-2">
                <strong>{{ $comment->user->name }}</strong>
                <p class="mb-0">{{ $comment->content }}</p>
            </div>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            
            <!-- Reply Button -->
            @auth
            <button class="btn btn-sm btn-link reply-btn text-primary mt-1">Reply</button>

            <!-- Reply Form (hidden by default) -->
            <form action="{{ route('comments.store', $post) }}" method="POST" class="reply-form d-none mt-1">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="d-flex">
                    <input type="text" name="content" class="form-control form-control-sm rounded-pill me-2" placeholder="Write a reply..." required>
                    <button type="submit" class="btn btn-primary btn-sm">Send</button>
                </div>
            </form>
            @endauth

            <!-- Reply Thread -->
            <div class="reply-thread ms-4 mt-2"></div>
        </div>
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
@push('styles')
<style>
.reply-btn {
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
}
.reply-btn:hover {
    color: #0d6efd;
    transform: scale(1.1);
}
.reply-thread .comment-item {
    border-left: 2px solid #ddd;
    padding-left: 10px;
    margin-top: 8px;
}
.comment-item.animate__animated.animate__fadeIn {
    animation-duration: 0.5s;
}
</style>
@endpush
@push('scripts')
<script>
function toggleComments(postId) {
    const commentsDiv = document.getElementById('comments-' + postId);
    if (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') {
        commentsDiv.style.display = 'block';
    } else {
        commentsDiv.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function(){
    // Reply Button Toggle
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            const form = btn.closest('.comment-item').querySelector('.reply-form');
            form.classList.toggle('d-none');
            form.querySelector('input[name="content"]').focus();
        });
    });

    // AJAX Reply Submission
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

// // Like functionality - Fixed version
// document.addEventListener('DOMContentLoaded', function() {
//     document.querySelectorAll('.like-btn').forEach(button => {
//         button.addEventListener('click', function(e) {
//             e.preventDefault();

//             const postId = this.dataset.postId;
//             const icon = this.querySelector('i');
//             const countSpan = this.querySelector('span');
//             const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//             fetch(`/posts/${postId}/toggle-like`, {
//                 method: 'POST',
//                 headers: {
//                     'X-CSRF-TOKEN': csrfToken,
//                     'Accept': 'application/json'
//                 }
//             })
//             .then(res => res.json())
//             .then(data => {
//                 if (data.success) {
//                     if (data.liked) {
//                         this.classList.add('text-danger');
//                         icon.classList.remove('bi-heart');
//                         icon.classList.add('bi-heart-fill');
//                     } else {
//                         this.classList.remove('text-danger');
//                         icon.classList.remove('bi-heart-fill');
//                         icon.classList.add('bi-heart');
//                     }
//                     countSpan.textContent = data.likes_count;
//                 }
//             });
//         });
//     });
// });

</script>
@endpush
@endsection