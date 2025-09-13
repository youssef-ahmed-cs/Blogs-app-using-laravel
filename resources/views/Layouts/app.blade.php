
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @yield('meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/retro-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-fixes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/show-post-fixes.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <style>
    /* Global styles */
    body {
        background-color: #0f1726; /* Dark background color */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        overflow-x: hidden;
    }

    /* Ensure dropdown is visible and properly styled */
    .navbar-nav .dropdown-menu {
        display: none; /* Hidden by default */
        position: absolute;
        top: 100%;
        right: 0;
        left: auto;
        z-index: 1000;
        min-width: 280px;
        padding: 8px 0;
        margin: 8px 0 0;
        background-color: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }
    .navbar-nav .dropdown-menu.show {
        display: block !important; /* Force show when active */
    }
    .navbar-nav .dropdown-toggle::after { display: none; }

    /* Profile picture hover effect */
    .navbar-nav .nav-link.dropdown-toggle {
        padding: 4px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .navbar-nav .nav-link.dropdown-toggle:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: scale(1.05);
    }

    /* Dropdown header styling */
    .navbar-nav .dropdown-header {
        padding: 0.5rem 1.5rem;
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.8;
        margin-bottom: 0;
    }

    /* Facebook-style post cards - modernized */
    .post-container {
        margin-bottom: 16px !important;
        width: 100%;
        background-color: transparent;
        padding: 0;
    }
    .post-card {
        border-radius: 8px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        border: 1px solid rgba(255,255,255,0.2);
        margin-bottom: 0 !important;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
        backdrop-filter: blur(5px); /* Apply blur effect behind the card */
        transition: none;
    }
    .post-card .card-header {
        padding: 12px 16px;
        background-color: transparent;
        border-bottom: 1px solid rgba(240, 242, 245, 0.5);
    }
    .post-card .card-body {
        padding: 12px 16px 10px;
        background-color: transparent;
    }
    .post-actions {
        border-top: 1px solid #f0f2f5;
        display: flex;
        justify-content: space-around;
        padding: 8px 0;
        margin-top: 5px;
    }
    .post-actions .btn {
        flex: 1;
        border-radius: 6px;
        color: #65676b;
        background-color: transparent;
        font-weight: 500;
        padding: 8px 0;
        transition: background-color 0.2s;
    }
    .post-actions .btn:hover {
        background-color: #f0f2f5;
    }
    .comments-container {
        background: #fff;
        border-top: 1px solid #f0f2f5;
        padding: 12px 16px;
        border-radius: 0 0 8px 8px;
    }
    .comment-item { margin-bottom: 10px; }
    .comment-input {
        background-color: #f0f2f5;
        border-radius: 20px !important;
        padding: 8px 16px;
        border-color: transparent;
    }
    .like-btn.active,
    .like-btn.text-danger {
        color: #e41e3f !important;
        font-weight: 600;
    }

    /* Action buttons styling (global) */
    .btn-light {
        background-color: #f0f2f5;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        padding: 8px 16px;
        transition: background-color 0.2s;
        flex: 1;
    }
    .btn-light:hover { background-color: #e4e6e9; }
    .btn-light.active {
        background-color: #e7f3ff;
        color: #1877f2;
    }

    /* Post actions (global) */
    .post-actions {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-top: 1px solid rgba(240, 242, 245, 0.5);
        margin-top: 8px;
    }
    
    /* Transparent fluid container */
    div.container-fluid {
        background-color: rgba(173, 216, 230, 0.5); /* Light blue transparent background */
    }
    
    /* Transparent post card styling */
    .transparent-post-card {
        background-color: transparent !important;
        backdrop-filter: blur(0px) !important;
        border: none !important;
        box-shadow: none !important;
    }
    .post-actions .btn {
        flex: 1;
        margin: 0 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .post-actions .btn i {
        margin-right: 6px;
        font-size: 1.1rem;
    }

    /* Navbar dropdown items */
    .navbar-nav .dropdown-item {
        padding: 12px 20px;
        font-size: 14px;
        color: #374151;
        transition: all 0.2s ease;
        border-radius: 6px;
        margin: 2px 8px;
    }
    .navbar-nav .dropdown-item:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #1f2937;
        transform: translateX(4px);
    }
    .navbar-nav .dropdown-item i {
        width: 18px;
        margin-right: 12px;
        opacity: 0.7;
    }
    .navbar-nav .dropdown-divider {
        margin: 8px 16px;
        border-color: #e5e7eb;
    }

    .navbar-nav .dropdown { position: relative; }
    .navbar { z-index: 1030; position: relative; }

    /* =====================
       Comment & Reply styles
       ===================== */
    .comment-item {
        background: transparent;
        box-shadow: none;
        padding: 4px 0;
        position: relative;
        margin-bottom: 10px;
        width: 100%;
    }
    .comments-list { display: flex; flex-direction: column; width: 100%; }
    .comments-list > div { width: 100%; }

    .comment-item .comment-content {
        background: #ffffff;
        border: 1px solid #eef0f3;
        border-radius: 10px;
        padding: 10px 14px;
        display: inline-block;
        max-width: 100%;
        margin-bottom: 2px;
    }
    .comment-actions { margin-top: 4px; margin-bottom: 8px; }

    .reply-thread {
        position: relative;
        margin-left: 20px; 
        padding-left: 18px;
    }
    .reply-thread::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 2px;
        background-color: #cad1da;
    }
    .reply-thread .comment-item::before {
        content: '';
        position: absolute;
        left: -18px;
        top: 16px;
        width: 18px;
        height: 2px;
        background-color: #cad1da;
    }
    .reply-thread .comment-item { margin-bottom: 12px; }
    .reply-thread .comment-item:last-child::after {
        content: '';
        position: absolute;
        left: -18px;
        top: 17px;
        bottom: -10px;
        width: 2px;
        background-color: #f0f2f5;
    }

    .reply-thread .reply-thread::before { background-color: #d8dfe8; }
    .reply-thread .reply-thread .reply-thread::before { background-color: #e3e8ee; }
    .reply-thread .reply-thread .comment-item::before { background-color: #d8dfe8; }
    .reply-thread .reply-thread .reply-thread .comment-item::before { background-color: #e3e8ee; }
    .reply-thread .comment-item .comment-content { background: #f8f9fa; }

    .comment-header { display: flex; align-items: center; margin-bottom: 2px; }
    .comment-header strong { font-weight: 600; color: #2a2a2a; }

    .reply-btn, .btn-link {
        cursor: pointer;
        transition: color 0.2s, transform 0.2s;
    }
    .reply-btn:hover, .btn-link:hover {
        color: #0d6efd;
        transform: scale(1.05);
    }

    .spin { animation: spinner 0.8s linear infinite; display: inline-block; }
    @keyframes spinner { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }

    .comments-container, .comment-section .card-body {
        max-height: none;
        overflow-y: visible;
    }
    .comments-section-scroll {
        max-height: 800px;
        overflow-y: auto;
    }

    .comments-list > div {
        width: 100%;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #f0f2f5;
    }
    .comments-list > div:last-child { border-bottom: none; }
    .card-body.comment-body { padding-bottom: 20px; }
    
    /* Modern Facebook-style post styling */
    .post-container {
        margin-bottom: 16px !important;
        width: 100%;
        background-color: transparent;
        padding: 0;
    }
    
    .post-card {
        border-radius: 8px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
        border: 1px solid rgba(0,0,0,0.04);
        margin-bottom: 0 !important;
        overflow: hidden;
        background-color: #fff;
        transition: none;
    }
    
    .post-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        transform: translateY(-5px);
    }
    
    /* Main content container - full width */
    .container.py-4 {
        max-width: 100%;
        padding: 16px 0 !important;
        background-color: #f0f2f5;
    }
    
    /* Transparent container styling */
    div.container-fluid.px-0.py-4 {
        background-color: transparent;
    }
    
    /* Post image container styling */
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
    
    /* Create post card */
    .create-post-card {
        border-radius: 8px;
        transition: none;
        margin-bottom: 16px;
        background-color: white;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
    }
    
    /* Share Modal Styling */
    #shareModal .modal-content, 
    #sharePostModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    #shareModal .modal-header,
    #sharePostModal .modal-header {
        border-bottom: 1px solid #f0f2f5;
        padding: 16px 20px;
    }
    #shareModal .modal-body,
    #sharePostModal .modal-body {
        padding: 20px;
    }
    #shareModal .modal-title,
    #sharePostModal .modal-title {
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

    /* Animated gradient background */
body {
    background: linear-gradient(135deg, #667eea, #764ba2, #f6d365, #fda085);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    overflow-x: hidden;
}

/* Floating circles */
.bg-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    pointer-events: none;
    animation: floatCircles linear infinite;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes floatCircles {
    0% { transform: translateY(0) translateX(0); opacity: 0.5; }
    50% { transform: translateY(-300px) translateX(150px); opacity: 1; }
    100% { transform: translateY(0) translateX(0); opacity: 0.5; }
}

</style>
@stack('styles')

    @stack('styles')
</head>
<body class="{{ auth()->check() ? 'user-logged-in' : 'user-guest' }} modern-bg">

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <b>FaceBog</b>
        </a>

        {{-- <!-- Search -->
        <form class="d-flex mx-auto" style="width: 400px;">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Search FaceBog..." aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form> --}}

        <!-- Right side -->
        <ul class="navbar-nav d-flex align-items-center">
            @auth
                <!-- Add Post Button -->

<li class="nav-item me-3">
    <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
        <i class="bi bi-bell fs-5"></i>
        @php
            $unreadCount = auth()->user()->unreadNotifications()->count() ?? 0;
        @endphp
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </a>
</li>



                <!-- Profile Dropdown - FIXED VERSION -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" 
                       id="navbarDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       data-bs-display="static"
                       aria-expanded="false">
                        @if(auth()->user()->profile?->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" 
                                 alt="Profile" class="rounded-circle" width="35" height="35">
                        @else
                            <img src="https://via.placeholder.com/35x35/667eea/ffffff?text={{ substr(auth()->user()->name, 0, 1) }}" 
                                 alt="Profile" class="rounded-circle" width="35" height="35">
                        @endif
                    </a>
                    
                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- Profile Header -->
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center">
                                @if(auth()->user()->profile?->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" 
                                         alt="Profile" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <img src="https://via.placeholder.com/50x50/667eea/ffffff?text={{ substr(auth()->user()->name, 0, 1) }}" 
                                         alt="Profile" class="rounded-circle me-3" width="50" height="50">
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ '@' . (auth()->user()->username ?? strtolower(str_replace(' ', '', auth()->user()->name))) }}</small>
                                </div>
                            </div>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.public', auth()->id()) }}">
                                <i class="bi bi-person"></i>Public Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-gear"></i>Profile Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.show') }}">
                                <i class="bi bi-sliders"></i>Settings
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <!-- Logout -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button class="dropdown-item text-danger w-100 text-start border-0 bg-transparent" type="submit">
                                    <i class="bi bi-box-arrow-right"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <!-- Guest Navigation -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary text-white px-3" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Sign Up
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <!-- Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<!-- Auth Required Modal for Guests -->
@guest
<div class="modal fade" id="authRequiredModal" tabindex="-1" aria-labelledby="authRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="bg-primary text-white text-center py-5 px-4" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;">
                    <i class="bi bi-globe2 display-4 mb-3"></i>
                    <h2 class="fw-bold mb-3">Welcome to FaceBog!</h2>
                    <p class="mb-0 fs-5">Connect with friends, share your thoughts, and explore what's happening around the world.</p>
                </div>
                
                <div class="p-5 text-center">
                    <div class="d-flex gap-3 justify-content-center mb-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow-sm">
                            <i class="bi bi-person-plus me-2"></i>Join Now
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4 py-3 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                        </a>
                    </div>
                    
                    <div class="text-muted small">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Sign in here</a></p>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
        </div>
    </div>
</div>
@endguest

<!-- Debug Script to Test Dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Test if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS is not loaded!');
        alert('Bootstrap JS is not loaded. Dropdown will not work.');
    } else {
        console.log('Bootstrap JS loaded successfully');
    }
    
    // Add click event listener to dropdown toggle for debugging
    const dropdownToggle = document.getElementById('navbarDropdown');
    if (dropdownToggle) {
        console.log('Dropdown toggle found');
        
    }
});

@auth
// Share functionality for all pages
document.addEventListener('click', function(e) {
    // Handle share platform buttons
    if (e.target.closest('.share-platform-btn')) {
        const btn = e.target.closest('.share-platform-btn');
        const platform = btn.getAttribute('data-platform');
        const postId = btn.getAttribute('data-post-id');
        
        if (postId && platform) {
            e.preventDefault();
            sharePost(postId, platform);
        }
    }
    
    // Handle copy link button
    if (e.target.closest('.copy-link-btn')) {
        const btn = e.target.closest('.copy-link-btn');
        const postId = btn.getAttribute('data-post-id');
        
        if (postId) {
            e.preventDefault();
            const postUrl = window.location.origin + '/posts/' + postId;
            navigator.clipboard.writeText(postUrl).then(function() {
                // Show copy success message using tooltip or custom event
                if (btn.hasAttribute('data-bs-toggle') && btn.getAttribute('data-bs-toggle') === 'tooltip') {
                    const tooltip = bootstrap.Tooltip.getInstance(btn);
                    if (tooltip) {
                        tooltip.show();
                        setTimeout(() => tooltip.hide(), 2000);
                    } else {
                        const newTooltip = new bootstrap.Tooltip(btn);
                        newTooltip.show();
                        setTimeout(() => newTooltip.hide(), 2000);
                    }
                }
                
                // Trigger custom event for copy alert if exists
                document.dispatchEvent(new CustomEvent('copySuccess'));
                
                // Record the share
                sharePost(postId, 'copy');
            });
        }
    }
    
    // Handle reshare button
    if (e.target.closest('.reshare-btn')) {
        const btn = e.target.closest('.reshare-btn');
        const postId = btn.getAttribute('data-post-id');
        
        if (postId) {
            e.preventDefault();
            const quoteInput = document.querySelector('.quote-input');
            const quoteText = quoteInput ? quoteInput.value : '';
            
            // Send request to reshare post
            fetch(`/posts/${postId}/reshare`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quote: quoteText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('تم إعادة مشاركة المنشور بنجاح!');
                    
                    // Close modal if exists
                    const modal = document.getElementById('shareModal');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    }
                    
                    // Redirect to the new post if redirect URL is provided
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Error resharing post:', error);
                alert('حدث خطأ أثناء إعادة مشاركة المنشور');
            });
        }
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
        body: JSON.stringify({ platform: platform })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update share count if displayed
            const shareCount = document.getElementById('share-count');
            if (shareCount) {
                shareCount.textContent = data.shares;
            }
            
            // For platforms other than "copy", open appropriate share URL
            if (platform !== 'copy') {
                let shareUrl = '';
                const postUrl = window.location.origin + '/posts/' + postId;
                const postTitle = document.title || 'Check out this post!';
                
                switch (platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(postUrl)}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(postUrl)}&text=${encodeURIComponent(postTitle)}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(postTitle + ' ' + postUrl)}`;
                        break;
                    case 'telegram':
                        shareUrl = `https://t.me/share/url?url=${encodeURIComponent(postUrl)}&text=${encodeURIComponent(postTitle)}`;
                        break;
                }
                
                if (shareUrl) {
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                }
            }
        }
    })
    .catch(error => console.error('Error sharing post:', error));
}

