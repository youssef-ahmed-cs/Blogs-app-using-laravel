<?php

namespace App\Http\Controllers;

use App\Notifications\PostInteraction;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display user notifications with pagination
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(10);
        
        if (request()->ajax()) {
            return response()->json([
                'notifications' => view('notifications.partials.notification-items', compact('notifications'))->render(),
                'nextPage' => $notifications->nextPageUrl()
            ]);
        }
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['status' => 'success']);
    }
}



