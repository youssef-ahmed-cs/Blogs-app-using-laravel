@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <b>FaceBog</b>
        </a>

        <!-- Search -->
        <form class="d-flex mx-auto" style="width: 400px;">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- Right side -->
        <ul class="navbar-nav d-flex align-items-center">
            @auth
                <!-- Add Post Button -->
                <li class="nav-item me-3">
                    <a class="nav-link" href="{{ route('posts.create') }}">
                        <i class="bi bi-plus-circle fs-5"></i>
                    </a>
                </li>

                <!-- Notifications -->
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell fs-5"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(auth()->user()->profile?->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" 
                                 alt="Profile" class="rounded-circle" width="35" height="35">
                        @else
                            <img src="https://via.placeholder.com/35x35.png?text={{ substr(auth()->user()->name, 0, 1) }}" 
                                 alt="Profile" class="rounded-circle" width="35" height="35">
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center">
                                @if(auth()->user()->profile?->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" 
                                         alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                @else
                                    <img src="https://via.placeholder.com/40x40.png?text={{ substr(auth()->user()->name, 0, 1) }}" 
                                         alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ '@' . (auth()->user()->username ?? auth()->user()->name) }}</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.public', auth()->id()) }}">
                            <i class="bi bi-person me-2"></i>Public Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                            <i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.show') }}">
                            <i class="bi bi-sliders me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
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

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

<script>
@auth
// Like functionality for authenticated users
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
            if (data.status === 'liked') {
                btn.classList.add('text-danger');
                icon.classList.replace('bi-heart', 'bi-heart-fill');
            } else {
                btn.classList.remove('text-danger');
                icon.classList.replace('bi-heart-fill', 'bi-heart');
            }
            if (likeCountSpan) {
                likeCountSpan.textContent = data.likesCount;
            }
        })
        .catch(err => console.error(err));
    }
});
@else
// Show auth modal for guests
function requireAuth() {
    const modal = new bootstrap.Modal(document.getElementById('authRequiredModal'));
    modal.show();
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.require-auth')) {
            e.preventDefault();
            e.stopPropagation();
            requireAuth();
        }
    });

    document.addEventListener('focus', function(e) {
        if (e.target.classList.contains('comment-input')) {
            requireAuth();
            e.target.blur();
        }
    }, true);
});
@endauth
</script>

</body>
</html>
