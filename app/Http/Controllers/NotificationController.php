<?php

namespace App\Http\Controllers;


use App\Notifications\PostInteraction;
use App\Models\Post;
use App\Models\Comment;

class NotificationController extends Controller
{
    // إرسال Notification عند Like
    public function sendLikeNotification(Post $post)
    {
        $post->user->notify(new PostInteraction(auth()->user(), $post, 'like'));
        return response()->json(['status' => 'success']);
    }

    // إرسال Notification عند Comment
    public function sendCommentNotification(Post $post, Comment $comment)
    {
        $post->user->notify(new PostInteraction(auth()->user(), $post, 'comment', $comment));
        return response()->json(['status' => 'success']);
    }

    // عرض كل النوتيفيكشنز
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    // تعليم Notification كمقروء
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);
        return response()->json(['status' => 'success']);
    }
}



