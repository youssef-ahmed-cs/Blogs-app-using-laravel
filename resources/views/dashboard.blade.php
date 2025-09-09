<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->user()->name }}'s Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white">
            Dashboard
        </a>

        <div class="flex items-center space-x-4">
            <a href="{{ route('posts.index') }}"
               class="flex items-center gap-1 bg-white text-indigo-600 font-medium px-4 py-1.5 rounded-md hover:bg-gray-100 transition duration-200 shadow-sm">
                Blog
            </a>

            <span class="text-white font-medium"> {{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="flex items-center gap-1 bg-red-500 text-white px-4 py-1.5 rounded-md hover:bg-red-600 transition duration-200 shadow-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 mt-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white shadow-lg rounded-xl p-6 text-center">
            <h2 class="text-2xl font-semibold text-gray-800">
                Welcome, <span class="text-indigo-600">{{ auth()->user()->name }}</span>!
            </h2>
            <p class="text-gray-600 mt-2">
                Your account is ready. Check your email at <strong
                    class="text-indigo-600">{{ auth()->user()->email }}</strong>.
            </p>
            <div class="mt-6 border-t pt-4">
                <h3 class="text-xl font-medium text-gray-700 mb-3">Your Stats</h3>
                <p class="text-gray-600">
                    You have <strong class="text-purple-700">{{ $count_posts ?? 0 }}</strong> blogs,
                    <strong class="text-purple-700">{{ $count_comments ?? 0 }}</strong> comments, and
                    <strong class="text-purple-700">{{ $count_likes ?? 0 }}</strong> likes.
                </p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6">
            <h3 class="text-xl font-medium text-gray-700 mb-4 text-center">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('posts.create') }}"
                   class="block bg-indigo-500 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-600 transition duration-200 text-center">
                    Create New Post
                </a>
                <a href="{{ route('notifications.index') }}"
                   class="block bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-md hover:bg-gray-300 transition duration-200 text-center">
                    Manage Notifications
                </a>
                <a href="#"
                   class="block bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-md hover:bg-gray-300 transition duration-200 text-center">
                    View Your Profile
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
