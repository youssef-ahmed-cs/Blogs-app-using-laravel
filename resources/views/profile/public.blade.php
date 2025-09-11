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
        margin-bottom: 20px;
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
    
    .fb-profile-section {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 20px 24px;
        background: #fff;
    }
    
    .fb-profile-avatar-container {
        position: relative;
        flex-shrink: 0;
    }
    
    .fb-profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 3px solid #e4e6ea;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        object-fit: cover;
        background: #eee;
    }
    
    .avatar-camera-btn {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-size: 14px;
    }
    
    .avatar-camera-btn:hover {
        background: #f8f9fa;
    }
    
    .fb-profile-details {
        flex-grow: 1;
        min-width: 0;
    }
    
    .fb-profile-name {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.2rem;
        color: #1c1e21;
    }
    
    .fb-profile-username {
        font-size: 1.1rem;
        color: #65676b;
        margin-bottom: 1rem;
    }
    
    .fb-profile-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    
    .profile-tabs {
        border-bottom: 1px solid #dee2e6;
        background: #fff;
        margin: 0;
    }
    
    .profile-tabs .nav-link {
        border: none;
        color: #65676b;
        font-weight: 600;
        padding: 12px 24px;
    }
    
    .profile-tabs .nav-link.active {
        border-bottom: 3px solid #1877f2;
        color: #1877f2;
        background: transparent;
    }
    
    @media (max-width: 768px) {
        .fb-profile-section {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px 16px;
        }
        
        .fb-profile-avatar {
            width: 110px;
            height: 110px;
        }
        
        .fb-profile-name { 
            font-size: 1.6rem;
            margin-top: 16px;
        }
        
        .fb-profile-actions {
            justify-content: center;
        }
        
        .profile-tabs .nav-link {
            padding: 12px 16px;
        }
    }
</style>

