@extends('Layouts.app')

@section('title', $user->name . ' - Public Profile')

@section('content')
<style>
    .fb-cover-container {
        position: relative;
        background: #f3f4f6;
        border-radius: 0 0 14px 14px;
        overflow: hidden;
        min-height: 210px;
        margin-bottom: 80px; /* Increased to make space for profile pic overlap */
    }
    .fb-cover-img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        background: #eceff1;
        display: block;
    }
    .fb-cover-gradient {
        position: absolute;
        left: 0; right: 0; bottom: 0; height: 50px;
        background: linear-gradient(to top, rgba(0,0,0,0.16) 60%, transparent 100%);
        z-index: 2;
    }
    .fb-cover-action {
        position: absolute;
        right: 24px;
        bottom: 14px;
        z-index: 3;
    }
    .fb-btn-img {
        background: #fff;
        color: #222;
        border: none;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.10);
        transition: background 0.2s;
        cursor: pointer;
    }
    .fb-btn-img:hover {
        background: #f4f4f4;
    }
    
    /* New avatar container to position it properly */
    .fb-profile-avatar-container {
        position: absolute;
        bottom: -60px;
        left: 24px;
        z-index: 10;
    }
    
    .fb-profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 6px 24px rgba(0,0,0,0.10);
        object-fit: cover;
        background: #eee;
    }
    
    .fb-profile-details {
        padding-top: 10px;
        padding-left: 170px; /* Make space for the avatar */
        min-width: 180px;
    }
    
    .fb-profile-name {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.2rem;
    }
    
    .fb-profile-username {
        font-size: 1.1rem;
        color: #65676b;
        margin-bottom: 0.7rem;
    }
    
    .fb-profile-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    
    .profile-tabs {
        border-bottom: 1px solid #dee2e6;
    }
    
    .profile-tabs .nav-link {
        border: none;
        color: #65676b;
        font-weight: 600;
        padding: 12px 16px;
    }
    
    .profile-tabs .nav-link.active {
        border-bottom: 3px solid #1877f2;
        color: #1877f2;
        background: transparent;
    }
    
    @media (max-width: 768px) {
        .fb-profile-avatar-container {
            left: 50%;
            transform: translateX(-50%);
        }
        .fb-profile-details {
            padding-left: 20px;
            padding-top: 70px;
            text-align: center;
        }
        .fb-profile-name { 
            font-size: 1.5rem;
        }
        .fb-profile-actions {
            justify-content: center;
        }
    }
</style>

