<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostInteraction extends Notification
{
    use Queueable;

    public $user;
    public $post;
    public $type;
    public $comment;

    public function __construct($user, $post, $type, $comment = null)
    {
        $this->user = $user;
        $this->post = $post;
        $this->type = $type;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'type' => $this->type,
        ];

        if ($this->type === 'like') {
            $data['message'] = $this->user->name . ' أعجب بالبوست: "' . $this->post->title . '"';
        } elseif ($this->type === 'comment' && $this->comment) {
            $data['comment_id'] = $this->comment->id;
            $data['comment_content'] = substr($this->comment->content, 0, 100) . '...';
            $data['message'] = $this->user->name . ' علّق على البوست: "' . $this->post->title . '"';
        }

        return $data;
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
