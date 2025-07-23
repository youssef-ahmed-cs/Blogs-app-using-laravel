<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-white">
            Laravel Blogs
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{ route('posts.index') }}"
               class="flex items-center gap-1 bg-white text-indigo-600 font-medium px-4 py-1.5 rounded-md hover:bg-gray-100 transition duration-200 shadow-sm">
                Blog
            </a>

            <span class="text-white font-medium">👋 {{ auth()->user()->name }}</span>

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
    <div class="bg-white shadow-lg rounded-xl p-6 max-w-xl mx-auto text-center">
        <div class="flex justify-center mb-4">
            <div
                class="w-16 h-16 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-full text-3xl shadow">
                👤
            </div>
        </div>
        <h3 class="text-lg text-gray-700">
            Hi <b class="text-indigo-700">{{ auth()->user()->name }}</b>,
            your account was created successfully!
            <br>
            Check your Email: <b class="text-indigo-700">{{ auth()->user()->email }}</b>
            <br>
            You have <b class="text-purple-700">{{ auth()->user()->posts->count() }}</b> blog(s) 🎉
        </h3>
    </div>
</div>

</body>
</html>
