
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <style>
        /* Global styles */
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        .navbar-nav .dropdown-toggle::after {
            display: none; /* Hide default arrow */
        }

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
        
        /* Facebook-style post cards */
        .card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: white;
            padding: 12px 15px;
        }
        
        .card-body {
            padding: 15px;
        }
        
        /* Action buttons styling */
        .btn-light {
            background-color: #f0f2f5;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 16px;
            transition: background-color 0.2s;
            flex: 1;
        }
        
        .btn-light:hover {
            background-color: #e4e6e9;
        }
        
        .btn-light.active {
            background-color: #e7f3ff;
            color: #1877f2;
        }
        
        /* Action buttons container */
        .post-actions {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-top: 1px solid #f0f2f5;
            margin-top: 8px;
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

        /* Debug: Add red border to test if dropdown exists */
        .navbar-nav .dropdown {
            position: relative;
        }

        /* Make sure navbar has proper z-index */
        .navbar {
            z-index: 1030;
            position: relative;
        }
    </style>
</head>
<body class="{{ auth()->check() ? 'user-logged-in' : 'user-guest' }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <b>FaceBog</b>
        </a>

        <!-- Search -->
        <form class="d-flex mx-auto" style="width: 400px;">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Search FaceBog..." aria-label="Search">
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
@endauth

@guest
// For guest users, set up interaction listeners
document.addEventListener('DOMContentLoaded', function() {
    setupGuestInteractions();
});
@endguest
</script>

<!-- Include guest interactions JS -->
<script src="{{ asset('js/guest-interactions.js') }}"></script>

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

<!-- Stacked Scripts -->
@stack('scripts')
</body>
</html>