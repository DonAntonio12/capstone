<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Helpers\SystemHelper::getSiteName())</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafafa;
            margin: 0;
            min-height: 100vh;
            color: #1f2937;
            /* Move doodle pattern here for all pages */
            background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
            background-repeat: repeat;
            background-size: 220px 110px;
        }
        
        .navbar {
            width: 100%;
            background: #059669;
            border-bottom: 1px solid #f3f4f6;
            color: #fff;
            padding: 0;
            box-shadow: 0 2px 24px 0 rgba(0,0,0,0.10);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            overflow: visible;
        }
        
        .navbar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            opacity: 0.18;
            pointer-events: none;
            background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke="white" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
            background-repeat: repeat;
            background-size: 220px 110px;
        }
        
        .navbar-content {
            width: 100%;
            max-width: 1400px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2rem;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .navbar-logo {
            display: flex;
            align-items: center;
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.025em;
            transition: color 0.2s ease;
            position: relative;
        }
        
        .navbar-logo::before {
            content: '🌱';
            position: absolute;
            top: -10px;
            right: -15px;
            font-size: 1rem;
            opacity: 0.9;
            animation: wiggle 4s ease-in-out infinite;
        }
        
        .navbar-logo::after {
            content: '🌿';
            position: absolute;
            bottom: -8px;
            left: -5px;
            font-size: 0.8rem;
            opacity: 0.8;
            animation: sway 5s ease-in-out infinite;
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(8deg); }
            75% { transform: rotate(-8deg); }
        }
        
        @keyframes sway {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }
        
        .navbar-logo:hover {
            color: #d1fae5;
        }
        
        .navbar-links {
            display: flex;
            gap: 3rem;
            align-items: center;
            position: relative;
        }
        
        .navbar-links::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.4), transparent);
            border-radius: 2px;
        }
        
        .navbar-links::after {
            content: '🌾';
            position: absolute;
            top: -20px;
            right: 20%;
            font-size: 0.9rem;
            opacity: 0.8;
            animation: wave 3s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }
        
        .navbar-links a {
            text-decoration: none;
            color: #e0f2f1;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            padding: 0.75rem 0;
            position: relative;
            letter-spacing: -0.025em;
        }
        
        .navbar-links a:hover {
            color: #fff;
        }
        
        .navbar-links a.active {
            color: #fff;
            font-weight: 600;
        }
        
        .navbar-links a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #fff;
            border-radius: 1px;
        }
        
        .navbar-links a:hover::before {
            content: '🌿';
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            opacity: 0.9;
            animation: bounce 0.6s ease-in-out;
        }
        
        .navbar-links a:nth-child(2):hover::before {
            content: '🌱';
        }
        
        .navbar-links a:nth-child(3):hover::before {
            content: '🌾';
        }
        
        .navbar-links a:nth-child(4):hover::before {
            content: '🌻';
        }
        
        .navbar-links a:nth-child(5):hover::before {
            content: '📞';
        }
        
        .navbar-links a:nth-child(6):hover::before {
            content: '👤';
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-8px); }
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #e0f2f1;
            position: relative;
        }
        
        .navbar-user::before {
            content: '🌱';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            opacity: 0.8;
            animation: float 4s ease-in-out infinite;
        }
        
        .navbar-user::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.3));
            border-radius: 1px;
        }
        
        .navbar-user span {
            font-weight: 500;
            color: #fff;
        }
        
        .navbar-logout {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.025em;
            position: relative;
            overflow: hidden;
        }
        
        .navbar-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .navbar-logout:hover::before {
            left: 100%;
        }
        
        .navbar-logout:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .navbar-logout:active {
            transform: translateY(0);
        }
        
        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
            padding-top: 72px; /* height of fixed navbar */
        }
        
        @media (max-width: 1024px) {
            .navbar-links {
                gap: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .navbar-links {
                gap: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .navbar-user {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .navbar::after,
            .navbar::before {
                display: none;
            }
            
            .navbar-links::after {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .navbar-links {
                gap: 1rem;
            }
            
            .navbar-links a {
                font-size: 0.875rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-logo">
                @if(\App\Helpers\SystemHelper::getLogoUrl())
                    <img src="{{ \App\Helpers\SystemHelper::getLogoUrl() }}" alt="Logo" style="width:32px;height:32px;margin-right:12px;border-radius:50%;">
                @else
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right:12px; color:currentColor;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                {{ \App\Helpers\SystemHelper::getSiteName() }}
            </div>
            <div class="navbar-links">
                <a href="/dashboard" @if(request()->is('dashboard')) class="active" @endif>Home</a>
                <a href="/testing" @if(request()->is('testing')) class="active" @endif>Soil Testing</a>
                <a href="/history" @if(request()->is('history')) class="active" @endif>History</a>
                <a href="/about" @if(request()->is('about')) class="active" @endif>About Us</a>
                <a href="/contact" @if(request()->is('contact')) class="active" @endif>Contact</a>
                <a href="/profile" @if(request()->is('profile')) class="active" @endif>Profile</a>
            </div>
            <div class="navbar-user">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form method="POST" action="/logout" style="margin:0;">
                    @csrf
                    <button type="submit" class="navbar-logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer style="width:100%;background:#059669;color:#e0f2f1;text-align:center;padding:1.2rem 0 1rem 0;font-size:0.98rem;border-top:1px solid #e5e7eb;box-shadow:0 -1px 6px rgba(0,0,0,0.04);margin-top:2rem;letter-spacing:0.01em;">
        <div style="max-width:1400px;margin:0 auto;display:flex;flex-direction:column;align-items:center;gap:0.3rem;">
            <div>
                &copy; {{ date('Y') }} {{ \App\Helpers\SystemHelper::getSiteName() }}. All rights reserved.
            </div>
            <div>
                <a href="/contact" style="color:#bbf7d0;text-decoration:underline;font-weight:500;">Contact Us</a>
            </div>
        </div>
    </footer>
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html> 