@extends('Layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container py-4">

        @if(auth()->user()->notifications->isEmpty())
            <div class="alert alert-info">You have no notifications.</div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <ul class="list-group">
                        @foreach(auth()->user()->notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center
                            {{ $notification->read_at ? '' : 'list-group-item-warning' }}">
                                <div class="flex-grow-1">
                                    <strong>New Comment on "{{ $notification->data['title'] ?? 'Unknown Post' }}
                                        "</strong>
                                    <br>
                                    <em>"{{ Str::limit($notification->data['comment_content'] ?? 'No content', 200) }}
                                        "</em>
                                    <br>
                                    <small class="text-muted">By {{ $notification->data['name'] ?? 'Unknown User' }}
                                        {{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endsection
