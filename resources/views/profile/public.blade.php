@extends('Layouts.app')

@section('title', $user->name . ' - Public Profile')

@section('content')
<div class="card shadow-sm">
    <div class="card-body text-center">

        <!-- صورة -->
        @if($user->profile && $user->profile->profile_image)
            <img src="{{ asset('storage/'.$user->profile->profile_image) }}" 
                 class="rounded-circle mb-3" width="120" height="120" alt="Profile">
        @else
            <img src="https://via.placeholder.com/120x120.png?text=U" 
                 class="rounded-circle mb-3" alt="Profile">
        @endif

        <!-- الاسم واليوزر -->
        <h3>{{ $user->name }}</h3>
        <p class="text-muted">{{ '@'.$user->username }}</p>

        <!-- البايو -->
        <p>{{ $user->profile?->bio ?? 'No bio available.' }}</p>

        <!-- روابط التواصل -->
        <div class="mb-3">
            @if($user->profile?->twitter)
                <a href="{{ $user->profile->twitter }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-twitter"></i> Twitter
                </a>
            @endif
            @if($user->profile?->website)
                <a href="{{ $user->profile->website }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-link-45deg"></i> Website
                </a>
            @endif
        </div>

        <hr>

        <!-- البوستات -->
        <h5 class="mt-3">Posts</h5>
        @forelse($user->posts as $post)
            <div class="card mb-2 p-2 shadow-sm text-start">
                <h6>{{ $post->title }}</h6>
                <p>{{ Str::limit($post->body, 150) }}</p>
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-primary">Read More</a>
            </div>
        @empty
            <p class="text-muted">No posts yet.</p>
        @endforelse

        <!-- الكومنتات -->
        <h5 class="mt-4">Comments</h5>
        @forelse($user->comments as $comment)
            <div class="card mb-2 p-2 shadow-sm text-start">
                <p>{{ $comment->body }}</p>
                <small class="text-muted">On post: {{ $comment->post->title ?? 'Deleted Post' }}</small>
            </div>
        @empty
            <p class="text-muted">No comments yet.</p>
        @endforelse

    </div>
</div>
@endsection
