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

            <!-- Quote text for reshares -->
            @if($post->isReshare() && $post->quote)
                <p class="mb-3 post-quote">{{ $post->quote }}</p>
            @endif
            
            @if($post->isReshare() && $post->originalPost)
            <!-- Reshared Post -->
            <div class="reshared-post border rounded p-3 mb-3 bg-light">
                <div class="d-flex align-items-center mb-2">
                    <a href="{{ route('profile.public', $post->originalPost->user->id) }}" class="text-decoration-none me-2">
                        <img src="{{ $post->originalPost->user->profile?->profile_image ? asset('storage/'.$post->originalPost->user->profile->profile_image) : asset('images/default-avatar.png') }}" 
                            alt="Profile" width="40" height="40" class="rounded-circle">
                    </a>
                    <div>
                        <a href="{{ route('profile.public', $post->originalPost->user->id) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $post->originalPost->user->name }}
                        </a><br>
                        <small class="text-muted">{{ $post->originalPost->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                @if($post->originalPost->title)
                <h5 class="fw-bold mb-2">{{ $post->originalPost->title }}</h5>
                @endif
                <p class="mb-3">{{ $post->originalPost->description }}</p>

                @if($post->originalPost->image_post)
                <div class="post-img mb-3 text-center">
                    <img src="{{ asset('storage/'.$post->originalPost->image_post) }}" 
                         class="img-fluid rounded" style="max-height:350px; object-fit:cover;">
                </div>
                @endif
            </div>
            @else
            <!-- Regular Post -->
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
            @endif

            <!-- أزرار الإعجاب والتعليقات والمشاركة -->
            <div class="d-flex justify-content-around border-top pt-2 text-muted post-actions">
<button class="btn btn-light p-1 like-btn {{ $post->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" 
        data-post-id="{{ $post->id }}">
    <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
    <span class="like-count">{{ $post->likes->count() }}</span>
</button>


                <button class="btn btn-light p-1">
                    <i class="bi bi-chat"></i> {{ $post->comments->count() }}
                </button>
                <button class="btn btn-light p-1 share-btn" data-bs-toggle="modal" data-bs-target="#shareModal">
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
                @foreach($post->comments->where('parent_id', null) as $comment)
                    <div id="comment-{{ $comment->id }}" class="comment-container">
                        @include('Partials.comment-thread', ['comment' => $comment, 'showReplies' => true])
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.comment-item { padding-left: 0; margin-bottom: 8px; }
.reply-thread { border-left: 2px solid #e5e7eb; padding-left: 12px; margin-left: 8px; }
.reply-thread .comment-item { margin-top: 6px; }
.reply-btn { cursor: pointer; transition: color 0.2s; }
.reply-btn:hover { color: #0d6efd; }

/* Reshared post styling */
.reshared-post {
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}
.reshared-post:hover {
    transform: translateY(-2px);
}
.post-quote {
    font-style: italic;
    padding: 10px 15px;
    border-left: 4px solid #3b5998;
    margin-bottom: 15px;
    background-color: #f0f2f5;
    border-radius: 0 8px 8px 0;
}

/* Share Button Styling */
.share-btn {
    position: relative;
    transition: all 0.2s ease;
}
.share-btn:hover {
    background-color: rgba(0, 120, 255, 0.1) !important;
    color: #0078ff;
}
.share-btn.active {
    color: #0078ff;
    background-color: rgba(0, 120, 255, 0.15) !important;
}

/* Share Modal Styling */
#shareModal .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}
#shareModal .modal-header {
    border-bottom: 1px solid #f0f2f5;
    padding: 16px 20px;
}
#shareModal .modal-body {
    padding: 20px;
}
#shareModal .modal-title {
    font-weight: 600;
    font-size: 1.2rem;
}

/* Share Platform Buttons */
.share-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
    margin-top: 16px;
}
.share-platform-btn, .copy-link-btn {
    border-radius: 8px;
    padding: 10px 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.share-platform-btn:hover, .copy-link-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.share-platform-btn i, .copy-link-btn i {
    font-size: 1.2rem;
}

/* Preview Content */
.share-preview {
    background-color: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}
.preview-content {
    padding: 10px 0;
}
.comment-section .card-body { max-height: 600px; overflow-y: auto; }

/* Comment containers - Facebook-style stacked layout */
.comment-container {
    width: 100%;
    margin-bottom: 16px;
    padding-bottom: 10px;
    display: block;
    border-bottom: 1px solid #f0f2f5;
}

.comment-container:last-child {
    border-bottom: none;
}

/* Facebook-style comment layout */
.comments-list {
    display: flex;
    flex-direction: column;
    width: 100%;
}

/* Add proper spacing between top-level comments */
.comments-list > div:not(:first-child) {
    margin-top: 5px;
}

/* Ensure primary comments don't have right margin */
.comment-item.d-flex {
    margin-right: 0;
}
</style>
@endpush

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">مشاركة المنشور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <!-- Share with Quote/Comment Section -->
                    @auth
                    <div class="mb-3">
                        <div class="quote-input-container">
                            <textarea class="form-control quote-input" placeholder="أضف تعليقًا للمشاركة..." rows="2"></textarea>
                        </div>
                    </div>
                    @endauth
                    
                    <div class="share-preview mb-3 border rounded p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $post->user->profile?->profile_image ? asset('storage/' . $post->user->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                                class="rounded-circle me-2" width="40" height="40" alt="{{ $post->user->name }}">
                            <div>
                                <strong>{{ $post->user->name }}</strong>
                                <div class="text-muted small">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="preview-content">
                            @if($post->title)
                                <h6 class="mb-1">{{ $post->title }}</h6>
                            @endif
                            <p class="small mb-2 text-truncate">{{ $post->description }}</p>
                            @if($post->image_post)
                                <div class="text-center border-top pt-2">
                                    <img src="{{ asset('storage/' . $post->image_post) }}" 
                                        class="img-fluid rounded" style="max-height:150px; object-fit:cover;">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @auth
                    <div class="share-type-tabs mb-3">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="share-external-tab" data-bs-toggle="tab" href="#share-external" role="tab">مشاركة خارجية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="reshare-tab" data-bs-toggle="tab" href="#reshare" role="tab">إعادة مشاركة كمنشور</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div class="tab-pane fade show active" id="share-external" role="tabpanel">
                                <div class="share-options d-flex justify-content-between flex-wrap">
                                    <button class="btn btn-outline-primary mb-2 share-platform-btn" data-platform="facebook" data-post-id="{{ $post->id }}">
                                        <i class="bi bi-facebook me-2"></i>Facebook
                                    </button>
                                    <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="twitter" data-post-id="{{ $post->id }}">
                                        <i class="bi bi-twitter me-2"></i>Twitter
                                    </button>
                                    <button class="btn btn-outline-success mb-2 share-platform-btn" data-platform="whatsapp" data-post-id="{{ $post->id }}">
                                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                    </button>
                                    <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="telegram" data-post-id="{{ $post->id }}">
                                        <i class="bi bi-telegram me-2"></i>Telegram
                                    </button>
                                    <button class="btn btn-outline-secondary mb-2 copy-link-btn" data-platform="copy" data-post-id="{{ $post->id }}" data-bs-toggle="tooltip" data-bs-title="تم نسخ الرابط!">
                                        <i class="bi bi-clipboard me-2"></i>نسخ الرابط
                                    </button>
                                    <div class="copy-alert alert alert-success mt-2 d-none" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i> تم نسخ الرابط بنجاح!
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="reshare" role="tabpanel">
                                <button class="btn btn-primary w-100 reshare-btn" data-post-id="{{ $post->id }}">
                                    <i class="bi bi-arrow-repeat me-2"></i>إعادة مشاركة كمنشور
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="share-options d-flex justify-content-between flex-wrap">
                        <button class="btn btn-outline-primary mb-2 share-platform-btn" data-platform="facebook" data-post-id="{{ $post->id }}">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </button>
                        <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="twitter" data-post-id="{{ $post->id }}">
                            <i class="bi bi-twitter me-2"></i>Twitter
                        </button>
                        <button class="btn btn-outline-success mb-2 share-platform-btn" data-platform="whatsapp" data-post-id="{{ $post->id }}">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </button>
                        <button class="btn btn-outline-info mb-2 share-platform-btn" data-platform="telegram">
                            <i class="bi bi-telegram me-2"></i>Telegram
                        </button>
                        <button class="btn btn-outline-secondary mb-2 copy-link-btn" data-platform="copy">
                            <i class="bi bi-clipboard me-2"></i>نسخ الرابط
                        </button>
                    </div>
                    @endauth
                    
                    <div class="copy-alert alert alert-success mt-3 d-none">
                        <i class="bi bi-check-circle-fill me-2"></i>تم نسخ الرابط بنجاح!
                    </div>
                    
                    <div class="share-stats border-top mt-3 pt-3 text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="bi bi-eye me-2"></i>
                            <span id="view-count">{{ $post->views ?? 0 }}</span>
                            <span class="mx-3">•</span>
                            <i class="bi bi-share me-2"></i>
                            <span id="share-count">{{ $post->shares ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- The reply interactions are handled in the shared partial's script and global handlers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update view count when post is shown
    updateViewCount({{ $post->id }});
    
    // Add copy alert
    const copyAlert = document.querySelector('.copy-alert');
    if (copyAlert) {
        // Create a custom event listener for copy success
        document.addEventListener('copySuccess', function() {
            copyAlert.classList.remove('d-none');
            setTimeout(() => {
                copyAlert.classList.add('d-none');
            }, 3000);
        });
    }
    
    // Set up tooltip for copy link button
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const modal = bootstrap.Modal.getInstance(document.getElementById('shareModal'));
                    modal.hide();
                    
                    // Optionally redirect to the new post
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => console.error('Error resharing post:', error));
        });
    });
    
    // Copy link button
    if (copyButton) {
        copyButton.addEventListener('click', function() {
            // Copy the post URL to clipboard
            const postUrl = window.location.origin + '/posts/{{ $post->id }}';
            navigator.clipboard.writeText(postUrl).then(function() {
                copyAlert.classList.remove('d-none');
                setTimeout(() => {
                    copyAlert.classList.add('d-none');
                }, 3000);
                
                // Record the share
                sharePost({{ $post->id }}, 'copy');
            });
        });
    }
});

// Function to share post
function sharePost(postId, platform) {
    // Send request to record share
    fetch(`/posts/${postId}/share`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        // This is handled in app.blade.php now
    });
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
            const viewCount = document.getElementById('view-count');
            if (viewCount) {
                viewCount.textContent = data.views;
            }
        }
    });
}
</script>
@endpush
