@foreach($notifications as $notification)
    @php
        $data = $notification->data;
        $message = $data['message'] ?? 'New notification';
        $postId = $data['post_id'] ?? null;
        $isUnread = !$notification->read_at;
    @endphp
    <div class="list-group-item notification-item {{ $isUnread ? 'unread' : '' }}" 
         data-id="{{ $notification->id }}" 
         data-post-id="{{ $postId }}">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                @if($postId)
                    <a href="{{ route('posts.show', $postId) }}" 
                       class="notification-link {{ $isUnread ? 'fw-bold' : '' }}" 
                       style="display: block; padding: 0;">
                        {{ $message }}
                    </a>
                @else
                    <div class="{{ $isUnread ? 'fw-bold' : '' }}">
                        {{ $message }}
                    </div>
                @endif
                <small class="text-muted">
                    <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                </small>
            </div>

            <div class="d-flex align-items-center">
                @if($isUnread)
                    <span class="badge bg-primary rounded-pill me-2">New</span>
                @endif
                <button class="btn btn-sm btn-outline-danger delete-notification" 
                        data-id="{{ $notification->id }}" 
                        title="Delete notification">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
@endforeach