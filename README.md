# Blogs App using Laravel



## ✨ Features

### Core Features
- **User Authentication:** Secure registration, login, and logout with Laravel Sanctum.
- **Blog Management:** Create, edit, delete blog posts with rich content and image uploads.
- **Post Interactions:** 
  - Like/unlike posts with real-time counters
  - Threaded comment system with replies
  - Post view tracking and analytics
  - Social media sharing (Facebook, Twitter, WhatsApp, Telegram)
  - Post resharing/reposting functionality

### Social Features
- **User Profiles:** Complete profile management with bio, avatar, and cover images
- **Follow System:** Follow/unfollow users and view follower statistics
- **Public Profiles:** View other users' profiles with their posts and social connections
- **Real-time Notifications:** Get notified for likes, comments, follows, and reshares

### Advanced Features
- **User Settings:** Change passwords, update profile information, delete account
- **Image Management:** Upload and manage profile pictures, cover photos, and post images
- **API Support:** RESTful API with Sanctum authentication for mobile/external apps
- **Queue System:** Background job processing for notifications and heavy tasks
- **Developer Tools:** Code linting with Pint, real-time logging with Pail

### Technical Features
- **Modern UI:** Responsive design with Tailwind CSS and Bootstrap 5
- **RESTful Architecture:** Clean API design following Laravel conventions
- **Database Optimization:** Efficient queries with proper relationships and indexing
- **Security:** CSRF protection, input validation, and secure file uploads

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2 (Laravel 12 requirement)
- Composer 2.0+
- MySQL 8.0+ (or compatible database)
- Node.js 18+ & npm (for Vite and frontend assets)

### Technology Stack

- **Backend:** Laravel 12.x with PHP 8.2+
- **Frontend:** Tailwind CSS 4.x, Bootstrap 5, Vite 6
- **Database:** MySQL with comprehensive migrations
- **Authentication:** Laravel Sanctum for web and API
- **File Storage:** Laravel Storage with public disk
- **Queue System:** Database driver for background jobs
- **Development Tools:** Laravel Pint (code style), Laravel Pail (logging)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/M9nx/Blogs-app-using-laravel.git
   cd Blogs-app-using-laravel
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment:**
   - Copy `.env.example` to `.env`
   - Set database credentials and other environment variables in `.env`

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Create storage link for file uploads:**
   ```bash
   php artisan storage:link
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets:**
   ```bash
   npm run build
   # or for development with hot reloading:
   npm run dev
   ```

8. **Start the application:**
   ```bash
   # For development (with queue, logs, and vite):
   composer run dev
   
   # Or individually:
   php artisan serve
   # In separate terminals:
   php artisan queue:work
   npm run dev
   ```
   
   Visit [http://localhost:8000](http://localhost:8000) in your browser.

## 📧 Email Configuration

Enable email notifications by setting up your mail configuration in `.env`. Example for Gmail SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_email_password_or_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Tips:**

- For Gmail, use an [App Password](https://support.google.com/accounts/answer/185833) if you have 2FA enabled.
- After editing `.env`, run:
  ```bash
  php artisan config:cache
  ```
- See [Laravel Mail Documentation](https://laravel.com/docs/mail) for more options.

## 🗺️ Routes Catalog

### Web Routes

#### Authentication Routes
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form  
- `POST /register` - Process registration
- `POST /logout` - Logout user

#### Public Routes
- `GET /` - Home page (posts feed)
- `GET /posts` - Posts index
- `GET /posts/{post}` - View single post
- `GET /posts/{post}/share-preview` - Social media sharing preview
- `GET /profile/{id}` - View public user profile

#### Protected Routes (Require Authentication)

**Post Management:**
- `GET /posts/create` - Create new post form
- `POST /posts` - Store new post
- `GET /posts/{post}/edit` - Edit post form
- `PUT /posts/{post}` - Update post
- `DELETE /posts/{post}` - Delete post

**Post Interactions:**
- `POST /posts/{post}/toggle-like` - Like/unlike post
- `POST /posts/{post}/share` - Share post to social media
- `POST /posts/{post}/reshare` - Reshare/repost
- `POST /posts/{post}/view` - Record post view

**Comments:**
- `POST /posts/{post}/comments` - Add comment
- `PUT /comments/{comment}` - Edit comment
- `DELETE /comments/{comment}` - Delete comment

**User Profile & Settings:**
- `GET /profile` - Own profile settings
- `POST /profile` - Update profile
- `POST /profile/{user}/avatar-upload` - Upload avatar
- `POST /profile/{user}/cover-upload` - Upload cover photo
- `GET /settings` - User settings page
- `POST /settings/password` - Change password
- `DELETE /settings/delete` - Delete account

**Social Features:**
- `POST /follow/{user}` - Follow user
- `DELETE /unfollow/{user}` - Unfollow user

**Notifications:**
- `GET /notifications` - Notifications center
- `POST /notifications/{id}/mark-as-read` - Mark notification as read
- `POST /notifications/mark-all-as-read` - Mark all notifications as read
- `DELETE /notifications/{id}` - Delete notification

### API Routes (with Sanctum Authentication)

#### Authentication
- `POST /api/register` - API user registration
- `POST /api/login` - API user login  
- `POST /api/logout` - API logout (requires auth)

#### User Info
- `GET /api/user` - Get authenticated user info (requires auth)

## 📱 API Usage

### Authentication Example

```javascript
// Register
const response = await fetch('/api/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com', 
        password: 'password123',
        password_confirmation: 'password123'
    })
});

