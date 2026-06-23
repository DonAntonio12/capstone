<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Helpers\SystemHelper::getSiteName() }} - Smart Soil Analysis</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            <style>
        :root {
            --primary-color: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary-color: #f0fdf4;
            --accent-color: #34d399;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --gradient-primary: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --gradient-secondary: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            --gradient-hero: linear-gradient(135deg, rgba(5, 150, 105, 0.9) 0%, rgba(16, 185, 129, 0.8) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-secondary);
            overflow-x: hidden;
        }

        /* Modern Navigation */
        .nav-agro {
            position: fixed;
            width: 100%;
            z-index: 1000;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }

        .nav-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.025em;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-logo:hover {
            transform: scale(1.05);
        }

        .nav-logo .emoji {
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .nav-logo img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .nav-menu {
            display: flex;
            gap: 2.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            list-style: none;
        }

        .nav-menu a {
            color: var(--text-primary);
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .nav-menu a:hover {
            color: var(--primary-color);
        }

        .nav-btn {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            text-decoration: none;
            display: inline-block;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Modern Hero Section */
        .hero-agro {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.9) 0%, rgba(16, 185, 129, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80') center center/cover no-repeat;
            position: relative;
            padding: 120px 2rem 80px 2rem;
        }

        .hero-content-agro {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            letter-spacing: -0.025em;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 400;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 3rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-btns {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .hero-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            text-decoration: none;
            display: inline-block;
        }

        .hero-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Modern Sections */
        .section {
            max-width: 1280px;
            margin: 0 auto;
            padding: 100px 2rem 60px 2rem;
            background: var(--bg-primary);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        .section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
            letter-spacing: -0.025em;
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Modern Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.4s ease;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .feature-icon {
            background: var(--gradient-secondary);
            color: var(--primary-color);
            border-radius: 20px;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            font-size: 2.5rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .feature-desc {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Modern How It Works */
        .how-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            position: relative;
        }

        .how-step {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .how-step:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .how-step-number {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gradient-primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: var(--shadow-md);
        }

        .how-step-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            margin-top: 1rem;
        }

        .how-step-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .how-step-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Modern About Section */
        .about-section {
            background: var(--bg-primary);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            max-width: 1280px;
            margin: 4rem auto 0 auto;
            padding: 80px 2rem 60px 2rem;
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .about-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: -0.025em;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-secondary);
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: center;
        }

        .about-content p {
            margin-bottom: 1.5rem;
        }

        .about-content b {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Modern Contact Form */
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--bg-primary);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-submit {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            width: 100%;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Modern Footer */
        .footer {
            width: 100%;
            background: var(--text-primary);
            color: white;
            text-align: center;
            padding: 3rem 0 2rem 0;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 0 2rem;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-light);
            margin-bottom: 1rem;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .nav-content {
                padding: 1rem 1.5rem;
            }
            
            .nav-menu {
                gap: 2rem;
            }
            
            .section, .about-section {
                margin-top: 3rem;
                padding: 80px 1.5rem 50px 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .nav-menu {
                gap: 1.5rem;
                font-size: 0.9rem;
            }
            
            .hero-agro {
                padding: 100px 1rem 60px 1rem;
            }
            
            .hero-btns {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            
            .hero-btn {
                width: 100%;
                max-width: 300px;
            }
            
            .section, .about-section {
                margin-top: 2rem;
                padding: 60px 1rem 40px 1rem;
                border-radius: 16px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .how-steps {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .contact-form {
                padding: 2rem 1.5rem;
            }
            
            .footer-content {
                padding: 0 1rem;
            }
            
            .footer-links {
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .nav-menu {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .section-title, .about-title {
                font-size: 1.8rem;
            }
            
            .feature-card {
                padding: 2rem 1.5rem;
            }
            
            .how-step {
                padding: 1.5rem 1rem;
            }
        }

        /* Scroll animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Loading states */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Focus states for accessibility */
        .nav-btn:focus,
        .hero-btn:focus,
        .form-input:focus,
        .form-submit:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
            </style>
    </head>
<body>
    <!-- Navigation -->
    <nav class="nav-agro">
        <div class="nav-content">
            <div class="nav-logo">
                @if(\App\Helpers\SystemHelper::getLogoUrl())
                    <img src="{{ \App\Helpers\SystemHelper::getLogoUrl() }}" alt="Logo">
                @else
                    <span class="emoji">🌱</span>
                @endif
                {{ \App\Helpers\SystemHelper::getSiteName() }}
            </div>
            <div class="nav-menu">
                <a href="#home">Home</a>
                <a href="#features">Features</a>
                <a href="#how">How It Works</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
            <div>
                <a href="{{ url('/admin/login') }}" class="nav-btn">Admin Login</a>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero-agro" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content-agro">
            <div class="hero-title">It's Best Agricultural Platform</div>
            <div class="hero-subtitle">Empowering farmers and agri-professionals with smart technology for sustainable agriculture. Unlock the power of data-driven farming with real-time monitoring, AI insights, and actionable recommendations.</div>
            <div class="hero-btns">
                <button class="hero-btn" onclick="window.location.href='{{ route('register') }}'">Get Started</button>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section class="section" id="features">
        <div class="section-subtitle">Why {{ \App\Helpers\SystemHelper::getSiteName() }}?</div>
        <div class="section-title">Features</div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">🌱</div>
                <div class="feature-title">Real-time Monitoring</div>
                <div class="feature-desc">Get instant updates on your soil's NPK levels with advanced sensor technology and IoT connectivity.</div>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">🤖</div>
                <div class="feature-title">AI Predictions</div>
                <div class="feature-desc">Leverage artificial intelligence to predict future soil conditions and optimize crop growth patterns.</div>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">🌾</div>
                <div class="feature-title">Smart Recommendations</div>
                <div class="feature-desc">Receive personalized recommendations for soil improvement and crop management strategies.</div>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Data Analytics</div>
                <div class="feature-desc">Track and analyze your farm's performance with detailed reports and actionable insights.</div>
            </div>
        </div>
    </section>
    <!-- How It Works Section -->
    <section class="section" id="how">
        <div class="section-subtitle">Simple steps to get started</div>
        <div class="section-title">How It Works</div>
        <div class="how-steps">
            <div class="how-step fade-in">
                <div class="how-step-number">1</div>
                <div class="how-step-icon">📝</div>
                <div class="how-step-title">Register</div>
                <div class="how-step-desc">Create your {{ \App\Helpers\SystemHelper::getSiteName() }} account and set up your profile to get started.</div>
            </div>
            <div class="how-step fade-in">
                <div class="how-step-number">2</div>
                <div class="how-step-icon">🧪</div>
                <div class="how-step-title">Test Soil</div>
                <div class="how-step-desc">Collect and test your soil samples using our advanced sensor technology.</div>
            </div>
            <div class="how-step fade-in">
                <div class="how-step-number">3</div>
                <div class="how-step-icon">🤖</div>
                <div class="how-step-title">Get AI Results</div>
                <div class="how-step-desc">Receive instant AI-powered analysis and personalized recommendations.</div>
            </div>
            <div class="how-step fade-in">
                <div class="how-step-number">4</div>
                <div class="how-step-icon">🌾</div>
                <div class="how-step-title">Improve Farm</div>
                <div class="how-step-desc">Apply insights to boost your yield, improve soil health, and maximize productivity.</div>
            </div>
        </div>
    </section>
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-title">About Us</div>
        <div class="about-content">
            <p>
                <b>{{ \App\Helpers\SystemHelper::getSiteName() }}</b> is dedicated to empowering farmers and agricultural professionals with smart technology for sustainable agriculture. Our platform combines real-time soil monitoring, AI-powered predictions, and actionable recommendations to help you make informed decisions and maximize your crop yields. With {{ \App\Helpers\SystemHelper::getSiteName() }}, you can confidently manage your land, improve productivity, and contribute to a greener future.
            </p>
        </div>
    </section>
    <!-- Contact Section -->
    <section class="section" id="contact">
        <div class="section-subtitle">Need help? Reach out to our support team.</div>
        <div class="section-title">Contact Us</div>
        <form class="contact-form" method="POST" action="#">
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" required class="form-input">
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" required class="form-input">
            </div>
            <div class="form-group">
                <label for="message" class="form-label">Message</label>
                <textarea id="message" name="message" rows="4" required class="form-input form-textarea"></textarea>
            </div>
            <button type="submit" class="form-submit">Send Message</button>
        </form>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                {{ \App\Helpers\SystemHelper::getSiteName() }}
            </div>
            <div class="footer-links">
                <a href="#home">Home</a>
                <a href="#features">Features</a>
                <a href="#how">How It Works</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} {{ \App\Helpers\SystemHelper::getSiteName() }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.nav-agro');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 12px rgba(0, 0, 0, 0.07)';
            }
        });

        // Contact form handling
        document.querySelector('.contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const message = formData.get('message');
            
            // Simple validation
            if (!name || !email || !message) {
                alert('Please fill in all fields.');
                return;
            }
            
            // Simulate form submission
            const submitBtn = this.querySelector('.form-submit');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Sending...';
            submitBtn.classList.add('loading');
            
            setTimeout(() => {
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
                submitBtn.textContent = originalText;
                submitBtn.classList.remove('loading');
            }, 2000);
        });

        // Add loading state to buttons
        document.querySelectorAll('.hero-btn, .nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.href && this.href.includes('register')) {
                    this.classList.add('loading');
                }
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-agro');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click effects to buttons
        document.querySelectorAll('button, .nav-btn, .hero-btn').forEach(btn => {
            btn.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            btn.addEventListener('mouseup', function() {
                this.style.transform = '';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    </script>

    </body>
</html>
