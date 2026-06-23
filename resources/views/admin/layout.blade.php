<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ \App\Helpers\SystemHelper::getSiteName() }}</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background: #181f1b;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #181f1b;
            box-shadow: 2px 0 24px rgba(34,139,34,0.10);
            display: flex;
            flex-direction: column;
            padding: 2.2rem 1.2rem 1.2rem 1.2rem;
            min-height: 100vh;
            border-top-right-radius: 24px;
            border-bottom-right-radius: 24px;
            border-right: 1.5px solid #1e2b22;
        }
        .sidebar-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #6ee7b7;
            margin-bottom: 2.2rem;
            letter-spacing: 2px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
        }
        .sidebar-logo {
            background: linear-gradient(135deg, #228B22 60%, #6ee7b7 100%);
            color: #fff;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 2px 8px rgba(34,139,34,0.10);
        }
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.08rem;
            font-weight: 600;
            color: #f3fdf7;
            text-decoration: none;
            padding: 0.7rem 1rem;
            border-radius: 12px;
            transition: background 0.18s, color 0.18s, box-shadow 0.16s;
            background: transparent;
            border: none;
            outline: none;
            position: relative;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, #228B22 80%, #1e2b22 100%);
            color: #6ee7b7;
            box-shadow: 0 2px 12px rgba(34,139,34,0.13);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 60%;
            border-radius: 6px;
            background: #6ee7b7;
        }
        .sidebar-icon {
            font-size: 1.3rem;
            color: #6ee7b7;
            background: #1e2b22;
            border-radius: 50%;
            padding: 0.4rem;
            transition: background 0.18s, color 0.18s;
        }
        .sidebar-link:hover .sidebar-icon, .sidebar-link.active .sidebar-icon {
            background: #228B22;
            color: #fff;
        }
        .main-content {
            flex: 1;
            padding: 2.5rem 3rem;
            background: #181f1b;
            min-height: 100vh;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
            box-shadow: 0 0 0 rgba(0,0,0,0);
            color: #f3fdf7;
        }
        @media (max-width: 900px) {
            .main-content { padding: 1.2rem 0.5rem; }
            .sidebar { width: 100px; padding: 1rem 0.3rem; }
            .sidebar-title { font-size: 1.1rem; }
            .sidebar-link { font-size: 0.9rem; padding: 0.5rem 0.5rem; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-title">
            <span class="sidebar-logo">
                @if(\App\Helpers\SystemHelper::getLogoUrl())
                    <img src="{{ \App\Helpers\SystemHelper::getLogoUrl() }}" alt="Logo" style="width:24px;height:24px;border-radius:50%;">
                @else
                    <i class="fas fa-leaf"></i>
                @endif
            </span>
            {{ \App\Helpers\SystemHelper::getSiteName() }} Admin
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link{{ Request::is('admin/dashboard*') ? ' active' : '' }}"><span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link{{ Request::is('admin/users*') ? ' active' : '' }}"><span class="sidebar-icon"><i class="fas fa-users"></i></span> User Management</a>
            <a href="{{ route('admin.soil.index') }}" class="sidebar-link{{ Request::is('admin/soil-data*') ? ' active' : '' }}"><span class="sidebar-icon"><i class="fas fa-seedling"></i></span> Soil Data</a>
            <a href="{{ route('admin.settings') }}" class="sidebar-link{{ Request::is('admin/settings*') ? ' active' : '' }}"><span class="sidebar-icon"><i class="fas fa-cogs"></i></span> System Settings</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:auto;">
            @csrf
            <button type="submit" class="sidebar-link" style="width:100%;border:none;background:none;display:flex;align-items:center;gap:0.8rem;font-size:1.08rem;font-weight:600;cursor:pointer;padding:0.7rem 1rem;border-radius:10px;">
                <span class="sidebar-icon"><i class="fas fa-sign-out-alt"></i></span> Logout
            </button>
        </form>
            
            
        </nav>
        
    </div>
    <div class="main-content">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html> 