// Login and get token
const loginResponse = await fetch('/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'john@example.com',
        password: 'password123'
    })
});

const { token } = await loginResponse.json();

// Use token for authenticated requests
const userResponse = await fetch('/api/user', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
```

## 🗄️ Database Schema

The application uses the following main database tables:

### Core Tables
- **users** - User accounts and basic profile info
- **profiles** - Extended user profile data (bio, images)
- **posts** - Blog posts with content and metadata
- **comments** - Post comments with threading support
- **likes** - Post likes/reactions
- **follows** - User following relationships
- **notifications** - System notifications

### Key Relationships
- Users have one Profile
- Users have many Posts, Comments, Likes
- Posts belong to User, have many Comments and Likes
- Comments support threading (self-referential)
- Follows create many-to-many relationships between Users

## 🎨 User Interface Features

### Modern Design
- **Responsive Layout:** Works on desktop, tablet, and mobile devices
- **Tailwind CSS:** Utility-first styling with custom components
- **Bootstrap Components:** Enhanced UI elements and interactions
- **Dark/Light Mode Support:** Consistent theming across the application

### Interactive Elements
- **Real-time Likes:** Instant feedback with AJAX updates
- **Infinite Scroll:** Smooth loading of posts and comments
- **Image Uploads:** Drag-and-drop file upload with previews
- **Social Sharing:** One-click sharing to major platforms
- **Notification Dropdown:** Real-time notification updates

## 🔔 Notification System

### Types of Notifications
- **Post Interactions:** When someone likes or comments on your posts
- **Social Activity:** When someone follows you or reshares your content
- **Comment Replies:** When someone replies to your comments

### Features
- **Real-time Updates:** Notifications appear instantly
- **Email Integration:** Optional email notifications for important events
- **Mark as Read:** Individual or bulk notification management
- **Notification History:** Keep track of all your interactions

## 🛠️ Development Tools

### Code Quality
```bash
# Format code according to Laravel standards
./vendor/bin/pint

# Run tests
php artisan test

# View real-time logs
php artisan pail
```

### Asset Building
```bash
# Development with hot reloading
npm run dev

# Production build
npm run build

# Watch for changes
npm run dev --watch
```

### Queue Management
```bash
# Process background jobs
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed jobs  
php artisan queue:retry all
```

## 📸 Screenshots

### Home Feed
The main page displays a clean, responsive feed of blog posts with social interactions.

### User Profile
Comprehensive user profiles with cover photos, avatars, bio information, and social statistics.

### Post Creation
Rich post editor with image upload support and preview functionality.

### Notification Center  
Real-time notification system keeping users informed of all interactions.

## 🚀 Usage Examples

### Creating Your First Post
1. Register an account or log in
2. Click "Create Post" from the navigation or home page
3. Add a compelling title and rich content
4. Optionally upload an image to make your post stand out
5. Publish and watch the engagement roll in!

### Building Your Network
1. Explore the user profiles from post authors
2. Follow users whose content interests you
3. Engage with posts through likes and thoughtful comments
4. Build your own following by creating quality content

### Managing Your Profile
1. Go to Profile Settings from the navigation menu
2. Upload a profile picture and cover photo
3. Write a compelling bio that tells your story
4. Update your information as needed

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Development Setup
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Follow the installation instructions above
4. Make your changes following Laravel conventions
5. Run tests: `php artisan test`
6. Format code: `./vendor/bin/pint`
7. Commit your changes: `git commit -m 'Add amazing feature'`
8. Push to your branch: `git push origin feature/amazing-feature`
9. Open a Pull Request

### Contribution Guidelines
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation for user-facing changes
- Ensure backwards compatibility

## 🐛 Troubleshooting

### Common Issues

**Storage Permission Errors:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Missing Storage Link:**
```bash
php artisan storage:link
```

**Database Connection Issues:**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists: `CREATE DATABASE your_database_name`

**npm/Vite Issues:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Queue Not Processing:**
```bash
php artisan queue:work --tries=3 --timeout=90
# Or use supervisor for production
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with [Laravel 12](https://laravel.com) - The PHP Framework for Web Artisans
- UI components powered by [Tailwind CSS](https://tailwindcss.com) and [Bootstrap](https://getbootstrap.com)
- Icons from various open-source icon libraries
- Special thanks to the Laravel community for inspiration and support

## 📞 Support

- **Issues:** [GitHub Issues](https://github.com/M9nx/Blogs-app-using-laravel/issues)
- **Documentation:** [Laravel Documentation](https://laravel.com/docs)
- **Community:** [Laravel Community](https://laracasts.com)

---

**Happy Blogging! 📝✨**