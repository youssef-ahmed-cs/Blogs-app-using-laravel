<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{route('posts.index')}}">
            <b>{{auth()->user()->name}}</b> Blogs
        </a>

        <!-- Search -->
        <form class="d-flex mx-auto w-50">
            <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
        </form>

        <!-- Right side -->
        <ul class="navbar-nav d-flex align-items-center">
            <!-- Notifications -->
            <li class="nav-item me-3">
                <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
                </a>
            </li>

            <!-- Profile Dropdown -->
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle d-flex align-items-center btn border-0 bg-transparent" 
                        id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" type="button">
                    @if(auth()->user()->profile?->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" 
                             alt="Profile" class="rounded-circle" width="35" height="35">
                    @else
                        <img src="https://via.placeholder.com/35x35.png?text=U" 
                             alt="Profile" class="rounded-circle" width="35" height="35">
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.public', auth()->id()) }}">Public Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('settings.show') }}">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    @yield('content')
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>
</html>
