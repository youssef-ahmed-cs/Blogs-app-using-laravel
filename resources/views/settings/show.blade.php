@extends('Layouts.app')

@section('title', 'Settings')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Account Settings</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h6>Change Password</h6>
        <form action="{{ route('settings.updatePassword') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
                @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>

        <h6>Danger Zone</h6>
        <form action="{{ route('settings.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>
</div>
@endsection
