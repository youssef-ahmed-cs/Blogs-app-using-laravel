<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-indigo-100 via-white to-indigo-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md m-4 bg-white rounded-2xl shadow-xl p-8 space-y-6 border border-gray-200">
    <div class="text-center space-y-2">
        <h2 class="text-3xl font-extrabold text-indigo-700">Login</h2>
        <p class="text-sm text-gray-500">Enter your email and password to log in</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf

        <div>
            <input type="email" name="email" id="email"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Email">
            @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <input type="password" name="password" id="password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Password">
            @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-semibold transition duration-200 shadow-md">
                Log in
            </button>
        </div>
    </form>

    <div class="text-center pt-4 border-t">
        <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Sign up</a>
        </p>
    </div>
</div>

</body>
</html>
