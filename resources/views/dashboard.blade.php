<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-bold text-indigo-700">
            Laravel App
        </div>

        <div class="flex items-center space-x-4">
            <span class="text-gray-700">👋 {{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="bg-red-500 text-black px-4 py-1.5 rounded-md hover:bg-red-600 transition duration-200">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 mt-10">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-xl mx-auto">
        <h3 class="text-lg text-gray-800">
            Hi <b class="text-indigo-800">{{ auth()->user()->name }}</b>, your account was created successfully!
        </h3>
    </div>
</div>


</body>
</html>
