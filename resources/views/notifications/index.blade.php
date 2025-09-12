@extends('Layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mt-4">
    <h2>Notifications</h2>

    @if($notifications->count())
        <ul class="list-group">
            @foreach($notifications as $notification)
                @php
                    $message = $notification->data['message'] ?? 'تم التفاعل على البوست';
                    $postId = $notification->data['post_id'] ?? null;
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $notification->id }}">
                    <div>
                        @if($postId)
                            <a href="{{ route('posts.show', $postId) }}" class="notification-link {{ !$notification->read_at ? 'fw-bold' : '' }}">
                                {{ $message }}
                            </a>
                        @else
                            {{ $message }}
                        @endif
                        <br>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>

                    @if(!$notification->read_at)
                        <span class="badge bg-primary rounded-pill">New</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">لا توجد إشعارات حالياً</p>
    @endif
</div>

<script>
document.addEventListener('click', function(e) {
    const link = e.target.closest('.notification-link');
    if(link) {
        e.preventDefault();
        const li = link.closest('li');
        const notificationId = li.dataset.id;
        const href = link.getAttribute('href');

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
                li.querySelector('.badge')?.remove();
                link.classList.remove('fw-bold');

                // تحديث عداد navbar
                const badge = document.querySelector('.navbar .bi-bell + span');
                if(badge) {
                    let count = parseInt(badge.textContent);
                    badge.textContent = Math.max(count - 1, 0);
                    if(badge.textContent == '0') badge.remove();
                }

                // تحويل المستخدم للبوست
                window.location.href = href;
            }
        })
        .catch(err => console.error(err));
    }
});
</script>
@endsection
