@extends('layouts.app')

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

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 text-center">
@if($user->profile && $user->profile->profile_image)
    <img src="{{ asset('storage/'.$user->profile->profile_image) }}" class="rounded-circle mb-3" width="100" height="100" alt="Profile">
@else
    <img src="https://via.placeholder.com/100x100.png?text=U" class="rounded-circle mb-3" alt="Profile">
@endif
                <input type="file" name="profile_image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Bio</label>
                <input type="text" name="bio" class="form-control" value="{{ old('bio', $user->bio) }}">
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection
