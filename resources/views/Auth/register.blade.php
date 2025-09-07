<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-indigo-100 via-white to-indigo-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-3xl font-extrabold text-indigo-700">Create Account</h2>
        <p class="text-sm text-gray-500">Enter your details to register</p>
    </div>

    <form method="POST" action="{{ route('post.register') }}" class="space-y-4">
        @csrf

        <div>
            <input type="text" name="name" id="name"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Full Name" {{old('name')}}>
            @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <input type="email" name="email" id="email"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Email" {{old('email')}}>
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
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Confirm Password">
            @error('password_confirmation')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-semibold transition duration-200 shadow-md">
                Sign up
            </button>
        </div>
    </form>

    <div class="mt-6 border-t pt-4 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Login</a>
        </p>
    </div>
</div>

</body>
</html>
