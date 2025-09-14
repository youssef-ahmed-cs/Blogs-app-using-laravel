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
        $post = $this->comment->post;
        $postTitle = $post ? ($post->title ?: $post->description) : '';
        
        // Limit post title/description length
        if ($postTitle && strlen($postTitle) > 30) {
            $postTitle = substr($postTitle, 0, 30) . '...';
        }
        
        $commenter = $this->comment->user;
        $commenterName = $commenter ? $commenter->name : 'Someone';
        
        return [
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'user_id' => $commenter ? $commenter->id : null,
            'user_name' => $commenterName,
            'post_id' => $post ? $post->id : null,
            'post_title' => $postTitle,
            'type' => 'comment',
            'message' => $commenterName . ' commented on your post: "' . $postTitle . '"',
            'created_at' => $this->comment->created_at,
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