<div class="container">
    <div class="card border-0 shadow-sm mb-5">
        <!-- Cover Header -->
        <div class="fb-cover-container">
            <img class="fb-cover-img"
                src="{{ $user->profile?->cover_image ? asset('storage/'.$user->profile->cover_image) : 'https://via.placeholder.com/800x210/6c757d/ffffff?text=Cover+Photo' }}"
                alt="Cover">
            <div class="fb-cover-gradient"></div>
            <div class="fb-cover-action">
                @if(auth()->check() && auth()->id() === $user->id)
                    <form action="{{ route('profile.cover.upload', $user->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                        @csrf
                        <label class="fb-btn-img" style="cursor:pointer;">
                            <i class="bi bi-camera"></i> Add Cover Photo
                            <input type="file" name="cover_image" accept="image/*" onchange="this.form.submit()" style="display:none;">
                        </label>
                    </form>
                @endif
            </div>
        </div>

        <!-- Profile Section -->
        <div class="fb-profile-section">
            <!-- Profile Avatar -->
            <div class="fb-profile-avatar-container">
                <img class="fb-profile-avatar"
                    src="{{ $user->profile && $user->profile->profile_image ? asset('storage/'.$user->profile->profile_image) : 'https://via.placeholder.com/130x130.png?text='.substr($user->name, 0, 1) }}"
                    alt="Profile">
                @if(auth()->check() && auth()->id() === $user->id)
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm" style="display:none;">
                        @csrf
                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" onchange="this.form.submit()">
                    </form>
                    <button onclick="document.getElementById('profileImageInput').click();" class="avatar-camera-btn">
                        <i class="bi bi-camera"></i>
                    </button>
                @endif
            </div>

            <!-- Profile Details -->
            <div class="fb-profile-details">
                <div class="fb-profile-name">{{ $user->name }}</div>
                <div class="fb-profile-username text-muted mb-3">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</div>
                
                <div class="fb-profile-actions">
                    <!-- Followers Button -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#followersModal" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people"></i> Followers 
                        <span class="badge bg-primary">{{ $followersCount ?? 0 }}</span>
                    </a>

                    <!-- Following Button -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#followingsModal" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person-lines-fill"></i> Following 
                        <span class="badge bg-secondary">{{ $followingsCount ?? 0 }}</span>
                    </a>
                    
                    <!-- Follow/Unfollow Logic -->
                    @if(auth()->check())
                        @if(auth()->id() !== $user->id)
                            @if($isFollowing)
                                <!-- Unfollow Button -->
                                <form action="{{ route('unfollow', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-person-dash"></i> Unfollow
                                    </button>
                                </form>
                            @else
                                <!-- Follow Button -->
                                <form action="{{ route('follow', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-person-plus"></i> Follow
                                    </button>
                                </form>
                            @endif
                        @else
                            <!-- Edit Profile Button for Own Profile -->
                            <a href="{{ route('profile.show') }}" class="btn btn-light border btn-sm">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                        @endif
                    @else
                        <!-- Guest Follow Button - Triggers Auth Modal -->
                        <button class="btn btn-primary btn-sm require-auth" data-action="follow">
                            <i class="bi bi-person-plus"></i> Follow
                        </button>
                        
                        <!-- Message Button for Guests -->
                        <button class="btn btn-outline-primary btn-sm require-auth" data-action="message">
                            <i class="bi bi-chat-dots"></i> Message
                        </button>
                    @endguest

                    <!-- Additional Action Buttons -->
                    @auth
                        @if(auth()->id() !== $user->id)
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-chat-dots"></i> Message
                            </button>
                            <div class="dropdown d-inline">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-flag"></i> Report User
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-person-x"></i> Block User
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Profile Stats -->
                <div class="text-muted small">
                    <span class="me-3">
                        <strong>{{ $posts->count() }}</strong> posts
                    </span>
                    <span class="me-3">
                        <strong>{{ $followersCount ?? 0 }}</strong> followers
                    </span>
                    <span>
                        <strong>{{ $followingsCount ?? 0 }}</strong> following
                    </span>
                </div>
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
                @forelse($posts as $post)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1">
                                    @if($post->title)
                                    <h6 class="card-title">{{ $post->title }}</h6>
                                    @endif
                                    <p class="mb-1">{{ $post->description }}</p>
                                    @if($post->image_post)
                                        <img src="{{ asset('storage/'.$post->image_post) }}" 
                                             class="img-fluid rounded mb-2" 
                                             style="max-height:300px; object-fit:cover;" alt="Post Image">
                                    @endif
                                    
                                    <!-- Post Stats -->
                                    <div class="d-flex gap-3 text-muted small mt-2">
                                        <span><i class="bi bi-heart"></i> {{ $post->likes->count() }}</span>
                                        <span><i class="bi bi-chat"></i> {{ $post->comments->count() }}</span>
                                        <span><i class="bi bi-eye"></i> {{ $post->views ?? 0 }}</span>
                                        <span><i class="bi bi-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-primary rounded-pill">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                        <p class="text-muted">{{ $user->name }} hasn't posted anything yet.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Comments Tab -->
            <div class="tab-pane fade" id="comments" role="tabpanel">
                @forelse($user->comments as $comment)
                    <div class="card mb-2 p-3 shadow-sm border-0">
                        <div>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <small class="text-muted">
                                Commented on: 
                                <a href="{{ route('posts.show', $comment->post->id) }}" class="text-decoration-none">
                                    {{ Str::limit($comment->post->description, 30) }}
                                </a>
                                <span class="mx-2">•</span>
                                {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-chat-square-text fs-1 text-muted mb-3"></i>
                        <p class="text-muted">{{ $user->name }} hasn't commented on any posts yet.</p>
                    </div>
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
        <h5 class="modal-title" id="followersModalLabel">
            <i class="bi bi-people me-2"></i>Followers ({{ $followersCount ?? 0 }})
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(isset($followers) && $followers->count() > 0)
            @foreach($followers as $follower)
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ $follower->profile?->profile_image ? asset('storage/'.$follower->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text='.substr($follower->name, 0, 1) }}" 
                         width="40" height="40" class="rounded-circle me-3">
                    <div>
                        <a href="{{ route('profile.public', $follower->id) }}" class="text-decoration-none fw-semibold">
                            {{ $follower->name }}
                        </a>
                        <div class="text-muted small">{{ '@' . ($follower->username ?? strtolower(str_replace(' ', '', $follower->name))) }}</div>
                    </div>
                </div>
                
                @auth
                    @if(auth()->id() !== $follower->id)
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-person-plus"></i>
                        </button>
                    @endif
                @else
                    <button class="btn btn-outline-primary btn-sm require-auth" data-action="follow">
                        <i class="bi bi-person-plus"></i>
                    </button>
                @endauth
              </div>
            @endforeach
        @else
          <div class="text-center py-4">
              <i class="bi bi-people fs-1 text-muted mb-3"></i>
              <p class="text-muted mb-0">No followers yet.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Following Modal -->
<div class="modal fade" id="followingsModal" tabindex="-1" aria-labelledby="followingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="followingsModalLabel">
            <i class="bi bi-person-lines-fill me-2"></i>Following ({{ $followingsCount ?? 0 }})
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(isset($followings) && $followings->count() > 0)
            @foreach($followings as $following)
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ $following->profile?->profile_image ? asset('storage/'.$following->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text='.substr($following->name, 0, 1) }}" 
                         width="40" height="40" class="rounded-circle me-3">
                    <div>
                        <a href="{{ route('profile.public', $following->id) }}" class="text-decoration-none fw-semibold">
                            {{ $following->name }}
                        </a>
                        <div class="text-muted small">{{ '@' . ($following->username ?? strtolower(str_replace(' ', '', $following->name))) }}</div>
                    </div>
                </div>
                
                @auth
                    @if(auth()->id() !== $following->id)
                        <form action="{{ route('unfollow', $following->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-person-dash"></i>
                            </button>
                        </form>
                    @endif
                @else
                    <button class="btn btn-outline-primary btn-sm require-auth" data-action="follow">
                        <i class="bi bi-person-plus"></i>
                    </button>
                @endauth
              </div>
            @endforeach
        @else
          <div class="text-center py-4">
              <i class="bi bi-person-lines-fill fs-1 text-muted mb-3"></i>
              <p class="text-muted mb-0">Not following anyone yet.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection