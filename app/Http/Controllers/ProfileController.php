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
    $followers = $user->followers()->with('profile')->get();
    $followings = $user->followings()->with('profile')->get();
    return view('profile.show', compact('user', 'followers', 'followings'));
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

    // جلب المتابعين والمتابعين مع البروفايل
    $followers = $user->followers()->with('profile')->get();
    $followings = $user->followings()->with('profile')->get();

    return view('profile.public', compact('user', 'posts', 'comments', 'followers', 'followings'));
}

public function uploadCover(Request $request, User $user)
{
    // Validate only authenticated user can update their own cover
    if (Auth::id() !== $user->id) {
        return back()->with('error', 'لا يمكنك تغيير صورة غلاف مستخدم آخر');
    }

    $request->validate([
        'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Create profile if it doesn't exist
    $profile = $user->profile ?? $user->profile()->create();

    // Delete old cover image if exists
    if ($profile->cover_image && Storage::disk('public')->exists($profile->cover_image)) {
        Storage::disk('public')->delete($profile->cover_image);
    }

    // Store new cover image
    $path = $request->file('cover_image')->store('cover_images', 'public');
    $profile->cover_image = $path;
    $profile->save();

    return back()->with('success', 'تم تحديث صورة الغلاف بنجاح');
}}