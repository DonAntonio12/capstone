@extends('layouts.user')

@section('title', 'About Us - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<style>
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .page-subtitle {
        font-size: 1.1rem;
        color: #6b7280;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .content-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .section-content {
        color: #374151;
        line-height: 1.7;
        font-size: 1rem;
    }
    
    .mission-vision {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .mission-card,
    .vision-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .card-content {
        color: #374151;
        line-height: 1.6;
    }
    
    .team-section {
        margin-top: 3rem;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .team-member {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .member-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #228B22;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
    }
    
    .member-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .member-role {
        color: #228B22;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .member-bio {
        color: #6b7280;
        line-height: 1.6;
    }
    
    .stats-section {
        background: #f9fafb;
        border-radius: 12px;
        padding: 2rem;
        margin: 2rem 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #228B22;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .page-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .mission-vision {
            grid-template-columns: 1fr;
        }
        
        .team-grid {
            grid-template-columns: 1fr;
        }
    }

    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
</style>
@endsection

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">About {{ \App\Helpers\SystemHelper::getSiteName() }}</h1>
        <p class="page-subtitle">Empowering farmers with intelligent soil analysis and data-driven agricultural insights for sustainable farming practices.</p>
    </div>

    <div class="content-section">
        <h2 class="section-title">Our Story</h2>
        <div class="section-content">
            <p>{{ \App\Helpers\SystemHelper::getSiteName() }} was born from a simple yet powerful vision: to democratize access to advanced soil analysis technology for farmers of all scales. Founded by a team of agricultural scientists, data analysts, and technology enthusiasts, we recognized the critical gap between traditional farming methods and modern precision agriculture.</p>
            <br>
            <p>Our journey began with a simple question: "How can we help farmers make better decisions about their soil health?" This led to the development of our comprehensive soil analysis platform that combines cutting-edge sensor technology with artificial intelligence to provide real-time insights into soil conditions.</p>
        </div>
    </div>

    <div class="mission-vision">
        <div class="mission-card">
            <h3 class="card-title">Our Mission</h3>
            <div class="card-content">
                To provide farmers with accurate, real-time soil analysis and predictive insights that enable sustainable farming practices, increase crop yields, and reduce environmental impact through data-driven decision making.
            </div>
        </div>
        <div class="vision-card">
            <h3 class="card-title">Our Vision</h3>
            <div class="card-content">
                To become the leading platform for intelligent soil management, empowering farmers worldwide to achieve optimal crop production while preserving soil health for future generations.
            </div>
        </div>
    </div>

    <div class="stats-section">
        <h2 class="section-title">Our Impact</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Active Farmers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10,000+</div>
                <div class="stat-label">Soil Tests Analyzed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">95%</div>
                <div class="stat-label">Accuracy Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">30%</div>
                <div class="stat-label">Average Yield Increase</div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h2 class="section-title">Our Technology</h2>
        <div class="section-content">
            <p>{{ \App\Helpers\SystemHelper::getSiteName() }} leverages advanced sensor technology and artificial neural networks (ANN) to provide comprehensive soil analysis. Our system integrates multiple data points including nitrogen, phosphorus, and potassium levels, soil temperature, moisture content, and environmental factors to deliver accurate predictions and recommendations.</p>
            <br>
            <p>Key features of our technology include:</p>
            <ul style="margin: 1rem 0; padding-left: 2rem;">
                <li>Real-time soil monitoring and analysis</li>
                <li>AI-powered crop recommendations</li>
                <li>Predictive analytics for soil health trends</li>
                <li>Mobile-friendly interface for field access</li>
                <li>Comprehensive data visualization and reporting</li>
            </ul>
        </div>
    </div>

    <div class="team-section">
        <h2 class="section-title">Meet Our Team</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Kenneth James Remo Antonio</div>
                <div class="member-role">Chief Agricultural Scientist</div>
                <div class="member-bio">PhD in Soil Science with 15+ years of experience in precision agriculture and sustainable farming practices.</div>
            </div>
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Kenneth James Remo Antonio</div>
                <div class="member-role">Lead Data Scientist</div>
                <div class="member-bio">Specialist in machine learning and AI applications for agricultural data analysis and prediction modeling.</div>
            </div>
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Kenneth James Remo Antonio</div>
                <div class="member-role">Product Manager</div>
                <div class="member-bio">Expert in user experience design and agricultural technology product development with focus on farmer needs.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
@endsection 