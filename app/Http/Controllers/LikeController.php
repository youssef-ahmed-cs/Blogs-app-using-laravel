<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeManagement\StoreLikeRequest;
use App\Http\Requests\LikeManagement\UpdateLikeRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function toggleLike(Post $post): RedirectResponse
    {
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }
        return back();
    }
}
