<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="container mx-auto px-4">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Registration Form</h2>
            <p class="text-sm text-gray-500">Enter your details to register</p>
        </div>

        <form method="POST" action="{{ route('post.register') }}" class="space-y-4">
            @csrf

            <div>
                <input type="text" name="name" id="name"
                       class="w-full px-4 py-2 border mb-3 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Name">
            </div>
            @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror

            <div>
                <input type="email" name="email" id="email"
                       class="w-full px-4 py-2 border mb-3 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Email">
            </div>
            @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror

            <div>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2 border mb-3 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Password">
            </div>
            @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror

            <div>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-2 border mb-3 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Confirm Password">
            </div>
            @error('password_confirmation')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror

            <div>
                <button type="submit"
                        class="bg-blue-950 text-black py-2 px-6 rounded-md hover:bg-blue-700 transition duration-200 mx-auto block">
                    Sign up
                </button>
            </div>
        </form>

        <div class="mt-6 border-t pt-4 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{route('login')}}" class="text-blue-600 hover:underline">Login</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
