<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Post;

// Try to get user ID 1 for testing
$user = Auth::loginUsingId(1);

// Check if user authentication was successful
if (!$user) {
    echo "Failed to authenticate test user. Make sure there's a user with ID 1.\n";
    exit(1);
}

// Get a random post for testing
$post = Post::first();

if (!$post) {
    echo "No posts found in the database to test with.\n";
    exit(1);
}

// Test reshare functionality
try {
    // Create a new post as a reshare
    $newPost = new Post();
    $newPost->user_id = Auth::id();
    $newPost->title = null;
    $newPost->description = $post->title ?? '';
    $newPost->quote = 'This is a test reshare with quote';
    $newPost->original_post_id = $post->id;
    $newPost->is_reshare = true;
    $newPost->save();
    
    // Increment the share count on the original post
    $post->increment('shares');
    
    // Output success message
    echo "Reshare test successful! Created new post ID: {$newPost->id}\n";
    echo "Original post ID: {$post->id} now has {$post->shares} shares.\n";
    
} catch (Exception $e) {
    echo "Error during reshare test: " . $e->getMessage() . "\n";
}

// Log out
Auth::logout();

echo "Test completed.\n";