<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Like;
use App\Notifications\PostInteraction;

class LikeController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        // Check if user already liked this post
        $existingLike = Like::where('post_id', $post->id)
                           ->where('user_id', $user->id)
                           ->first();

        if ($existingLike) {
            // Unlike the post
            $existingLike->delete();
            $status = 'unliked';
        } else {
            // Like the post
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id
            ]);
            $status = 'liked';

            // Send notification to post owner (if not the same user)
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostInteraction($user, $post, 'like'));
            }
        }

        // Get updated likes count
        $likesCount = Like::where('post_id', $post->id)->count();

        return response()->json([
            'success' => true,
            'liked' => $status === 'liked',
            'status' => $status,
            'likes_count' => $likesCount,
        ]);
    }
}
