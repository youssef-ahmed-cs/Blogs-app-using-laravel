@extends('Layouts.app')

@section('title', 'Home - FaceBog')

@push('styles')
<style>
    /* Custom styles for post index page */
    .post-image-container {
        margin: 0 -15px;
        overflow: hidden;
        max-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .post-image-container img {
        object-fit: cover;
        width: 100%;
        max-height: 500px;
    }
    
    .create-post-card {
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 20px;
    }
    
    .create-post-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .comment-input {
        background-color: #f0f2f5;
        border-radius: 20px !important;
    }
    
    .like-btn.text-danger {
        color: #ff4d4f !important;
    }
    
    .like-btn.text-danger i {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
@auth
<!-- Create Post Modal Trigger -->
<div class="card mb-4 create-post-card shadow-sm">
    <div class="card-body d-flex align-items-center py-3">
        <img src="{{ auth()->user()->profile?->profile_image 
            ? asset('storage/'.auth()->user()->profile->profile_image) 
            : 'https://via.placeholder.com/40x40.png?text=U' }}" 
            alt="Profile" width="42" height="42" class="rounded-circle me-3">

        <div class="flex-grow-1">
            <button class="form-control text-start text-muted py-2 px-3" 
                    style="border-radius: 25px; cursor: pointer; background-color: #f0f2f5; border-color: transparent;"
                    data-bs-toggle="modal" data-bs-target="#createPostModal">
                What's on your mind, {{ auth()->user()->name }}?
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
            <div class="card mb-4 shadow-sm" id="post-{{ $post->id }}">
                <div class="card-header bg-white border-0">
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

                <div class="card-body pb-1">
                    @if($post->title)
                    <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                    @endif
                    <p class="mb-3">{{ $post->description }}</p>

                    @if($post->image_post)
                    <div class="post-image-container">
                        <img src="{{ asset('storage/'.$post->image_post) }}" 
                             class="img-fluid w-100 rounded" alt="Post Image">
                    </div>
                    @endif

                    <!-- Post Stats -->
                    <div class="d-flex justify-content-between align-items-center text-muted my-2 px-2 py-1">
                        <div class="stats-like-count">
                            @if(($post->likes_count ?? 0) > 0)
                                <i class="bi bi-heart-fill text-danger me-1"></i> 
                                <span>{{ $post->likes_count }} {{ $post->likes_count == 1 ? 'like' : 'likes' }}</span>
                            @else
                                <span class="text-muted small">Be the first to like this</span>
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
                    <div class="post-actions d-flex border-top pt-2 px-1 py-1">
                        @auth
                        <button class="btn btn-light like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" 
                                data-post-id="{{ $post->id }}">
                            <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            <span>Like</span> 
                            <span class="like-count ms-1">{{ $post->likes->count() > 0 ? '('.$post->likes->count().')' : '' }}</span>
                        </button>
                        @else
                        <button class="btn btn-light require-auth" data-action="like">
                            <i class="bi bi-heart"></i> 
                            <span>Like</span>
                            <span class="like-count ms-1">{{ $post->likes_count > 0 ? '('.$post->likes_count.')' : '' }}</span>
                        </button>
                        @endauth

                        <button class="btn btn-light {{ !auth()->check() ? 'require-auth' : '' }}" 
                                onclick="toggleComments({{ $post->id }})"
                                data-action="comment">
                            <i class="bi bi-chat"></i> Comment
                        </button>

                        <a href="{{ route('posts.show', $post) }}" class="btn btn-light">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>

                    <!-- Comments Section -->
                    <div id="comments-{{ $post->id }}" class="comments-container p-3 border-top bg-light" style="display: none;">
                        @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="d-flex">
                                <img src="{{ auth()->user()->profile?->profile_image ? asset('storage/'.auth()->user()->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
                                     alt="Profile" width="32" height="32" class="rounded-circle me-2">
                                <div class="flex-grow-1">
                                    <input type="text" name="content" class="form-control rounded-pill comment-input" 
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
                        <div class="recent-comments-container">
                            @foreach($post->comments->where('parent_id', null)->take(2) as $comment)
                                @include('Partials.comment-thread', ['comment' => $comment, 'showReplies' => false])
                            @endforeach
                            
                            @if($post->comments->count() > 2)
                                <div class="text-center mt-2">
                                    <a href="{{ route('posts.show', $post) }}" class="text-primary view-more-comments">
                                        <i class="bi bi-chat-text me-1"></i> View all {{ $post->comments->count() }} comments
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-journal-richtext fs-1 text-primary mb-4"></i>
                    <h4 class="fw-bold">No posts yet</h4>
                    <p class="text-muted mb-4">When you or your friends post content, you'll see it here.</p>
                    @auth
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createPostModal" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-plus-lg me-2"></i> Create Your First Post
                    </a>
                    @else
                    <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-person-plus me-2"></i> Join FaceBog
                    </a>
                    @endauth
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="d-flex justify-content-center my-4">
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
    const commentsContainer = document.getElementById(`comments-${postId}`);
    const commentBtn = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
    
    if (commentsContainer.style.display === 'none' || !commentsContainer.style.display) {
        // Show loading spinner in button
        if (commentBtn) {
            const originalButtonText = commentBtn.innerHTML;
            commentBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-1"></i> Loading...';
            commentBtn.disabled = true;
            
            // Add active class
            commentBtn.classList.add('active', 'bg-light');
        }
        
        // Show comments with animation after small delay (simulates loading)
        setTimeout(() => {
            commentsContainer.style.display = 'block';
            commentsContainer.classList.add('animate__animated', 'animate__fadeIn');
            
            // Reset button
            if (commentBtn) {
                commentBtn.innerHTML = '<i class="bi bi-chat"></i> Comment';
                commentBtn.disabled = false;
            }
            
            // Focus on comment input if user is authenticated
            const commentInput = commentsContainer.querySelector('input[name="content"]');
            if (commentInput) {
                setTimeout(() => {
                    commentInput.focus();
                }, 300);
            }
        }, 500);
    } else {
        // Hide comments with animation
        commentsContainer.classList.add('animate__animated', 'animate__fadeOut');
        
        setTimeout(() => {
            commentsContainer.style.display = 'none';
            commentsContainer.classList.remove('animate__animated', 'animate__fadeOut');
        }, 300);
        
        // Remove active class from the comment button
        if (commentBtn) {
            commentBtn.classList.remove('active', 'bg-light');
        }
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
            
            // Get post_id from the form's action URL or hidden input
            const postId = form.querySelector('input[name="post_id"]') 
                ? form.querySelector('input[name="post_id"]').value 
                : form.action.match(/\/posts\/(\d+)\/comments/)[1];
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

// Like functionality with AJAX
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const postId = this.dataset.postId;
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('.like-count');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/posts/${postId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.liked) {
                        this.classList.add('text-danger');
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                    } else {
                        this.classList.remove('text-danger');
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                    }
                    countSpan.textContent = data.likes_count;
                }
            })
            .catch(err => console.error(err));
        });
    });

    // Add active class to comment button when comments are visible
    document.addEventListener('click', function(e) {
        if (e.target.closest('button[onclick^="toggleComments"]')) {
            const btn = e.target.closest('button[onclick^="toggleComments"]');
            const postId = btn.getAttribute('onclick').match(/toggleComments\((\d+)\)/)[1];
            const commentsContainer = document.getElementById(`comments-${postId}`);
            
            if (commentsContainer.style.display === 'none' || !commentsContainer.style.display) {
                // Remove active from all other comment buttons
                document.querySelectorAll('button[onclick^="toggleComments"]').forEach(otherBtn => {
                    if (otherBtn !== btn) {
                        otherBtn.classList.remove('active', 'bg-light');
                    }
                });
            }
        }
    });
    
    // Submit comment with Enter key
    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });
});
</script>

