<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
    <!-- العنوان -->
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-indigo-700">Create Account</h2>
        <p class="text-sm text-gray-500 mt-1">Enter your email and password to register</p>
    </div>

    <!-- النموذج -->
    <form method="POST" action="{{ route('post.register') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="example@mail.com">
            @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="••••••••">
            @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full bg-indigo-700 text-white py-2 rounded-md hover:bg-indigo-800 transition duration-200 font-medium">
                Sign up
            </button>
        </div>
    </form>

    <!-- Footer -->
    <div class="text-center pt-4 border-t">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="#" class="text-indigo-600 hover:underline font-medium">Sign in</a>
        </p>
    </div>
</div>

</body>
</html>
