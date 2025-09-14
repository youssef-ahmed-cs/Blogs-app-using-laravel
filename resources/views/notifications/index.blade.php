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

/* Loading indicator */
.loading-spinner {
    display: none;
    text-align: center;
    padding: 20px;
}

/* Add fade in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.notification-item {
    animation: fadeIn 0.3s ease;
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
        <div class="list-group" id="notifications-container">
            @include('notifications.partials.notification-items')
        </div>
        
        <!-- Loading spinner for infinite scroll -->
        <div class="loading-spinner" id="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-2">Loading more notifications...</div>
        </div>
        
        <!-- Hidden input to store next page URL -->
        <input type="hidden" id="next-page" value="{{ $notifications->nextPageUrl() }}">
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if(data.status === 'success') {
                    notificationItem.remove();
                    updateNotificationBadge();
                    
                    // Check if there are no more notifications
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        // Show the empty state
                        const container = document.querySelector('.container.mt-4');
                        container.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                                <p class="text-muted">No notifications yet</p>
                            </div>
                        `;
                    }
                }
            })
            .catch(err => {
                console.error('Error deleting notification:', err);
                alert('Failed to delete notification. Please try again.');
            });
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if(data.status === 'success') {
                    // Update UI
                    notificationItem.classList.remove('unread');
                    const badge = notificationItem.querySelector('.badge');
                    if (badge) badge.remove();
                    const boldText = notificationItem.querySelector('.fw-bold');
                    if (boldText) boldText.classList.remove('fw-bold');
                    updateNotificationBadge();
                }
                
                // Redirect to post even if the server response was not a success
                setTimeout(() => {
                    window.location.href = href;
                }, 100);
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(data => {
        if(data.status === 'success') {
            // Update all notification items
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                const badge = item.querySelector('.badge');
                if (badge) badge.remove();
                const boldText = item.querySelector('.fw-bold');
                if (boldText) boldText.classList.remove('fw-bold');
            });
            
            // Hide mark all as read button
            const markAllBtn = document.querySelector('button[onclick="markAllAsRead()"]');
            if (markAllBtn) markAllBtn.remove();
            
            updateNotificationBadge();
            
            // Show success message
            const container = document.querySelector('.container.mt-4');
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success alert-dismissible fade show mt-3';
            successMsg.innerHTML = `
                All notifications marked as read
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            container.insertBefore(successMsg, container.firstChild.nextSibling);
            
            // Auto dismiss after 3 seconds
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        }
    })
    .catch(err => {
        console.error('Error marking all as read:', err);
        alert('Failed to mark all notifications as read. Please try again.');
    });
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

// Infinite scroll functionality
let isLoading = false;
let hasMorePages = !!document.getElementById('next-page')?.value;

// Function to load more notifications
function loadMoreNotifications() {
    if (isLoading || !hasMorePages) return;
    
    isLoading = true;
    const nextPageUrl = document.getElementById('next-page').value;
    const loadingSpinner = document.getElementById('loading-spinner');
    loadingSpinner.style.display = 'block';
    
    fetch(nextPageUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const notificationsContainer = document.getElementById('notifications-container');
        
        // Append new notifications
        const tempContainer = document.createElement('div');
        tempContainer.innerHTML = data.notifications;
        
        // Add each child element individually to preserve event handlers
        while (tempContainer.firstChild) {
            notificationsContainer.appendChild(tempContainer.firstChild);
        }
        
        // Update next page URL or mark as no more pages
        if (data.nextPage) {
            document.getElementById('next-page').value = data.nextPage;
        } else {
            hasMorePages = false;
        }
        
        isLoading = false;
        loadingSpinner.style.display = 'none';
    })
    .catch(error => {
        console.error('Error loading more notifications:', error);
        isLoading = false;
        loadingSpinner.style.display = 'none';
    });
}

// Check if scroll is near bottom to load more notifications
window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
        loadMoreNotifications();
    }
});

// Add event delegation for new notification items added by infinite scroll
document.addEventListener('click', function(e) {
    const notificationLink = e.target.closest('.notification-link');
    const deleteBtn = e.target.closest('.delete-notification');
    
    // Handle existing click events...
});
</script>
@endsection