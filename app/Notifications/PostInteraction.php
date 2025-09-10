<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostInteraction extends Notification
{
    use Queueable;

    public $user;
    public $post;
    public $type;     // 'like' أو 'comment'
    public $comment;  // optional

    public function __construct($user, $post, $type, $comment = null)
    {
        $this->user = $user;
        $this->post = $post;
        $this->type = $type;
        $this->comment = $comment; // مهم
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $commentContent = $this->comment?->content ?? ''; // لو في كومنت استخدمه

        $message = $this->type === 'like'
            ? $this->user->name . ' أعجب بالبوست: "' . $this->post->title . '"'
            : $this->user->name . ' علّق على البوست: "' . $this->post->title . '" - "' . $commentContent . '"';

        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'type' => $this->type,
            'comment_id' => $this->comment?->id,
            'message' => $message,
        ];
    }
}
