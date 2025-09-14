<?php

use App\Models\Post;
use App\Models\Like;

echo "=== Database Debug Info ===" . PHP_EOL;

echo "Total likes in database: " . Like::count() . PHP_EOL;
echo PHP_EOL;

$posts = Post::withCount('likes')->get();
echo "Posts with like counts:" . PHP_EOL;
foreach ($posts as $post) {
    echo "Post {$post->id} ('{$post->title}'): {$post->likes_count} likes" . PHP_EOL;
}

echo PHP_EOL;
echo "Individual likes:" . PHP_EOL;
$likes = Like::with(['post', 'user'])->get();
foreach ($likes as $like) {
    echo "User {$like->user->name} (ID: {$like->user_id}) liked Post {$like->post_id} ('{$like->post->title}')" . PHP_EOL;
}
