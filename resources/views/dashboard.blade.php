<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->user()->name }}'s Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">

{{-- Navbar --}}
<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white tracking-wide">
            📊 Dashboard
        </a>

        <div class="flex items-center space-x-4">
            <a href="{{ route('posts.index') }}"
               class="flex items-center gap-1 bg-white text-indigo-600 font-medium px-4 py-1.5 rounded-lg hover:bg-gray-100 transition duration-200 shadow">
                Blog
            </a>

            <span class="text-white font-medium">👤 {{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="flex items-center gap-1 bg-red-500 text-white px-4 py-1.5 rounded-lg hover:bg-red-600 transition duration-200 shadow">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 mt-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white shadow-lg rounded-xl p-6 text-center">
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=100"
                alt="Avatar"
                class="mx-auto rounded-full shadow mb-4">

            <h2 class="text-2xl font-semibold text-gray-800">
                Welcome, <span class="text-indigo-600">{{ auth()->user()->name }}</span>!
            </h2>
            <p class="text-gray-600 mt-2">
                Your account is ready. Check your email at <strong
                    class="text-indigo-600">{{ auth()->user()->email }}</strong>.
            </p>

            <div class="mt-6 border-t pt-4">
                <h3 class="text-xl font-medium text-gray-700 mb-3">📈 Your Stats</h3>
                <p class="text-gray-600 space-y-1">
                    <span>You have <strong class="text-purple-700">{{ $count_posts ?? 0 }}</strong> blogs</span><br>
                    <span><strong class="text-purple-700">{{ $count_comments ?? 0 }}</strong> comments</span><br>
                    <span><strong class="text-purple-700">{{ $count_likes ?? 0 }}</strong> likes</span>
                </p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-medium text-gray-700 mb-6 text-center">⚡ Quick Actions</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="{{ route('posts.create') }}"
                   class="block bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium py-3 px-4 rounded-lg hover:scale-105 transition duration-200 text-center shadow">
                    ➕ Create New Post
                </a>
                <a href="{{ route('notifications.index') }}"
                   class="block bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg hover:bg-gray-300 hover:scale-105 transition duration-200 text-center shadow">
                    🔔 Manage Notifications
                </a>
                <a href="#"
                   class="block bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg hover:bg-gray-300 hover:scale-105 transition duration-200 text-center shadow">
                    👀 View Your Profile
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
