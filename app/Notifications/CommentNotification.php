<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public object $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'name' => $this->comment->user?->name,
            'title' => $this->comment->post?->title,
            'message' => $this->comment->user?->name . ' commented on your post: ' . $this->comment->post?->title,
            'created_at' => $this->comment->created_at,
            'post_id' => $this->comment->post?->id,
            'user_id' => $this->comment->user?->id,
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
