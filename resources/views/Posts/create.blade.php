@extends('Layouts.app')

@section('title', 'Create Post - FaceBog')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <img src="{{ auth()->user()->profile?->profile_image ? asset('storage/'.auth()->user()->profile->profile_image) : 'https://via.placeholder.com/40x40.png?text=U' }}" 
                         alt="Profile" width="40" height="40" class="rounded-circle me-3">
                    <h5 class="mb-0">Create Post</h5>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Title -->
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg border-0" 
                                   name="title" 
                                   placeholder="Add a title (optional)..."
                                   value="{{ old('title') }}">
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <textarea class="form-control border-0" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="What's on your mind, {{ auth()->user()->name }}?"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <input type="file" class="form-control" name="image_post" accept="image/*" id="imageInput">
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="d-flex gap-3">
                                <label for="imageInput" class="btn btn-light d-flex align-items-center">
                                    <i class="bi bi-camera text-success me-2"></i>Photo/Video
                                </label>
                                <button type="button" class="btn btn-light">
                                    <i class="bi bi-emoji-smile text-warning me-2"></i>Feeling/Activity
                                </button>
                                <button type="button" class="btn btn-light">
                                    <i class="bi bi-geo-alt text-danger me-2"></i>Check In
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <div class="d-flex gap-2">
                            <a href="{{ route('home') }}" class="btn btn-secondary flex-fill">Cancel</a>
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-send me-2"></i>Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});
</script>
@endsection
