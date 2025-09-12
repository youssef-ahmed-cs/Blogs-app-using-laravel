<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        // Prevent self-following
        if (auth()->id() === $user->id) {
            return back()->with('error', 'لا يمكنك متابعة نفسك');
        }

        // Check if already following
        $existingFollow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)
            ->first();

        if ($existingFollow) {
            return back()->with('error', 'أنت تتابع هذا المستخدم بالفعل');
        }

        // Create follow relationship
        Follow::create([
            'follower_id' => auth()->id(),
            'following_id' => $user->id
        ]);

        return back()->with('success', 'أنت الآن تتابع ' . $user->name . '!');
    }

    public function unfollow(User $user)
    {
        // Find and delete the follow relationship
        $follow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $user->id)
            ->first();

        if ($follow) {
            $follow->delete();
            return back()->with('success', 'لقد ألغيت متابعة ' . $user->name);
        }

        return back()->with('error', 'أنت لا تتابع هذا المستخدم');
    }
}
