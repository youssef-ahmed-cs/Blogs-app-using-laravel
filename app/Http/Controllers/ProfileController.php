<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $user->load('profile');
        
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            
            // Create or update profile
            if (!$user->profile) {
                $user->profile()->create(['profile_image' => $imagePath, 'bio' => $request->bio]);
            } else {
                $user->profile->update(['profile_image' => $imagePath, 'bio' => $request->bio]);
            }
        } else {
            // Update bio only
            if (!$user->profile) {
                $user->profile()->create(['bio' => $request->bio]);
            } else {
                $user->profile->update(['bio' => $request->bio]);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function public($id)
    {
        $user = User::with(['posts.likes', 'posts.comments', 'comments.post', 'profile'])
            ->findOrFail($id);
        
        // Get user's posts with likes and comments count
        $posts = $user->posts()
            ->with(['user.profile', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();

        // Get followers and followings using the User model relationships
        $followersCount = $user->followersCount();
        $followingsCount = $user->followingCount();
        
        // Get actual followers and followings for modals
        $followers = $user->followers()->with('follower.profile')->get()->pluck('follower');
        $followings = $user->following()->with('following.profile')->get()->pluck('following');

        // Check if current user is following this user
        $isFollowing = false;
        if (auth()->check()) {
            $isFollowing = auth()->user()->isFollowing($user->id);
        }
        
        return view('profile.public', compact('user', 'posts', 'followers', 'followings', 'followersCount', 'followingsCount', 'isFollowing'));
    }

public function uploadCover(Request $request, User $user)
{
    if (auth()->id() !== $user->id) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $request->validate([
        'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        // Delete old cover if exists
        if ($user->profile && $user->profile->cover_image) {
            Storage::disk('public')->delete($user->profile->cover_image);
        }

        // Store new
        $coverPath = $request->file('cover_image')->store('cover_images', 'public');

        // Save in DB
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['cover_image' => $coverPath]
        );

        return redirect()->back()->with('success', 'Cover photo updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to upload cover.');
    }
}



}