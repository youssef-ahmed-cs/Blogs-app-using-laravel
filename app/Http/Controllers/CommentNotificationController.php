<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentNotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id): \Illuminate\Http\RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }

        return back()->with('status', 'Notification marked as read!');
    }

    public function markAllAsRead(): \Illuminate\Http\RedirectResponse
    {
        $notifications = auth()->user()->unreadNotifications;
        if ($notifications->count()) {
            $notifications->markAsRead();
        }

        return back()->with('status', 'All notifications marked as read!');
    }
}
