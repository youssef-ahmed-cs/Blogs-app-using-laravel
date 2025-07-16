<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md m-4 bg-white rounded-xl shadow-lg p-8 space-y-6">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-indigo-700">Login</h2>
        <p class="text-sm text-gray-500 mt-1">Enter your email and password to Login</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <div class="m-3">
            <input type="email" name="email" id="email"
                   class="w-full mb-3 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Email">
            @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="m-3">
            <input type="password" name="password" id="password"
                   class="w-full  px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                   placeholder="Password">
            @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="bg-blue-950 text-black py-2 px-6 rounded-md hover:bg-blue-700 transition duration-200 mx-auto block">
                Log in
            </button>
        </div>
    </form>

    <div class="text-center pt-4 border-t">
        <p class="text-sm text-gray-600">
            Dosen't have an account?
            <a href="{{route('register')}}" class="text-indigo-600 hover:underline font-medium">Sign in</a>
        </p>
    </div>
</div>

</body>
</html>
