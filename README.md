# Blogs App using Laravel



## ✨ Features

- **User Authentication:** Secure registration, login, and logout.
- **Blog Management:** Create, edit, and delete blog posts.
- **Post Interaction:** Comment on and like/unlike posts.
- **Notifications:** Receive notifications for new comments.
- **Responsive UI:** Clean interface built with Blade templates.
- **RESTful Routing:** Follows Laravel conventions.
- **API Endpoints:** Basic authentication and user info for API use.
- **Console Commands:** Inspiring quotes via Artisan.

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL (or compatible database)
- Node.js & npm (for frontend assets)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/youssef-ahmed-cs/Blogs-app-using-laravel.git
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

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Serve the application:**
   ```bash
   php artisan serve
   ```
   Visit [http://localhost:8000](http://localhost:8000) in your browser.

7. **Build frontend assets (optional):**
   ```bash
   npm run dev
   ```

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