// Like functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.like-btn')) {
        e.preventDefault();

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
            if (data.liked) {
                btn.classList.add('text-danger');
                icon.classList.replace('bi-heart', 'bi-heart-fill');
            } else {
                btn.classList.remove('text-danger');
                icon.classList.replace('bi-heart-fill', 'bi-heart');
            }
            if (likeCountSpan) {
                likeCountSpan.textContent = data.likes_count;
            }
        })
        .catch(err => console.error(err));
    }
});

// Global delegated handler for reply form submissions (AJAX)
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.matches('form.reply-form')) {
        e.preventDefault();
        const input = form.querySelector('input[name="content"]');
        const content = (input?.value || '').trim();
        if (!content) return;

        // Add loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        submitBtn.disabled = true;
        
        // Extract post id from action URL: /posts/{id}/comments
        const action = form.action || '';
        const m = action.match(/\/posts\/(\d+)\/comments/i);
        if (!m) return;
        const postId = m[1];
        const parentId = form.querySelector('input[name="parent_id"]').value;

        fetch(`/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content, parent_id: parentId })
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
            if (data && data.status === 'success') {
                const parentItem = form.closest('.comment-item');
                let thread = parentItem.querySelector('.reply-thread');
                if (!thread) {
                    thread = document.createElement('div');
                    thread.className = 'reply-thread';
                    parentItem.querySelector('.flex-grow-1').appendChild(thread);
                }

                // Create comment element
                const div = document.createElement('div');
                div.id = `comment-${data.comment.id}`;
                div.className = 'comment-item d-flex mb-2 animate__animated animate__fadeIn';
                
                // Create full HTML structure with proper nesting for Reddit-style threads
                div.innerHTML = `
                    <a href="${data.comment.user_profile}" class="me-2">
                        <img src="${data.comment.user_image}" class="rounded-circle" width="32" height="32" alt="avatar">
                    </a>
                    <div class="flex-grow-1">
                        <div class="comment-content">
                            <div class="comment-header">
                                <strong>${data.comment.user_name}</strong>
                                <small class="text-muted ms-2">Just now</small>
                            </div>
                            <p class="mb-1"></p>
                        </div>
                        <div class="comment-actions mt-1">
                            <div class="d-flex align-items-center gap-3">
                                <button class="btn btn-sm btn-link text-primary p-0 reply-btn">
                                    <small><i class="bi bi-reply me-1"></i>Reply</small>
                                </button>
                                <form action="/comments/${data.comment.id}" method="POST" class="d-inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <button class="btn btn-sm btn-link text-danger p-0" type="submit">
                                        <small><i class="bi bi-trash me-1"></i>Delete</small>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <form action="/posts/${postId}/comments" method="POST" class="reply-form mt-2 d-none animate__animated animate__fadeIn">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="parent_id" value="${data.comment.id}">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <input type="text" name="content" class="form-control form-control-sm rounded-pill" placeholder="Write a reply..." required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary ms-2 rounded-circle"><i class="bi bi-send"></i></button>
                            </div>
                        </form>
                        <div class="reply-thread"></div>
                    </div>
                `;
                div.querySelector('.comment-content p').textContent = data.comment.content;
                thread.appendChild(div);

                // Show a success notification
                const notification = document.createElement('div');
                notification.className = 'alert alert-success alert-dismissible fade show animate__animated animate__fadeIn';
                notification.setAttribute('role', 'alert');
                notification.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i> Reply posted successfully
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Add notification at the top of the comment section
                const commentSection = document.querySelector('.comment-section') || document.querySelector('.comments-container');
                if (commentSection && commentSection.firstChild) {
                    commentSection.insertBefore(notification, commentSection.firstChild);
                    
                    // Auto-dismiss after 3 seconds
                    setTimeout(() => {
                        notification.classList.add('animate__fadeOut');
                        setTimeout(() => notification.remove(), 500);
                    }, 3000);
                }

                // Reset form
                input.value = '';
                form.classList.add('d-none');
            }
        })
        .catch(err => {
            console.error('Failed to submit reply', err);
            // Show error message
            const errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger mt-2 small py-2 animate__animated animate__fadeIn';
            errorMsg.textContent = 'Failed to post reply. Please try again.';
            form.appendChild(errorMsg);
            
            // Remove after 3 seconds
            setTimeout(() => {
                errorMsg.classList.add('animate__fadeOut');
                setTimeout(() => errorMsg.remove(), 500);
            }, 3000);
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalBtnHtml;
            submitBtn.disabled = false;
        });
    }
});

// Delegated toggle for reply forms
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.reply-btn');
    if (!btn) return;
    
    const item = btn.closest('.comment-item');
    const form = item?.querySelector('.reply-form');
    if (!form) return;
    
    // Hide other open forms within the same thread to reduce clutter
    const allOpenForms = document.querySelectorAll('.reply-form:not(.d-none)');
    allOpenForms.forEach(f => {
        if (f !== form) {
            f.classList.remove('animate__fadeIn');
            f.classList.add('animate__fadeOut');
            setTimeout(() => {
                f.classList.remove('animate__fadeOut');
                f.classList.add('d-none');
            }, 300);
        }
    });
    
    // Toggle this form with animation
    if (form.classList.contains('d-none')) {
        form.classList.remove('d-none');
        form.classList.add('animate__fadeIn');
        const input = form.querySelector('input[name="content"]');
        input && setTimeout(() => input.focus(), 300);
    } else {
        form.classList.remove('animate__fadeIn');
        form.classList.add('animate__fadeOut');
        setTimeout(() => {
            form.classList.remove('animate__fadeOut');
            form.classList.add('d-none');
        }, 300);
    }
});

// Submit on Enter for reply inputs
document.addEventListener('keydown', function(e) {
    const input = e.target;
    if (input.matches && input.matches('form.reply-form input[name="content"]')) {
        if (e.key === 'Enter' && input.value.trim() !== '') {
            e.preventDefault();
            input.closest('form').requestSubmit();
        }
    }
});
@endauth

@guest
// For guest users, set up interaction listeners
document.addEventListener('DOMContentLoaded', function() {
    setupGuestInteractions();
});
@endguest
</script>

<!-- Include guest interactions and comment handlers JS -->
<script src="{{ asset('js/guest-interactions.js') }}"></script>
<script src="{{ asset('js/comment-handlers.js') }}"></script>

@guest
<!-- Authentication Card - Shows for guest users when trying to interact -->
<div id="auth-required-card" class="card auth-required-card shadow" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 400px; z-index: 1050;">
    <div class="card-body text-center py-5">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="hideAuthCard()"></button>
        <i class="bi bi-lock fs-1 text-primary mb-3"></i>
        <h5>Authentication Required</h5>
        <p class="text-muted">Please login or create an account to interact with posts and users.</p>
        <div class="d-grid gap-2 col-12 col-md-8 mx-auto">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Create Account</a>
        </div>
    </div>
</div>

<!-- Overlay background -->
<div id="auth-overlay" class="auth-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1040;"></div>

<script>
function showAuthCard() {
    document.getElementById('auth-required-card').style.display = 'block';
    document.getElementById('auth-overlay').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function hideAuthCard() {
    document.getElementById('auth-required-card').style.display = 'none';
    document.getElementById('auth-overlay').style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
}

// Close the card when clicking on the overlay
document.getElementById('auth-overlay').addEventListener('click', hideAuthCard);
</script>
@endguest

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Background Animation Script -->
<script src="{{ asset('js/background-animation.js') }}"></script>

<!-- Post card animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply animations to post cards
    const postCards = document.querySelectorAll('.post-card');
    postCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in-up');
        }, index * 100);
    });
});
</script>

<!-- Initialize Bootstrap Dropdowns -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Debug: Check if bootstrap is loaded
    console.log('Bootstrap version:', bootstrap.Dropdown.VERSION);
});


</script>

<!-- Retro Theme Toggle Button -->
<button id="retro-theme-toggle" title="Toggle Retro Theme">
    <i class="bi bi-stars"></i>
</button>

<!-- Grid Background for Retro Theme -->
<div class="grid-bg"></div>

<!-- Stacked Scripts -->
@stack('scripts')

<script>
// Set retro theme as default unless explicitly disabled
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('retroTheme') !== 'disabled') {
        document.body.classList.add('retro-theme');
        document.getElementById('retro-theme-toggle').innerHTML = '<i class="bi bi-sun"></i>';
        localStorage.setItem('retroTheme', 'enabled');
    }
});

// Toggle retro theme
document.getElementById('retro-theme-toggle').addEventListener('click', function() {
    if (document.body.classList.contains('retro-theme')) {
        document.body.classList.remove('retro-theme');
        localStorage.setItem('retroTheme', 'disabled');
        this.innerHTML = '<i class="bi bi-stars"></i>';
    } else {
        document.body.classList.add('retro-theme');
        localStorage.setItem('retroTheme', 'enabled');
        this.innerHTML = '<i class="bi bi-sun"></i>';
    }
});

// Setup AJAX CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
</body>
</html>