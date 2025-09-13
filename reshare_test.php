// Test reshare functionality
// Run with: php artisan tinker --execute="require('reshare_test.php');"

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PostInteraction;

try {
    // Find a test user
    $user = User::first();
    if (!$user) {
        echo "No users found in database.\n";
        return;
    }
    
    // Login as user
    Auth::login($user);
    echo "Logged in as user: {$user->name} (ID: {$user->id})\n";
    
    // Find a post to reshare
    $post = Post::where('user_id', '!=', $user->id)->first();
    if (!$post) {
        echo "No posts found by other users to test resharing.\n";
        $post = Post::first();
        if (!$post) {
            echo "No posts found at all.\n";
            return;
        }
        echo "Using post ID {$post->id} for testing even though it's by the same user.\n";
    } else {
        echo "Found post ID {$post->id} by user ID {$post->user_id} for testing.\n";
    }
    
    // Create a new post as a reshare
    $newPost = new Post();
    $newPost->user_id = Auth::id();
    $newPost->title = null;
    $newPost->description = $post->title ?? '';
    $newPost->quote = 'This is a test reshare with quote - ' . now();
    $newPost->original_post_id = $post->id;
    $newPost->is_reshare = true;
    $newPost->save();
    
    echo "Created new reshared post with ID: {$newPost->id}\n";
    
    // Increment the share count on the original post
    $post->increment('shares');
    echo "Incremented share count on original post to {$post->shares}\n";
    
    // Send notification if post owner is different
    if ($post->user_id != Auth::id()) {
        echo "Sending notification to user ID {$post->user_id}...\n";
        $originalPostUser = User::find($post->user_id);
        if ($originalPostUser) {
            $originalPostUser->notify(new PostInteraction(
                Auth::user(),
                $post,
                'reshare'
            ));
            echo "Notification sent successfully!\n";
        } else {
            echo "Could not find original post user with ID {$post->user_id}\n";
        }
    } else {
        echo "Not sending notification because reshared post belongs to current user\n";
    }
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}