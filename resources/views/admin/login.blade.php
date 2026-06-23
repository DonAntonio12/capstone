<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ \App\Helpers\SystemHelper::getSiteName() }}</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body {
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') center center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Figtree', sans-serif;
        }
        .admin-login-card {
            background: rgba(34,34,34,0.85);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            color: #FFD600;
            backdrop-filter: blur(6px);
        }
        .admin-login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 2px;
        }
        .admin-login-label {
            color: #FFD600;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .admin-login-input {
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            border: none;
            margin-bottom: 1.2rem;
            background: #222;
            color: #FFD600;
            font-size: 1rem;
        }
        .admin-login-btn {
            width: 100%;
            background: #FFD600;
            color: #222;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            padding: 0.9rem 0;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .admin-login-btn:hover {
            background: #FBBF24;
        }
        .admin-login-error {
            color: #ff4d4f;
            background: #fff3f3;
            border-radius: 6px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <form class="admin-login-card" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="admin-login-title">Admin Login</div>
        @if($errors->any())
            <div class="admin-login-error">
                {{ $errors->first() }}
            </div>
        @endif
        <label class="admin-login-label" for="email">Email</label>
        <input class="admin-login-input" type="email" name="email" id="email" required autofocus>
        <label class="admin-login-label" for="password">Password</label>
        <input class="admin-login-input" type="password" name="password" id="password" required>
        <button class="admin-login-btn" type="submit">Login</button>
    </form>
</body>
</html> 