<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← مهم

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'لا يمكنك متابعة نفسك');
        }

        Auth::user()->followings()->attach($user->id);
        
        return back()->with('success', 'تمت المتابعة بنجاح');
    }

    public function unfollow(User $user)
    {
        Auth::user()->followings()->detach($user->id);
        
        return back()->with('success', 'تم إلغاء المتابعة بنجاح');
    }
}
