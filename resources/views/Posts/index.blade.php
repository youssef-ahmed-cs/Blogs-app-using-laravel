@extends('Layouts.app')

@section('title', 'Home - FaceBog')

@section('content')
<div class="container-fluid px-0 py-4">
    <div class="row justify-content-center mx-0">
        <div class="col-lg-6 col-md-8 home-feed" style="padding-bottom: 30px; padding-top: 10px;">
            <!-- Main content area -->
            @auth
            <!-- Create Post Modal Trigger -->
            <div class="card mb-4 create-post-card shadow-sm">
                <div class="card-body d-flex align-items-center py-3">
                    <a href="{{ route('profile.public', auth()->id()) }}" class="me-3">
                        <img src="{{ auth()->user()->profile?->profile_image ? asset('storage/'.auth()->user()->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                            alt="Profile" width="42" height="42" class="rounded-circle">
                    </a>
                    <div class="flex-grow-1">
                        <button class="form-control text-start text-muted py-2 px-3" style="border-radius: 25px; cursor: pointer; background-color: #f0f2f5; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#createPostModal">
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
                .modal-content { transition: transform 0.2s ease-in-out; }
                .modal-content:hover { transform: scale(1.02); }
                
                /* Reshared post styles */
                .reshared-post-card {
                    border-left: 3px solid #FF11A7;
                }
                .reshared-post {
                    border-radius: 8px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                    transition: transform 0.3s ease;
                    color: #fff;
                    background: rgba(34, 0, 51, 0.8);
                    border: 1px solid #FF11A7;
                }
                .reshared-post:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 0 15px #FF11A7;
                }
            </style>
            @endpush
            @endauth

            <!-- Posts Feed -->
            @forelse($posts as $post)
            <!-- Individual Post Container -->
            <div class="post-container fade-in-up">
                <div class="card post-card {{ $post->isReshare() ? 'reshared-post-card' : '' }}" id="post-{{ $post->id }}">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('profile.public', $post->user->id) }}" class="text-decoration-none me-2">
                                <img src="{{ $post->user->profile?->profile_image ? asset('storage/'.$post->user->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                                    alt="Profile" width="40" height="40" class="rounded-circle">
                            </a>
                            <div>
                                <h6 class="mb-0 fw-bold">
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
                    @if($post->isReshare() && $post->quote)
                    <p class="mb-3 post-content">{{ $post->quote }}</p>
                    @endif
                    
                    @if($post->isReshare() && $post->originalPost)
                    <!-- Reshared Post -->
                    <div class="reshared-post border rounded p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ route('profile.public', $post->originalPost->user->id) }}" class="text-decoration-none me-2">
                                <img src="{{ $post->originalPost->user->profile?->profile_image ? asset('storage/'.$post->originalPost->user->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                                    alt="Profile" width="30" height="30" class="rounded-circle">
                            </a>
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    <a href="{{ route('profile.public', $post->originalPost->user->id) }}" class="text-decoration-none">
                                        {{ $post->originalPost->user->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $post->originalPost->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        
                        @if($post->originalPost->title)
                        <h6 class="fw-bold mb-2">{{ $post->originalPost->title }}</h6>
                        @endif
                        <p class="mb-3 small">{{ $post->originalPost->description }}</p>

                        @if($post->originalPost->image_post)
                        <div class="post-image-container post-img-container">
                            <img src="{{ asset('storage/'.$post->originalPost->image_post) }}" 
                                 class="img-fluid w-100" alt="Original Post Image">
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- Regular Post -->
                    @if($post->title)
                    <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                    @endif
                    <p class="mb-3 post-content">{{ $post->description }}</p>

                    @if($post->image_post)
                    <div class="post-image-container post-img-container">
                        <img src="{{ asset('storage/'.$post->image_post) }}" 
                             class="img-fluid w-100" alt="Post Image">
                    </div>
                    @endif
                    @endif

                    <!-- Post Stats -->
                    <div class="d-flex justify-content-between align-items-center text-muted my-2 px-1 py-1">
                        <div class="stats-like-count">
                            @if(($post->likes_count ?? 0) > 0)
                                <i class="bi bi-heart-fill text-danger me-1"></i> 
                                <span>{{ $post->likes_count }}</span>
                            @else
                                <span class="text-muted small">Be the first to like this</span>
                            @endif
                        </div>
                        <div>
                            @if($post->comments_count > 0)
                                <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="post-actions d-flex border-top pt-2">
                        @auth
                        <button class="btn like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger active' : '' }}" 
                                data-post-id="{{ $post->id }}">
                            <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                            <span>Like</span>
                        </button>
                        @else
                        <button class="btn require-auth" data-action="like">
                            <i class="bi bi-heart me-2"></i> 
                            <span>Like</span>
                        </button>
                        @endauth

                        <button class="btn {{ !auth()->check() ? 'require-auth' : '' }}" 
                                onclick="toggleComments({{ $post->id }})"
                                data-action="comment">
                            <i class="bi bi-chat me-2"></i> Comment
                        </button>

                        <a href="{{ route('posts.show', $post) }}" class="btn">
                            <i class="bi bi-eye me-2"></i> View
                        </a>
                        
                        <a href="{{ route('posts.show', $post) }}#share" class="btn share-post-btn" data-post-id="{{ $post->id }}">
                            <i class="bi bi-share me-2"></i> Share
                        </a>
                    </div>

                    <!-- Comments Section -->
                    <div id="comments-{{ $post->id }}" class="comments-container p-3 border-top" style="display: none;">
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
            </div>
            @empty
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-journal-richtext fs-1 text-primary mb-4"></i>
                    <h4 class="fw-bold">No posts yet</h4>
                    <p class="text-muted mb-4">When you or your friends post content, you'll see it here.</p>
                    @auth
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createPostModal" class="btn btn-primary px-4 py-2 rounded-pill">
                        <i class="bi bi-plus-lg me-2"></i> Create Your First Post
                    </a>
                    @else
                    <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2 rounded-pill">
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
@endsection

@push('styles')
<style>
/* Comment thread styling */
.reply-thread { border-left: 2px solid #e5e7eb; padding-left: 12px; margin-left: 8px; }
.reply-thread .comment-item { margin-top: 6px; }
.reply-btn {
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
}
.reply-btn:hover {
    color: #0d6efd;
    transform: scale(1.1);
}
.comment-item.animate__animated.animate__fadeIn {
    animation-duration: 0.5s;
}

/* Share button styling */
.share-post-btn {
    color: #65676b;
    transition: all 0.2s ease;
}
.share-post-btn:hover {
    color: #0078ff;
    background-color: rgba(0, 120, 255, 0.1);
}
.share-post-btn i {
    font-size: 1.1rem;
}

/* Loading spinner */
.spin {
    animation: spinner 0.8s linear infinite;
    display: inline-block;
}
@keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Facebook-style post cards */
.post-card {
    margin-bottom: 16px !important;
    border-radius: 8px !important;
    overflow: hidden;
}

.post-card .card-header {
    padding: 12px 15px;
    background-color: white;
    border-bottom: none;
}

.post-card .card-body {
    padding: 0 15px 10px;
}

/* Post actions bar */
.post-actions {
    border-top: 1px solid #f0f2f5;
    padding: 6px 0;
    margin: 8px 0 0;
}

.post-actions .btn {
    border-radius: 6px;
    flex: 1;
    color: #65676b;
    background-color: transparent;
    transition: background-color 0.2s;
    font-weight: 500;
    padding: 8px 0;
}

.post-actions .btn:hover {
    background-color: #f0f2f5;
}

/* Comments section styling */
.comments-container {
    background-color: white !important;
    border-top: 1px solid #f0f2f5;
    border-radius: 0 0 8px 8px;
}

/* Like buttons */
.like-btn.active, .like-btn.text-danger {
    color: #e41e3f !important;
    font-weight: 600;
}

/* Main content column */
.home-feed {
    padding-left: 10px;
    padding-right: 10px;
}

/* Image container */
.post-image-container {
    margin: 8px -16px 10px !important;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    max-height: 500px;
    overflow: hidden;
}
.post-image-container img {
    width: 100%;
    object-fit: cover;
    max-height: 500px;
}

/* Post stats area */
.stats-like-count {
    font-size: 0.9rem;
    padding: 4px 0;
}

/* Improved spacing between posts */
.post-container {
    margin-bottom: 16px !important;
    width: 100%;
    max-width: 680px;
    background-color: transparent;
    padding: 0;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination */
.pagination {
    margin-top: 20px;
    background-color: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

<!-- Share Modal -->
<div class="modal fade" id="sharePostModal" tabindex="-1" aria-labelledby="sharePostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sharePostModalLabel">Share Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="share-preview mb-3 border rounded p-3">
                        <!-- Dynamic content will be loaded here -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="share-options d-flex justify-content-between flex-wrap">
                        <button class="btn btn-outline-primary mb-2 share-platform-btn" data-platform="facebook">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </button>
                        <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="twitter">
                            <i class="bi bi-twitter me-2"></i>Twitter
                        </button>
                        <button class="btn btn-outline-success mb-2 share-platform-btn" data-platform="whatsapp">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </button>
                        <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="telegram">
                            <i class="bi bi-telegram me-2"></i>Telegram
                        </button>
                        <button class="btn btn-outline-secondary mb-2 copy-link-btn" data-platform="copy">
                            <i class="bi bi-clipboard me-2"></i>Copy Link
                        </button>
                    </div>
                    
                    <div class="copy-alert alert alert-success mt-3 d-none">
                        <i class="bi bi-check-circle-fill me-2"></i>Link copied successfully!
                    </div>
                    
                    <div class="share-stats border-top mt-3 pt-3 text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="bi bi-eye me-2"></i>
                            <span id="modal-view-count">0</span>
                            <span class="mx-3">•</span>
                            <i class="bi bi-share me-2"></i>
                            <span id="modal-share-count">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Share Post Functionality
let currentPostId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Add click event for share buttons
    document.querySelectorAll('.share-post-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            currentPostId = postId;
            
            // Show modal
            const shareModal = new bootstrap.Modal(document.getElementById('sharePostModal'));
            shareModal.show();
            
            // Load post preview
            loadPostPreview(postId);
            
            // Update view count
            updateViewCount(postId);
        });
    });
    
    // Share platform buttons
    document.querySelectorAll('#sharePostModal .share-platform-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!currentPostId) return;
            
            const platform = this.getAttribute('data-platform');
            sharePost(currentPostId, platform);
        });
    });
    
    // Copy link button
    const copyButton = document.querySelector('#sharePostModal .copy-link-btn');
    const copyAlert = document.querySelector('#sharePostModal .copy-alert');
    
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            if (!currentPostId) return;
            
            // Copy the post URL to clipboard
            const postUrl = window.location.origin + '/posts/' + currentPostId;
            navigator.clipboard.writeText(postUrl).then(function() {
                copyAlert.classList.remove('d-none');
                setTimeout(() => {
                    copyAlert.classList.add('d-none');
                }, 3000);
                
                // Record the share
                sharePost(currentPostId, 'copy');
            });
        });
    }
});

