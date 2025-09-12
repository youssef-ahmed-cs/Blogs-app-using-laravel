<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['title', 'description', 'user_id',
        'content', 'image', 'views', 'image_post',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user_creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // app/Models/Post.php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}


public function comments()
{
    return $this->hasMany(Comment::class);
}



public function likesCount()
{
    return $this->likes()->count();
}

public function commentsCount()
{
    return $this->comments()->count();
}

// app/Models/Post.php
// App/Models/Post.php
public function likes()
{
    return $this->hasMany(Like::class);
}

public function isLikedBy($user)
{
    return $this->likes()->where('user_id', $user->id)->exists();
}


}


