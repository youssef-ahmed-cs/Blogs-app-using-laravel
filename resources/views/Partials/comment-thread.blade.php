<div class="comment-item d-flex mb-2 p-2 shadow-sm rounded animate__animated animate__fadeIn">
    <a href="{{ route('profile.public', $comment->user->id) }}">
        <img src="{{ $comment->user->profile?->profile_image ? asset('storage/'.$comment->user->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
             class="rounded-circle me-2" width="32" height="32">
    </a>
    <div class="flex-grow-1">
        <div>
            <strong>{{ $comment->user->name }}</strong>
            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
        <p class="mb-1">{{ $comment->content }}</p>

        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-link text-primary reply-btn">Reply</button>
            @can('delete', $comment)
            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline ms-2">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
            @endcan
        </div>

        <!-- Reply Form -->
        <form action="{{ route('comments.store') }}" method="POST" class="reply-form mt-2 d-none">
            @csrf
            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <input type="text" name="content" class="form-control form-control-sm" placeholder="اكتب ردك...">
            <button type="submit" class="btn btn-sm btn-success mt-1">رد</button>
        </form>

        <!-- Replies -->
        @if($comment->replies)
        <div class="reply-thread mt-2 ms-4">
            @foreach($comment->replies as $reply)
                @include('partials.comment-thread', ['comment' => $reply])
            @endforeach
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.reply-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const form = btn.closest('.comment-item').querySelector('.reply-form');
            form.classList.toggle('d-none');
            form.querySelector('input[name="content"]').focus();
        });
    });
});
</script>
@endpush
