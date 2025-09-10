<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Notifications\PostInteraction;

class LikeController extends Controller
{
public function toggleLike(Post $post)
{
    $user = auth()->user();

    if ($post->likes()->where('user_id', $user->id)->exists()) {
        $post->likes()->where('user_id', $user->id)->delete();
        $status = 'unliked';
    } else {
        $post->likes()->create(['user_id' => $user->id]);
        $status = 'liked';

        // إرسال Notification للكاتب
        if ($post->user_id !== $user->id) {
            $post->user->notify(new PostInteraction($user, $post, 'like'));
        }
    }

    return response()->json([
        'status' => $status,
        'likesCount' => $post->likes()->count(),
    ]);
}


}
