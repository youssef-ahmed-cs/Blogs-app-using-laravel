@extends('Layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">My Profile</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4 text-center">
                <div class="mb-3">
                    @if($user->profile && $user->profile->profile_image)
                        <img src="{{ asset('storage/'.$user->profile->profile_image) }}" class="rounded-circle mb-3" width="100" height="100" alt="Profile" style="object-fit: cover;">
                    @else
                        <img src="/images/default-avatar.png" class="rounded-circle mb-3" width="100" height="100" alt="Profile" style="object-fit: cover; background-color: #f8f9fa;">
                    @endif
                </div>
                <div>
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Choose a new profile image (optional)</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection
