<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ \App\Helpers\SystemHelper::getSiteName() }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2232&q=80') center center/cover no-repeat fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.20);
            padding: 2.5rem 2rem 2rem 2rem;
            width: 100%;
            max-width: 500px;
            margin: 2rem 1rem;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .register-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #222;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .register-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.3rem;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.4rem;
        }
        .form-input {
            width: 100%;
            padding: .8rem 1.5rem;
            margin-right: 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 7px;
            font-size: 1rem;
            font-family: inherit;
            transition: border 0.2s;
            background: rgba(255, 255, 255, 0.9);
        }
        .form-input:focus {
            border-color: #FFD600;
            outline: none;
            box-shadow: 0 0 0 2px #fffbe6;
            background: #fff;
        }
        .form-btn {
            width: 100%;
            background: #FFD600;
            color: #222;
            font-weight: 700;
            border: none;
            border-radius: 7px;
            padding: 0.95rem 0;
            font-size: 1.1rem;
            margin-top: 0.5rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255,214,0,0.08);
            transition: background 0.2s;
        }
        .form-btn:hover {
            background: #FBBF24;
        }
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.98rem;
        }
        .form-footer a {
            color: #FFD600;
            text-decoration: none;
            font-weight: 600;
        }
        .error-message {
            color: #e53e3e;
            background: rgba(255, 240, 240, 0.9);
            border: 1px solid #fbb;
            border-radius: 5px;
            padding: 0.6rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.98rem;
        }
        @media (max-width: 500px) {
            .register-card { 
                padding: 1.5rem 0.5rem; 
                margin: 1rem 0.5rem;
            }
            .register-title { font-size: 1.4rem; }
        }
        @media (min-width: 700px) {
            .register-card { max-width: 880px !important; box-sizing: border-box; overflow: hidden; }
            .register-form-grid {
                display: grid;
                grid-template-columns: 1fr 2.5rem 1fr;
                gap: .5rem 1.5rem;
                max-width: 96%;
                margin: 0 auto;
            }
            .register-form-grid .form-group { margin-bottom: 0.7rem; }
            .register-form-grid .form-input { width: 100%; box-sizing: border-box; }
        }
        @media (max-width: 699px) {
            .register-form-grid { display: block; }
        }
    </style>
</head>
<body style="
    font-family: 'Figtree', sans-serif;
    background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2232&q=80') center center/cover no-repeat fixed;
    min-height: 100vh;
    margin: 0;
">
    <!-- Navigation -->
    <nav class="nav-agro" style="position:fixed;width:100%;z-index:50;top:0;left:0;background:#fff;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="nav-content" style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:1.2rem 2rem;">
            <div class="nav-logo" style="display:flex;align-items:center;font-size:1.7rem;font-weight:800;color:#059669;letter-spacing:2px;gap:0.5rem;">
                @if(\App\Helpers\SystemHelper::getLogoUrl())
                    <img src="{{ \App\Helpers\SystemHelper::getLogoUrl() }}" alt="Logo" style="width:32px;height:32px;border-radius:50%;">
                @else
                    <span class="emoji" style="font-size:2rem;">🌱</span>
                @endif
                {{ \App\Helpers\SystemHelper::getSiteName() }}
            </div>
            <div class="nav-menu" style="display:flex;gap:2rem;font-size:1rem;font-weight:500;">
                <a href="/" style="color:#222;text-decoration:none;transition:color 0.2s;">Home</a>
                <a href="/welcome#features" style="color:#222;text-decoration:none;transition:color 0.2s;">Features</a>
                <a href="/welcome#how" style="color:#222;text-decoration:none;transition:color 0.2s;">How It Works</a>
                <a href="/welcome#about" style="color:#222;text-decoration:none;transition:color 0.2s;">About</a>
                <a href="/welcome#contact" style="color:#222;text-decoration:none;transition:color 0.2s;">Contact</a>
            </div>
            <div>
                <a href="{{ url('/admin/login') }}" class="nav-btn" style="background:#059669;color:#fff;font-weight:700;border:none;border-radius:6px;padding:0.7rem 1.5rem;font-size:1rem;cursor:pointer;transition:background 0.2s;">Admin Login</a>
            </div>
        </div>
    </nav>
    <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding-top:90px;padding-bottom:30px;">
        <div class="register-card" style="background:rgba(255,255,255,0.97);backdrop-filter:blur(8px);border-radius:18px;box-shadow:0 8px 32px rgba(0,0,0,0.13);padding:2rem 1.5rem 1.5rem 1.5rem;width:100%;max-width:420px;margin:0 1rem;position:relative;z-index:2;border:1px solid #e5e7eb;">
            <div class="register-title" style="font-size:2.2rem;font-weight:800;color:#059669;text-align:center;margin-bottom:0.5rem;">Create Account</div>
            <div class="register-subtitle" style="color:#666;text-align:center;margin-bottom:2rem;">Sign up to start using {{ \App\Helpers\SystemHelper::getSiteName() }}</div>
            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin:0; padding-left:1.1em;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}" autocomplete="off" class="register-form-grid">
            @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="e.g. Juan Dela Cruz">
                </div>
                <div></div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username" placeholder="e.g. juan@email.com">
                </div>
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <input id="address" type="text" name="address" class="form-input" value="{{ old('address') }}" required placeholder="e.g. Brgy. Example, City, Province">
                </div>
                <div></div>
                <div class="form-group">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input id="contact_number" type="tel" name="contact_number" class="form-input" value="{{ old('contact_number') }}" required pattern="[0-9]{11}" maxlength="11" placeholder="e.g. 09171234567">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Enter password">
                </div>
                <div></div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Re-enter password">
                </div>
                <div style="grid-column:1/-1;margin-top:0.7rem;">
                    <button type="submit" class="form-btn" style="width:100%;background:#059669;color:#fff;font-weight:700;border:none;border-radius:7px;padding:0.85rem 0;font-size:1.1rem;margin-top:0;cursor:pointer;box-shadow:0 2px 8px rgba(5,150,105,0.08);transition:background 0.2s;">Register</button>
                </div>
            </form>
            <div class="form-footer" style="text-align:center;margin-top:1.5rem;font-size:0.98rem;">
                Already registered?
                <a href="{{ route('login') }}" style="color:#059669;text-decoration:none;font-weight:600;">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
