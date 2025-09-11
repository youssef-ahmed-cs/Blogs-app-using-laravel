<?php

namespace App\Http\Controllers;

use App\Notifications\PostInteraction;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // عرض كل النوتيفيكشنز
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    // تعليم Notification كمقروء
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['status' => 'success']);
    }

    // تعليم جميع النوتيفيكشنز كمقروء
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return response()->json(['status' => 'success']);
    }

    // حذف نوتيفيكشن
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['status' => 'success']);
    }
}



