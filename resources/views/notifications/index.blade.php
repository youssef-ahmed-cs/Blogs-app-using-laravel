@extends('Layouts.app')

@section('title', 'Notifications')

@section('content')
<style>
.notification-item {
    transition: background-color 0.2s ease;
    cursor: pointer;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-link {
    text-decoration: none;
    color: inherit;
}

.notification-link:hover {
    text-decoration: none;
    color: inherit;
}

.notification-item.unread {
    border-left: 4px solid #0d6efd;
    background-color: #f8f9ff;
}
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Notifications</h2>
        @if($notifications->where('read_at', null)->count() > 0)
            <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                Mark All as Read
            </button>
        @endif
    </div>

    @if($notifications->count())
        <div class="list-group">
            @foreach($notifications as $notification)
                @php
                    $message = $notification->data['message'] ?? 'تم التفاعل على البوست';
                    $postId = $notification->data['post_id'] ?? null;
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
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
            <p class="text-muted">No notifications yet</p>
        </div>
    @endif
</div>

<script>
// Mark single notification as read when clicked
document.addEventListener('click', function(e) {
    const link = e.target.closest('.notification-link');
    const deleteBtn = e.target.closest('.delete-notification');
    
    if (deleteBtn) {
        // Handle delete notification
        e.preventDefault();
        e.stopPropagation();
        
        const notificationId = deleteBtn.dataset.id;
        const notificationItem = deleteBtn.closest('.notification-item');
        
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    notificationItem.remove();
                    updateNotificationBadge();
                }
            })
            .catch(err => console.error('Error deleting notification:', err));
        }
        return;
    }
    
    if (link) {
        // Handle notification click
        e.preventDefault();
        const notificationItem = link.closest('.notification-item');
        const notificationId = notificationItem.dataset.id;
        const href = link.getAttribute('href');
        const isUnread = notificationItem.classList.contains('unread');

        if (isUnread) {
            // Mark as read first
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update UI
                    notificationItem.classList.remove('unread');
                    notificationItem.querySelector('.badge')?.remove();
                    link.classList.remove('fw-bold');
                    updateNotificationBadge();
                    
                    // Redirect to post
                    window.location.href = href;
                }
            })
            .catch(err => {
                console.error('Error marking notification as read:', err);
                // Still redirect even if marking as read fails
                window.location.href = href;
            });
        } else {
            // Already read, just redirect
            window.location.href = href;
        }
    }
});

// Mark all notifications as read
function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            // Update all notification items
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.querySelector('.badge')?.remove();
                item.querySelector('.fw-bold')?.classList.remove('fw-bold');
            });
            
            // Hide mark all as read button
            document.querySelector('button[onclick="markAllAsRead()"]')?.remove();
            
            updateNotificationBadge();
        }
    })
    .catch(err => console.error('Error marking all as read:', err));
}

// Update notification badge in navbar
function updateNotificationBadge() {
    const badge = document.querySelector('.navbar .bi-bell + .badge');
    if (badge) {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        if (unreadCount > 0) {
            badge.textContent = unreadCount;
        } else {
            badge.remove();
        }
    }
}
</script>
@endsection