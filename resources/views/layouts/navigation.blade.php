<nav x-data="{ open: false }" class="modern-navbar">
    <style>
        .modern-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }
        
        .modern-navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-brand:hover {
            transform: scale(1.05);
        }
        
        .nav-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .nav-brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #059669;
            letter-spacing: -0.025em;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-link {
            color: #1f2937;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 0;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: #059669;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid rgba(5, 150, 105, 0.2);
            border-radius: 12px;
            color: #059669;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-trigger:hover {
            background: rgba(5, 150, 105, 0.15);
            border-color: rgba(5, 150, 105, 0.3);
            transform: translateY(-1px);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .dropdown-icon {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }
        
        .user-trigger[aria-expanded="true"] .dropdown-icon {
            transform: rotate(180deg);
        }
        
        .mobile-menu-btn {
            display: none;
            padding: 0.5rem;
            background: transparent;
            border: none;
            border-radius: 8px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
        }
        
        .mobile-menu-icon {
            width: 24px;
            height: 24px;
        }
        
        .mobile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(229, 231, 235, 0.5);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-nav-link {
            display: block;
            padding: 0.75rem 2rem;
            color: #1f2937;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background: rgba(5, 150, 105, 0.05);
            border-left-color: #059669;
            color: #059669;
        }
        
        .mobile-user-section {
            border-top: 1px solid rgba(229, 231, 235, 0.5);
            padding: 1rem 2rem;
            margin-top: 1rem;
        }
        
        .mobile-user-info {
            margin-bottom: 1rem;
        }
        
        .mobile-user-name {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        
        .mobile-user-email {
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }
            
            .nav-content {
                height: 60px;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-brand-text {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 480px) {
            .nav-container {
                padding: 0 0.75rem;
            }
            
            .nav-brand-text {
                font-size: 1.2rem;
            }
        }
    </style>
    
    <!-- Primary Navigation Menu -->
    <div class="nav-container">
        <div class="nav-content">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="nav-brand">
                    <x-application-logo class="nav-logo fill-current text-green-600" />
                    <span class="nav-brand-text">{{ \App\Helpers\SystemHelper::getSiteName() }}</span>
                </a>

                <!-- Navigation Links -->
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        {{ __('Home') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="user-dropdown">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-trigger" type="button">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="dropdown-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = ! open" class="mobile-menu-btn">
                <svg class="mobile-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'mobile-menu open': open, 'mobile-menu': !open}" class="mobile-menu">
        <div>
            <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                {{ __('Dashboard') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="mobile-user-section">
            <div class="mobile-user-info">
                <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="mobile-user-email">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="mobile-nav-link"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.modern-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            const navbar = document.querySelector('.modern-navbar');
            const mobileMenuBtn = navbar.querySelector('.mobile-menu-btn');
            const mobileMenu = navbar.querySelector('.mobile-menu');
            
            if (!navbar.contains(e.target) && mobileMenu.classList.contains('open')) {
                // Close mobile menu
                Alpine.store('mobileMenuOpen', false);
            }
        });
        
        // Add smooth transitions to mobile menu links
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                // Close mobile menu after link click
                setTimeout(() => {
                    Alpine.store('mobileMenuOpen', false);
                }, 150);
            });
        });
    </script>
</nav>
