<div class="comment-item d-flex mb-2 p-2 shadow-sm rounded animate__animated animate__fadeIn">
    <a href="{{ route('profile.public', $comment->user->id) }}" class="me-2">
        <img src="{{ $comment->user->profile?->profile_image ? asset('storage/'.$comment->user->profile->profile_image) : 'https://via.placeholder.com/32x32.png?text=U' }}" 
             class="rounded-circle" width="32" height="32">
    </a>
    <div class="flex-grow-1">
        <div class="comment-content">
            <div>
                <strong>{{ $comment->user->name }}</strong>
            </div>
            <p class="mb-1">{{ $comment->content }}</p>
        </div>
        <div class="mt-1">
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>

            <div class="d-flex align-items-center">
                @if(!isset($hideActions) || !$hideActions)
                    @auth
                        <button class="btn btn-sm btn-link text-primary p-0 me-3 reply-btn">
                            <small><i class="bi bi-reply me-1"></i>Reply</small>
                        </button>
                        @can('delete', $comment)
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-link text-danger p-0" type="submit">
                                <small><i class="bi bi-trash me-1"></i>Delete</small>
                            </button>
                        </form>
                        @endcan
                    @else
                        <button class="btn btn-sm btn-link text-primary p-0 require-auth" data-action="comment-reply">
                            <small><i class="bi bi-reply me-1"></i>Reply</small>
                        </button>
                    @endauth
                @endif
            </div>

        <!-- Reply Form -->
        <form action="{{ route('comments.store', $comment->post_id) }}" method="POST" class="reply-form mt-2 d-none animate__animated animate__fadeIn">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <input type="text" name="content" class="form-control form-control-sm rounded-pill" 
                           placeholder="Write a reply..." required>
                </div>
                <button type="submit" class="btn btn-sm btn-primary ms-2 rounded-circle">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </form>

        <!-- Replies -->
        @if($comment->replies && (!isset($showReplies) || $showReplies))
        <div class="reply-thread mt-2 ms-4">
            @foreach($comment->replies as $reply)
                @include('Partials.comment-thread', ['comment' => $reply, 'showReplies' => false])
            @endforeach
        </div>
        @elseif($comment->replies && $comment->replies->count() > 0 && isset($showReplies) && !$showReplies)
        <div class="mt-1">
            <a href="{{ route('posts.show', $comment->post_id) }}#comment-{{ $comment->id }}" class="view-replies text-decoration-none">
                <small class="text-primary">
                    <i class="bi bi-chat-text me-1"></i> View {{ $comment->replies->count() }} {{ $comment->replies->count() == 1 ? 'reply' : 'replies' }}
                </small>
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Reply button functionality
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            // Hide all other reply forms first
            document.querySelectorAll('.reply-form:not(.d-none)').forEach(openForm => {
                if (openForm !== btn.closest('.comment-item').querySelector('.reply-form')) {
                    openForm.classList.add('d-none');
                }
            });
            
            // Toggle this reply form
            const form = btn.closest('.comment-item').querySelector('.reply-form');
            form.classList.toggle('d-none');
            
            if (!form.classList.contains('d-none')) {
                form.querySelector('input[name="content"]').focus();
            }
        });
    });
    
    // Submit reply with Enter key
    document.querySelectorAll('.reply-form input[name="content"]').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endpush