<div class="container">
    <div class="card border-0 shadow-sm mb-5">
        <!-- Cover Header -->
        <div class="fb-cover-container">
            <img class="fb-cover-img"
                src="{{ $user->profile?->cover_image ? asset('storage/'.$user->profile->cover_image) : asset('images/cover-default.jpg') }}"
                alt="Cover">
            <div class="fb-cover-gradient"></div>
            <div class="fb-cover-action">
                @if(auth()->check() && auth()->id() === $user->id)
                    <form action="{{ route('profile.cover.upload', $user->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                        @csrf
                        <label class="fb-btn-img" style="cursor:pointer;">
                            <i class="bi bi-camera"></i> إضافة صورة غلاف
                            <input type="file" name="cover_image" accept="image/*" onchange="this.form.submit()" style="display:none;">
                        </label>
                    </form>
                @endif
            </div>
            
            <!-- Profile Avatar - Now positioned to overlap -->
            <div class="fb-profile-avatar-container">
                <img class="fb-profile-avatar"
                    src="{{ $user->profile && $user->profile->profile_image ? asset('storage/'.$user->profile->profile_image) : asset('images/default-avatar.png') }}"
                    alt="Profile">
                @if(auth()->check() && auth()->id() === $user->id)
                    <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="avatarForm" style="display:none;">
                        @csrf
                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" onchange="this.form.submit()">
                    </form>
                    <button onclick="document.getElementById('profileImageInput').click();" class="btn btn-light btn-sm rounded-circle position-absolute" style="bottom: 10px; right: 10px;">
                        <i class="bi bi-camera"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Main Info: Name/Username/Actions -->
        <div class="fb-profile-details mb-4">
            <div class="fb-profile-name">{{ $user->name }}</div>
            <div class="fb-profile-username text-muted mb-3">{{ '@'.$user->username }}</div>
            
            <div class="fb-profile-actions">
                <a href="#" data-bs-toggle="modal" data-bs-target="#followersModal" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people"></i> Followers <span class="badge bg-primary">{{ count($followers) }}</span>
                </a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#followingsModal" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-person-lines-fill"></i> Following <span class="badge bg-secondary">{{ count($followings) }}</span>
                </a>
                
                @if(auth()->check() && auth()->id() !== $user->id)
                    @if(auth()->user()->followings->contains($user->id))
                        <form action="{{ route('unfollow', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-person-dash"></i> Unfollow
                            </button>
                        </form>
                    @else
                        <form action="{{ route('follow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-person-plus"></i> Follow
                            </button>
                        </form>
                    @endif
                @endif
                
                @if(auth()->check() && auth()->id() === $user->id)
                    <a href="{{ route('profile.show') }}" class="btn btn-light border">
                        <i class="bi bi-pencil"></i> تعديل الملف الشخصي
                    </a>
                @endif
            </div>
        </div>

        <!-- Tabs for Posts & Comments -->
        <ul class="nav nav-tabs profile-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button" role="tab">Comments</button>
            </li>
        </ul>
        
        <div class="tab-content p-3" id="profileTabContent">
            <!-- Posts Tab -->
            <div class="tab-pane fade show active" id="posts" role="tabpanel">
                @forelse($user->posts as $post)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1">{{ $post->description }}</p>
                                    @if($post->image_post)
                                        <img src="{{ asset('storage/'.$post->image_post) }}" 
                                             class="img-fluid rounded mb-2" 
                                             style="max-height:300px; object-fit:cover;" alt="Post Image">
                                    @endif
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-primary rounded-pill">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No posts yet.</p>
                @endforelse
            </div>
            <!-- Comments Tab -->
            <div class="tab-pane fade" id="comments" role="tabpanel">
                @forelse($user->comments as $comment)
                    <div class="card mb-2 p-2 shadow-sm border-0">
                        <div>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <small class="text-muted">
                                On post: 
                                <a href="{{ route('posts.show', $comment->post->id) }}">
                                    {{ Str::limit($comment->post->description, 30) }}
                                </a>
                            </small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Followers Modal -->
<div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="followersModalLabel">Followers</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @forelse($followers as $follower)
          <div class="d-flex align-items-center mb-2">
            <img src="{{ $follower->profile?->profile_image ? asset('storage/'.$follower->profile->profile_image) : asset('images/default-avatar.png') }}" width="40" height="40" class="rounded-circle me-2">
            <div>
              <a href="{{ route('profile.public', $follower->id) }}">{{ $follower->name }}</a>
              <div class="text-muted small">{{ '@' . $follower->username }}</div>
            </div>
          </div>
        @empty
          <p class="text-muted">No followers yet.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Following Modal -->
<div class="modal fade" id="followingsModal" tabindex="-1" aria-labelledby="followingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="followingsModalLabel">Following</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @forelse($followings as $following)
          <div class="d-flex align-items-center mb-2">
            <img src="{{ $following->profile?->profile_image ? asset('storage/'.$following->profile->profile_image) : asset('images/default-avatar.png') }}" width="40" height="40" class="rounded-circle me-2">
            <div>
              <a href="{{ route('profile.public', $following->id) }}">{{ $following->name }}</a>
              <div class="text-muted small">{{ '@' . $following->username }}</div>
            </div>
          </div>
        @empty
          <p class="text-muted">Not following anyone yet.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection