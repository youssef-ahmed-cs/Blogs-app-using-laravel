<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        // Handle profile image upload and bio update
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            // Delete old image if exists
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            
            // Update or create profile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['profile_image' => $imagePath, 'bio' => $request->bio]
            );
        } else {
            // Update bio only
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['bio' => $request->bio]
            );
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function public($id)
    {
        try {
            $user = User::with([
                'posts' => function ($query) {
                    $query->select('id', 'user_id', 'title', 'description', 'image_post', 'created_at')
                          ->with(['likes' => function ($q) {
                              $q->select('id', 'post_id');
                          }, 'comments' => function ($q) {
                              $q->select('id', 'post_id', 'content', 'created_at');
                          }]);
                },
                'comments' => function ($query) {
                    $query->select('id', 'user_id', 'post_id', 'content', 'created_at')
                          ->with(['post' => function ($q) {
                              $q->select('id', 'description');
                          }]);
                },
                'profile' => function ($query) {
                    $query->select('user_id', 'profile_image', 'bio', 'cover_image');
                }
            ])->findOrFail($id);

            // Get user's posts with likes and comments count
            $posts = $user->posts()
                ->with(['user.profile' => function ($query) {
                    $query->select('user_id', 'profile_image');
                }, 'likes' => function ($q) {
                    $q->select('id', 'post_id');
                }, 'comments' => function ($q) {
                    $q->select('id', 'post_id', 'content', 'created_at');
                }])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->get();

            // Get followers and followings count
            $followersCount = $user->followersCount();
            $followingsCount = $user->followingCount();
            
            // Get followers and followings for modals
            $followers = $user->followers()->with(['follower' => function ($query) {
                $query->select('id', 'name')->with(['profile' => function ($q) {
                    $q->select('user_id', 'profile_image');
                }]);
            }])->get()->pluck('follower');
            
            $followings = $user->following()->with(['following' => function ($query) {
                $query->select('id', 'name')->with(['profile' => function ($q) {
                    $q->select('user_id', 'profile_image');
                }]);
            }])->get()->pluck('following');

            // Check if current user is following this user
            $isFollowing = auth()->check() ? auth()->user()->isFollowing($user->id) : false;
            
            return view('profile.public', compact('user', 'posts', 'followers', 'followings', 'followersCount', 'followingsCount', 'isFollowing'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'User not found.');
        }
    }

    public function uploadCover(Request $request, User $user)
    {
        // Check if user is authenticated and authorized
        if (!auth()->check() || auth()->id() !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Validate the uploaded file
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'cover_image.required' => 'Please select an image.',
            'cover_image.image' => 'The file must be an image.',
            'cover_image.mimes' => 'The image must be a JPEG, PNG, JPG, GIF, or WebP.',
            'cover_image.max' => 'The image size must not exceed 2MB.',
        ]);

        try {
            // Check if file is valid
            if (!$request->hasFile('cover_image') || !$request->file('cover_image')->isValid()) {
                return redirect()->back()->with('error', 'Invalid file upload. Please try again.');
            }

            // Delete old cover if exists
            if ($user->profile && $user->profile->cover_image) {
                Storage::disk('public')->delete($user->profile->cover_image);
            }

            // Store new cover image
            $coverPath = $request->file('cover_image')->store('cover_images', 'public');

            // Save in DB - ensure profile exists
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['cover_image' => $coverPath]
            );

            return redirect()->back()->with('success', 'Cover photo updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to upload cover image: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to upload cover photo. Please try again.');
        }
    }
}