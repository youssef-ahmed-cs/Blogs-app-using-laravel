<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hello {{ $user->name }}, 👋</h1>

    <p>We're thrilled to have you on board!</p>

    <p>Welcome to <strong>Our Platform</strong> — a place where innovation meets simplicity. Whether you're here to
        explore, learn, or collaborate, we're committed to giving you the best experience possible.</p>

    <p>Here are a few things you can do now:</p>
    <ul>
        <li>🧭 Explore your personalized dashboard</li>
        <li>📚 Access helpful guides and resources</li>
        <li>🛠 Customize your profile and preferences</li>
    </ul>

    <p>Let’s get you started on your journey!</p>

    <a href="{{ url('/') }}" class="btn">Go to Dashboard</a>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Our Platform. All rights reserved.</p>
    </div>
</div>
</body>
</html>
