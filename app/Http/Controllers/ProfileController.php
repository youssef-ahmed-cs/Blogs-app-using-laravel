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
        // Add logging to debug duplicate calls
        Log::info('uploadCover method called', [
            'user_id' => $user->id,
            'auth_user' => auth()->id(),
            'has_file' => $request->hasFile('cover_image'),
            'session_id' => session()->getId()
        ]);

        // Check if user is authenticated and authorized
        if (!auth()->check() || auth()->id() !== $user->id) {
            Log::warning('Unauthorized cover upload attempt', ['user_id' => $user->id, 'auth_user' => auth()->id()]);
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
                Log::error('Invalid file upload', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'Invalid file upload. Please try again.');
            }

            // Delete old cover if exists
            if ($user->profile && $user->profile->cover_image) {
                Storage::disk('public')->delete($user->profile->cover_image);
                Log::info('Old cover image deleted', ['path' => $user->profile->cover_image]);
            }

            // Store new cover image
            $coverPath = $request->file('cover_image')->store('cover_images', 'public');
            Log::info('New cover image stored', ['path' => $coverPath]);

            // Save in DB - ensure profile exists
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['cover_image' => $coverPath]
            );

            Log::info('Cover image updated successfully', ['user_id' => $user->id, 'path' => $coverPath]);
            return redirect()->back()->with('success', 'Cover photo updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to upload cover image: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to upload cover photo. Please try again.');
        }
    }

    public function uploadAvatar(Request $request, User $user)
    {
        // Add logging to debug duplicate calls
        Log::info('uploadAvatar method called', [
            'user_id' => $user->id,
            'auth_user' => auth()->id(),
            'has_file' => $request->hasFile('profile_image'),
            'session_id' => session()->getId()
        ]);

        // Check if user is authenticated and authorized
        if (!auth()->check() || auth()->id() !== $user->id) {
            Log::warning('Unauthorized avatar upload attempt', ['user_id' => $user->id, 'auth_user' => auth()->id()]);
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Validate the uploaded file
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'profile_image.required' => 'Please select an image.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.mimes' => 'The image must be a JPEG, PNG, JPG, GIF, or WebP.',
            'profile_image.max' => 'The image size must not exceed 2MB.',
        ]);

        try {
            // Check if file is valid
            if (!$request->hasFile('profile_image') || !$request->file('profile_image')->isValid()) {
                Log::error('Invalid avatar file upload', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'Invalid file upload. Please try again.');
            }

            // Delete old profile image if exists
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
                Log::info('Old profile image deleted', ['path' => $user->profile->profile_image]);
            }

            // Store new profile image
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            Log::info('New profile image stored', ['path' => $imagePath]);

            // Save in DB - ensure profile exists
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['profile_image' => $imagePath]
            );

            Log::info('Profile image updated successfully', ['user_id' => $user->id, 'path' => $imagePath]);
            return redirect()->back()->with('success', 'Profile photo updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to upload profile image: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to upload profile photo. Please try again.');
        }
    }
}