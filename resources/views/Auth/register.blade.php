@extends('Layouts.auth')

@section('content')
    <h2>Create Account</h2>
    
    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

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
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            @error('password_confirmation')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100">
                Sign Up
            </button>
        </div>
    </form>

    <div class="bottom-links">
        <p>
            Already have an account?
            <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
@endsection
