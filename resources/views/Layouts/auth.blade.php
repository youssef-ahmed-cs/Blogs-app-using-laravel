<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: radial-gradient(ellipse at center, rgba(127,0,173,0.6) 0%,
          rgba(0,0,0,0.8) 60%, 
          rgba(0,0,0,1) 90%), 
          url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/31787/stars.jpg);
            background-size: cover;
            z-index:-5;
            color: white;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        .auth-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #FF11A7;
            border-radius: 10px;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 20px #FF11A7;
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        h2 {
            font-family: 'Monoton', monospace;
            margin-bottom: 1.5rem;
            text-align: center;
            animation: neon1 1.5s ease-in-out infinite alternate;
        }

        label {
            color: #ddd;
            margin-bottom: 0.25rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid #FF11A7;
            color: white;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #FF11A7;
            box-shadow: 0 0 0 0.2rem rgba(255, 17, 167, 0.25);
            color: white;
        }

        .btn-primary {
            background: transparent;
            border: 2px solid #FF11A7;
            color: white;
            font-family: 'Monoton', monospace;
            letter-spacing: 2px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #FF11A7;
            box-shadow: 0 0 10px #FF11A7, 0 0 40px #FF11A7;
            border-color: #FF11A7;
        }

        .bottom-links {
            margin-top: 1.5rem;
            text-align: center;
        }

        .bottom-links a {
            color: #FF11A7;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .bottom-links a:hover {
            text-shadow: 0 0 10px #FF11A7;
        }

        #layer-0 {
            background: rgba(92,71,255,0);
            background: -moz-linear-gradient(top, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 25%, rgba(255,71,255,1) 100%);
            background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(92,71,255,0)), color-stop(25%, rgba(0,0,0,1)), color-stop(100%, rgba(255,71,255,1)));
            background: -webkit-linear-gradient(top, rgba(0,0,0,1)) 0%, rgba(0,0,0,1) 25%, rgba(255,71,255,1) 100%);
            background: -o-linear-gradient(top, rgba(0,0,0,1) 0%, rgba(0,0,0,1)) 25%, rgba(255,71,255,1) 100%);
            background: -ms-linear-gradient(top, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 25%, rgba(255,71,255,1) 100%);
            background: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 25%, rgba(255,71,255,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5c47ff', endColorstr='#ff47ff', GradientType=0 );
            height:400px;
            width:200vw;
            opacity:1;
            position:absolute;
            bottom:0;
            left:0;
            right: 0;
            margin: 0 -50%;
            overflow: hidden;
            transform: perspective(200px) rotateX(60deg);
            z-index: -5;
        }

        #lines {
            background-size: 40px 40px;    
            background-image: repeating-linear-gradient(0deg, #60DCD3, #60DCD3 2px, transparent 1px, transparent 40px),repeating-linear-gradient(-90deg, #60DCD3, #60DCD3 2px, transparent 2px, transparent 40px);
            height:400px;
            width:100%;
            opacity:0.2;
            position:absolute;
            top:0;
            left:0;
            z-index:-4;
        }

        @keyframes neon1 {
            from {
                text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #FF11A7, 0 0 70px #FF11A7, 0 0 80px #FF11A7, 0 0 100px #FF11A7, 0 0 150px #FF1177;
            }
            to {
                text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff, 0 0 20px #FF11A7, 0 0 35px #FF11A7, 0 0 40px #FF11A7, 0 0 50px #FF11A7, 0 0 75px #FF11A7;
            }
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 10px #FF11A7;
            }
            to {
                box-shadow: 0 0 20px #FF11A7, 0 0 30px #FF11A7;
            }
        }

        /* Background circles */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 17, 167, 0.1);
            animation: pulse 3s infinite;
            z-index: -3;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.5;
            }
            100% {
                transform: scale(1);
                opacity: 0.3;
            }
        }

        /* Creating multiple circles */
        .bg-circle:nth-child(1) { top: 20%; left: 10%; width: 120px; height: 120px; }
        .bg-circle:nth-child(2) { top: 40%; left: 80%; width: 160px; height: 160px; animation-delay: 0.3s; }
        .bg-circle:nth-child(3) { top: 70%; left: 30%; width: 200px; height: 200px; animation-delay: 0.6s; }
        .bg-circle:nth-child(4) { top: 10%; left: 60%; width: 80px; height: 80px; animation-delay: 0.9s; }
    </style>
</head>
<body>
    <div id="lines"></div>
    <div id="layer-0"></div>
    
    <!-- Background circles -->
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    
    <div class="auth-container">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>