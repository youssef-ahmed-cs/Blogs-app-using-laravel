@extends('Layouts.app')

@section('title', $user->name . ' - Public Profile')

@section('content')
<div class="profile-page card shadow-sm">

    <!-- كفر -->
    <div class="cover-photo bg-primary" style="height:200px;"></div>

    <div class="card-body text-center">
        <!-- صورة البروفايل -->
        <div class="profile-image-wrapper" style="margin-top:-60px;">
            @if($user->profile && $user->profile->profile_image)
                <img src="{{ asset('storage/'.$user->profile->profile_image) }}" 
                     class="rounded-circle border border-3 border-white" 
                     width="120" height="120" alt="Profile">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" 
                     class="rounded-circle border border-3 border-white" 
                     width="120" height="120" alt="Profile">
            @endif
        </div>

        <!-- الاسم واليوزر -->
        <h3 class="mt-2">{{ $user->name }}</h3>
        <p class="text-muted">{{ '@'.$user->username }}</p>

        <!-- البايو -->
        <p>{{ $user->profile?->bio ?? 'No bio available.' }}</p>

        <!-- روابط -->
        <div class="mb-3">
            @if($user->profile?->twitter)
                <a href="{{ $user->profile->twitter }}" target="_blank" class="btn btn-outline-primary btn-sm me-1">
                    <i class="bi bi-twitter"></i> Twitter
                </a>
            @endif
            @if($user->profile?->website)
                <a href="{{ $user->profile->website }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-link-45deg"></i> Website
                </a>
            @endif
        </div>
    </div>

    <hr>

    <!-- البوستات -->
    <div class="card-body">
        <h5 class="mb-3">Posts</h5>
        @forelse($user->posts as $post)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p>{{ $post->description }}</p>

                    @if($post->image_post)
                        <img src="{{ asset('storage/'.$post->image_post) }}" 
                             class="img-fluid rounded mb-2" 
                             style="max-height:400px; object-fit:cover;" alt="Post Image">
                    @endif

                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">View Post</a>
                </div>
            </div>
        @empty
            <p class="text-muted">No posts yet.</p>
        @endforelse
    </div>

    <hr>

    <!-- الكومنتات -->
    <div class="card-body">
        <h5 class="mb-3">Comments</h5>
        @forelse($user->comments as $comment)
            <div class="card mb-2 p-2 shadow-sm">
                <p>{{ $comment->content }}</p>
                <small class="text-muted">
                    On post: 
                    <a href="{{ route('posts.show', $comment->post->id) }}">
                        {{ Str::limit($comment->post->description, 30) }}
                    </a>
                </small>
            </div>
        @empty
            <p class="text-muted">No comments yet.</p>
        @endforelse
    </div>
</div>
@endsection
