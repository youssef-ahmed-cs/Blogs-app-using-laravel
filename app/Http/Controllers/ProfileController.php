<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    
public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string|max:500',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Update user name
    $user->name = $request->name;
    
    // Get or create profile for the user
    $profile = $user->profile;
    if (!$profile) {
        $profile = new \App\Models\Profile();
        $profile->user_id = $user->id;
    }
    $profile->bio = $request->bio;
    
    if ($request->hasFile('profile_image')) {
        if ($profile->profile_image) {
            Storage::disk('public')->delete($profile->profile_image);
        }

        $path = $request->file('profile_image')->store('profiles', 'public');
        $profile->profile_image = $path;
    }

    // Save both models
    $user->save();
    $profile->save();

    return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
}
public function public($id)
{
    $user = User::with('profile')->findOrFail($id);

    $posts = $user->posts()->latest()->get();
    $comments = $user->comments()->latest()->get();

    return view('profile.public', compact('user', 'posts', 'comments'));
}



}
