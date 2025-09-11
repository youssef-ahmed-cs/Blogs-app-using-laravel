<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    protected $fillable = ['user_id', 'content'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
{
    return $this->hasMany(Comment::class, 'parent_id')->with('user')->orderBy('created_at', 'asc');
}

public function parent()
{
    return $this->belongsTo(Comment::class, 'parent_id');
}

}
