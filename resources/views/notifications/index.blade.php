@extends('Layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container py-4" style="max-width: 800px;">

        <h2 class="mb-4">Notifications</h2>

        @if(session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <div class="alert alert-info text-center">
                You have no notifications.
            </div>
        @else
            <form method="POST" action="{{ route('notifications.readAll') }}" class="mb-3 text-end">
                @csrf
                @method('PATCH')
                <button class="btn btn-sm btn-outline-primary">Mark All as Read</button>
            </form>

            <ul class="list-group shadow-sm">
                @foreach($notifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-start
                        {{ $notification->read_at ? '' : 'list-group-item-warning' }}">

                        <div class="me-3">
                            <span class="badge bg-primary rounded-pill">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="flex-grow-1">
                            <p class="mb-1">
                                <strong>
                                    New Comment on
                                    @if(!empty($notification->data['post_id']))
                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}">
                                            "{{ $notification->data['title'] ?? 'Unknown Post' }}"
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            "{{ $notification->data['title'] ?? 'Unknown Post' }}"
                                        </span>
                                    @endif
                                </strong>
                            </p>
                            <p class="text-muted mb-1">
                                <em>"{{ Str::limit($notification->data['comment_content'] ?? 'No content', 150) }}"</em>
                            </p>
                            <small class="text-secondary">
                                By {{ $notification->data['name'] ?? 'Unknown User' }}
                            </small>
                        </div>

                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success">Mark as Read</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