<style>
    /* Enhanced Comments Section Styling */
    .comments-container {
        background-color: #f8f9fa;
        border-radius: 0 0 8px 8px;
        transition: all 0.3s ease;
    }
    
    .comment-item {
        margin-bottom: 8px;
        padding: 8px;
        border-radius: 12px;
        background: #fff;
        transition: transform 0.2s ease;
    }
    
    .comment-item:hover {
        background: #f0f2f5;
        transform: translateX(2px);
    }
    
    .comment-content {
        padding: 8px 12px;
        background: #f0f2f5;
        border-radius: 18px;
        display: inline-block;
    }
    
    .view-more-comments {
        text-decoration: none;
        font-size: 0.9rem;
        color: #4267B2 !important;
        padding: 5px 10px;
        border-radius: 16px;
        background-color: #e6eaf0;
        transition: all 0.2s ease;
        display: inline-block;
    }
    
    .view-more-comments:hover {
        background-color: #dfe3ea;
        transform: translateY(-2px);
    }
    
    .btn.active {
        background-color: #e6eaf0 !important;
        font-weight: 500;
    }
    
    /* Animation for comments */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate__fadeIn {
        animation-duration: 0.3s;
    }
    
    .animate__fadeOut {
        animation-duration: 0.2s;
    }
    
    /* Spinner animation */
    .spin {
        animation: spinner 0.8s linear infinite;
        display: inline-block;
    }
    
    @keyframes spinner {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

<!-- Global Scripts - Place outside the push blocks -->
<script>
// Toggle Comments
function toggleComments(postId) {
    const commentsContainer = document.getElementById(`comments-${postId}`);
    const commentBtn = document.querySelector(`button[onclick="toggleComments(${postId})"]`);
    
    if (commentsContainer.style.display === 'none' || !commentsContainer.style.display) {
        // Show loading spinner in button
        if (commentBtn) {
            const originalButtonText = commentBtn.innerHTML;
            commentBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-1"></i> Loading...';
            commentBtn.disabled = true;
            
            // Add active class
            commentBtn.classList.add('active', 'bg-light');
        }
        
        // Show comments with animation after small delay (simulates loading)
        setTimeout(() => {
            commentsContainer.style.display = 'block';
            commentsContainer.classList.add('animate__animated', 'animate__fadeIn');
            
            // Reset button
            if (commentBtn) {
                commentBtn.innerHTML = '<i class="bi bi-chat"></i> Comment';
                commentBtn.disabled = false;
            }
            
            // Focus on comment input if user is authenticated
            const commentInput = commentsContainer.querySelector('input[name="content"]');
            if (commentInput) {
                setTimeout(() => {
                    commentInput.focus();
                }, 300);
            }
        }, 500);
    } else {
        // Hide comments with animation
        commentsContainer.classList.add('animate__animated', 'animate__fadeOut');
        
        setTimeout(() => {
            commentsContainer.style.display = 'none';
            commentsContainer.classList.remove('animate__animated', 'animate__fadeOut');
        }, 300);
        
        // Remove active class from the comment button
        if (commentBtn) {
            commentBtn.classList.remove('active', 'bg-light');
        }
    }
}
</script>
@endsection