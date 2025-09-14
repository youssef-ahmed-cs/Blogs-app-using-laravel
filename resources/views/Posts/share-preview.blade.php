@extends('Layouts.app')

@section('title', isset($post->title) ? $post->title : 'Post Preview')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('profile.public', $post->user->id) }}">
                            <img src="{{ $post->user->profile?->profile_image 
                                ? asset('storage/' . $post->user->profile->profile_image) 
                                : asset('images/default-avatar.png') }}" 
                                 class="rounded-circle me-2" width="50" height="50" alt="{{ $post->user->name }}">
                        </a>
                        <div>
                            <a href="{{ route('profile.public', $post->user->id) }}" class="fw-bold text-dark text-decoration-none">
                                {{ $post->user->name ?? 'User' }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    @if($post->title)
                        <h4 class="mb-3">{{ $post->title }}</h4>
                    @endif

                    <p>{{ $post->description }}</p>

                    @if($post->image_post)
                        <div class="post-img mb-3 text-center">
                            <img src="{{ asset('storage/' . $post->image_post) }}" 
                                 class="img-fluid rounded" style="max-height: 500px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="stats text-muted mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-heart"></i> {{ $post->likes->count() }} likes</span>
                            <span><i class="bi bi-chat"></i> {{ $post->comments->count() }} comments</span>
                            <span><i class="bi bi-eye"></i> {{ $post->views }} views</span>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-primary px-4">
                            View Post
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                            Browse More Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('meta')
<!-- Open Graph Meta Tags for Social Sharing -->
<meta property="og:title" content="{{ $post->title ?? 'Post on FaceBog' }}">
<meta property="og:description" content="{{ Str::limit($post->description, 150) }}">
<meta property="og:url" content="{{ route('posts.show', $post) }}">
@if($post->image_post)
<meta property="og:image" content="{{ asset('storage/' . $post->image_post) }}">
@endif
<meta property="og:type" content="article">
<meta property="og:site_name" content="FaceBog">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->title ?? 'Post on FaceBog' }}">
<meta name="twitter:description" content="{{ Str::limit($post->description, 150) }}">
@if($post->image_post)
<meta name="twitter:image" content="{{ asset('storage/' . $post->image_post) }}">
@endif
@endsection