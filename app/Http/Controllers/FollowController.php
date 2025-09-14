<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Notifications\FollowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $follower = Auth::user();
        
        // Prevent self-following
        if ($follower->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself');
        }

        // Check if already following
        $existingFollow = Follow::where('follower_id', $follower->id)
            ->where('following_id', $user->id)
            ->first();

        if ($existingFollow) {
            return back()->with('error', 'You are already following this user');
        }

        // Create follow relationship
        Follow::create([
            'follower_id' => $follower->id,
            'following_id' => $user->id
        ]);
        
        // Send notification to the user being followed
        $user->notify(new FollowNotification($follower));

        return back()->with('success', 'You are now following ' . $user->name . '!');
    }

    public function unfollow(User $user)
    {
        $follower = Auth::user();
        
        // Find and delete the follow relationship
        $follow = Follow::where('follower_id', $follower->id)
            ->where('following_id', $user->id)
            ->first();

        if ($follow) {
            $follow->delete();
            return back()->with('success', 'You have unfollowed ' . $user->name);
        }

        return back()->with('error', 'You are not following this user');
    }
}
