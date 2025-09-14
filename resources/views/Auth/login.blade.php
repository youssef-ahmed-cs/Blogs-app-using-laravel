@extends('Layouts.auth')

@section('content')
    <h2>Login</h2>
    
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            @error('email')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>
        </div>
    </form>

    <div class="bottom-links">
        <p>
            Don't have an account?
            <a href="{{ route('register') }}">Sign up</a>
        </p>
    </div>
@endsection
