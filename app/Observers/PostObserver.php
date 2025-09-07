<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post): void
    {
    }

    public function created(Post $post): void
    {
        $post->description = Str::limit($post->description, 350);
        $post->save();
    }

    public function updating(Post $post): void
    {
    }


    public function saving(Post $post): void
    {
    }

    public function saved(Post $post): void
    {
    }

    public function deleting(Post $post): void
    {
    }

    public function deleted(Post $post): void
    {
    }

    public function restored(Post $post): void
    {
    }
}