// Function to load post preview
function loadPostPreview(postId) {
    const previewContainer = document.querySelector('#sharePostModal .share-preview');
    
    fetch(`/posts/${postId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract post information
            const postUser = doc.querySelector('.post-card .card-body a.fw-bold').textContent.trim();
            const postTime = doc.querySelector('.post-card .card-body small.text-muted').textContent.trim();
            const postTitle = doc.querySelector('.post-card .card-body h5')?.textContent.trim();
            const postDesc = doc.querySelector('.post-card .card-body p.mb-2').textContent.trim();
            const postImage = doc.querySelector('.post-card .card-body .post-img img')?.getAttribute('src');
            const userImage = doc.querySelector('.post-card .card-body .rounded-circle')?.getAttribute('src');
            const viewCount = doc.querySelector('#view-count')?.textContent || '0';
            const shareCount = doc.querySelector('#share-count')?.textContent || '0';
            
            // Update modal stats
            document.getElementById('modal-view-count').textContent = viewCount;
            document.getElementById('modal-share-count').textContent = shareCount;
            
            // Build preview HTML
            let previewHTML = `
                <div class="d-flex align-items-center mb-2">
                    <img src="${userImage || 'https://via.placeholder.com/40x40.png?text=U'}" 
                        class="rounded-circle me-2" width="40" height="40" alt="${postUser}">
                    <div>
                        <strong>${postUser}</strong>
                        <div class="text-muted small">${postTime}</div>
                    </div>
                </div>
                <div class="preview-content">
                    ${postTitle ? `<h6 class="mb-1">${postTitle}</h6>` : ''}
                    <p class="small mb-2 text-truncate">${postDesc}</p>
                    ${postImage ? `
                        <div class="text-center border-top pt-2">
                            <img src="${postImage}" class="img-fluid rounded" style="max-height:150px; object-fit:cover;">
                        </div>
                    ` : ''}
                </div>
            `;
            
            previewContainer.innerHTML = previewHTML;
        })
        .catch(error => {
            console.error('Error loading post preview:', error);
            previewContainer.innerHTML = `<div class="alert alert-danger">Error loading preview</div>`;
        });
}

// Function to share post
function sharePost(postId, platform) {
    fetch(`/posts/${postId}/share`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ platform: platform })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update share count in the modal
            const shareCount = document.getElementById('modal-share-count');
            if (shareCount) {
                shareCount.textContent = data.shares;
            }
            
            // For platforms other than "copy", open a new window
            if (platform !== 'copy') {
                window.open(data.url, '_blank', 'width=600,height=400');
            }
        }
    })
    .catch(error => console.error('Error sharing post:', error));
}

// Function to update view count
function updateViewCount(postId) {
    fetch(`/posts/${postId}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const viewCount = document.getElementById('modal-view-count');
            if (viewCount) {
                viewCount.textContent = data.views;
            }
        }
    });
}

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

function updateViewCount(postId) {
    fetch(`/posts/${postId}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
    .then(data => {
        // Update the view count in the UI if needed
        const viewCountElement = document.querySelector(`#view-count-${postId}`);
        if (viewCountElement && data.views) {
            viewCountElement.textContent = data.views;
        }
    });
}
</script>
@endpush