<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PostInteraction;

class TestReshare extends Command
{
    protected $signature = 'test:reshare';
    protected $description = 'Test the reshare functionality';

    public function handle()
    {
        try {
            // Find a test user
            $user = User::first();
            if (!$user) {
                $this->error("No users found in database.");
                return 1;
            }
            
            // Login as user
            Auth::login($user);
            $this->info("Logged in as user: {$user->name} (ID: {$user->id})");
            
            // Find a post to reshare
            $post = Post::where('user_id', '!=', $user->id)->first();
            if (!$post) {
                $this->warn("No posts found by other users to test resharing.");
                $post = Post::first();
                if (!$post) {
                    $this->error("No posts found at all.");
                    return 1;
                }
                $this->warn("Using post ID {$post->id} for testing even though it's by the same user.");
            } else {
                $this->info("Found post ID {$post->id} by user ID {$post->user_id} for testing.");
            }
            
            // Create a new post as a reshare
            $newPost = new Post();
            $newPost->user_id = Auth::id();
            $newPost->title = 'Reshared post - ' . now();
            $newPost->description = $post->title ?? '';
            $newPost->quote = 'This is a test reshare with quote - ' . now();
            $newPost->original_post_id = $post->id;
            $newPost->is_reshare = true;
            $newPost->save();
            
            $this->info("Created new reshared post with ID: {$newPost->id}");
            
            // Increment the share count on the original post
            $post->increment('shares');
            $this->info("Incremented share count on original post to {$post->shares}");
            
            // Send notification if post owner is different
            if ($post->user_id != Auth::id()) {
                $this->info("Sending notification to user ID {$post->user_id}...");
                $originalPostUser = User::find($post->user_id);
                if ($originalPostUser) {
                    $originalPostUser->notify(new PostInteraction(
                        Auth::user(),
                        $post,
                        'reshare'
                    ));
                    $this->info("Notification sent successfully!");
                } else {
                    $this->error("Could not find original post user with ID {$post->user_id}");
                }
            } else {
                $this->warn("Not sending notification because reshared post belongs to current user");
            }
            
            $this->info("Test completed successfully!");
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error during test: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . " on line " . $e->getLine());
            return 1;
        }
    }
}