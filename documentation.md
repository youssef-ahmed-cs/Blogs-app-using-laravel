# Laravel Blog Application Documentation

This document provides a comprehensive overview of the Laravel blog application's main features, detailing the controllers, models, views, and routes used in each feature.

## Table of Contents
1. [Authentication System](#1-authentication-system)
2. [Posts Management](#2-posts-management)
3. [User Profiles](#3-user-profiles)
4. [Social Interactions](#4-social-interactions)
5. [Notifications System](#5-notifications-system)

---

## 1. Authentication System

### Feature Description
The authentication system handles user registration, login, and logout functionality. It provides secure user authentication and session management.

### Controllers Used
- **AuthController** (`app/Http/Controllers/AuthController.php`)
  - Manages user registration, login, and logout
  - Handles authentication request validation
  - Redirects users to appropriate pages based on authentication status

### Models Used
- **User** (`app/Models/User.php`)
  - Core model for user authentication
  - Contains fillable properties: name, email, password, role, profile_image, bio
  - Uses Laravel's built-in authentication traits: HasFactory, Notifiable

### Views Used
- **Auth/login.blade.php**
  - Login form with email and password fields
  - Error validation display
  - Link to registration page
- **Auth/register.blade.php**
  - Registration form with name, email, and password fields
  - Form validation display
  - Link to login page

### Routes Used
```php
// Authentication routes (accessible to guests)
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'loginForm')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
    Route::get('register', 'registerForm')->name('register');
    Route::post('register', 'registerPost')->name('register.post');
    Route::post('logout', 'logout')->name('logout');
});
```

### Important Notes
- Uses Laravel's built-in authentication system with custom controllers
- Password hashing is handled automatically through Laravel's `Hash` facade
- The login system uses the 'web' guard for session-based authentication
- Registration sends a welcome email (currently commented out in the code)
- After login, users are redirected to the posts index page
- Uses form requests for validation (AuthLoginRequest, AuthRegisterRequest)

---

## 2. Posts Management

### Feature Description
The posts management system allows users to create, view, edit, delete, and share blog posts. It supports rich content with text and images, as well as post interactions like views, shares, and reshares.

### Controllers Used
- **PostController** (`app/Http/Controllers/PostController.php`)
  - Handles CRUD operations for posts (Create, Read, Update, Delete)
  - Manages post sharing and resharing functionality
  - Tracks post views
  - Provides post display logic with related content

### Models Used
- **Post** (`app/Models/Post.php`)
  - Contains post data: title, description, user_id, content, image, views, shares, image_post
  - Has relationships to User, Comments, and Likes
  - Includes methods for tracking post statistics (likesCount, commentsCount, etc.)
  - Includes reshare functionality with original_post_id relationships
  - Uses the PostObserver for event handling

### Views Used
- **Posts/index.blade.php**
  - Main post feed displaying all posts
  - Includes post creation modal for authenticated users
  - Post cards with user info, content, and interaction options
- **Posts/show.blade.php**
  - Detailed view of a single post
  - Shows comments and related posts
  - Provides interaction buttons (like, comment, share)
- **Posts/create.blade.php**
  - Form for creating new posts
- **Posts/edit.blade.php**
  - Form for editing existing posts
- **Posts/share-preview.blade.php**
  - Preview layout for social media sharing

### Routes Used
```php
// Main posts route - both / and /posts should work
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Public post routes
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/view', [PostController::class, 'recordView'])->name('posts.view');
Route::get('/posts/{post}/share-preview', [PostController::class, 'sharePreview'])->name('posts.share-preview');

// Protected post routes
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Share routes
    Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
    Route::post('/posts/{post}/reshare', [PostController::class, 'reshare'])->name('posts.reshare');
});
```

### Important Notes
- Posts can include text content and optional images
- Post images are stored in the `public/storage/posts` directory
- The system tracks post views, likes, and shares
- Posts can be shared to various social media platforms (Facebook, Twitter, WhatsApp, Telegram)
- Users can reshare posts, creating a new post that references the original
- The home route (`/`) shows the post feed as the application's main page
- Post permissions ensure only the author can edit or delete their posts

---

## 3. User Profiles

### Feature Description
The user profiles feature allows users to create and manage their personal profiles, including profile information, photos, and biographical details. It also enables users to view other users' profiles and their associated content.

### Controllers Used
- **ProfileController** (`app/Http/Controllers/ProfileController.php`)
  - Manages user profile display and updates
  - Handles profile image and cover photo uploads
  - Provides both private (authenticated) and public profile views
- **SettingsController**
  - Handles user settings updates
  - Manages password changes
  - Provides account deletion functionality

### Models Used
- **User** (`app/Models/User.php`)
  - Core user data model
  - Contains relationships to Profile, Posts, Comments, Likes, and Follow
- **Profile** (`app/Models/Profile.php`)
  - Stores profile-specific information
  - Contains profile_image, bio, and cover_image
  - Belongs to a User model through a one-to-one relationship

### Views Used
- **profile/show.blade.php**
  - Private profile view for authenticated users
  - Form for updating profile information
  - Profile and cover image upload options
- **profile/public.blade.php**
  - Public-facing profile for viewing other users
  - Displays user posts, followers, and followings
  - Shows profile statistics (posts count, followers count)

### Routes Used
```php
// Public profile route
Route::get('/profile/{id}', [ProfileController::class, 'public'])->name('profile.public');

// Protected profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/{user}/cover-upload', [ProfileController::class, 'uploadCover'])->name('profile.cover.upload');
    Route::post('/profile/{user}/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.destroy');
});
```

### Important Notes
- Profile images are stored in `public/storage/profiles`
- Cover images are stored in `public/storage/cover_images`
- Profile information includes name, email, bio, profile image, and cover image
- The public profile view shows the user's posts, followers, and followings
- User settings allow password changes and account deletion
- The system implements proper authorization to ensure users can only update their own profiles
- The profile system uses eager loading to optimize database queries when displaying user data

---

## 4. Social Interactions

### Feature Description
The social interactions feature enables users to engage with posts and other users through likes, comments, follows, and shares. This creates a social network experience within the application.

### Controllers Used
- **LikeController** (`app/Http/Controllers/LikeController.php`)
  - Manages post likes and unlikes
  - Tracks like counts
- **CommentController** (`app/Http/Controllers/CommentController.php`)
  - Handles comment creation, editing, and deletion
  - Supports threaded comments (replies to comments)
  - Implements comment permissions
- **FollowController** (`app/Http/Controllers/FollowController.php`)
  - Manages user follow/unfollow functionality
  - Tracks follower and following relationships

### Models Used
- **Like** (`app/Models/Like.php`)
  - Represents a user's like on a post
  - Contains relationship to User and Post
- **Comment** (`app/Models/Comment.php`)
  - Stores comment content and relationships
  - Supports threaded comments through parent_id field
  - Contains relationships to User, Post, and other Comments
- **Follow** (`app/Models/Follow.php`)
  - Represents a follow relationship between users
  - Contains follower_id and following_id fields

### Views Used
- Embedded within post views:
  - Comment forms
  - Like buttons
  - Share buttons
  - Follow/unfollow buttons on profiles

### Routes Used
```php
Route::middleware('auth')->group(function () {
    // Like and Comment routes
    Route::post('/posts/{post}/toggle-like', [LikeController::class, 'toggleLike'])->name('posts.toggle-like');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Follow/Unfollow routes
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
});
```

### Important Notes
- Likes are implemented as a many-to-many relationship between users and posts
- Comments support threaded replies using a self-referential relationship
- The follow system uses a separate Follow model to track relationships
- Social interactions trigger notifications to relevant users
- The application prevents users from following themselves
- Comment permissions ensure users can only edit or delete their own comments
- AJAX is used for likes and follows to provide a seamless user experience
- Comments can be added, edited, and deleted without page refresh

---

## 5. Notifications System

### Feature Description
The notifications system keeps users informed about relevant activities within the application, such as new likes, comments, follows, and reshares of their content. It provides real-time updates on user interactions.

### Controllers Used
- **NotificationController** (`app/Http/Controllers/NotificationController.php`)
  - Manages notification display
  - Handles marking notifications as read
  - Provides notification deletion functionality

### Models Used
- **Notification** (Laravel's built-in notification system)
  - Uses Laravel's database notifications
- **Custom Notification Classes**:
  - `PostInteraction` (`app/Notifications/PostInteraction.php`)
    - Handles notifications for likes, comments, and reshares
  - `FollowNotification` (`app/Notifications/FollowNotification.php`)
    - Handles notifications for new followers
  - `CommentNotification` (`app/Notifications/CommentNotification.php`)
    - Specific notifications for comments on posts

### Views Used
- **notifications/index.blade.php**
  - Main notification center
  - Lists all user notifications
  - Provides UI for reading and managing notifications
- **notifications/partials/notification-items.blade.php**
  - Partial view for rendering notification items
  - Used for AJAX-loaded notification content

### Routes Used
```php
Route::middleware('auth')->group(function () {
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
```

### Important Notes
- Uses Laravel's built-in notification system with database driver
- Notifications are created when users:
  - Like a post
  - Comment on a post
  - Follow another user
  - Reshare a post
- The UI shows read/unread status with visual indicators
- Notifications can be marked as read individually or all at once
- AJAX is used to update notifications without page refresh
- Notifications link directly to the relevant content (post, comment, user profile)
- The system prevents sending notifications when users interact with their own content