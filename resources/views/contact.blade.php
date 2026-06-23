@extends('layouts.user')

@section('title', 'Contact Us - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<style>
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
    
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
    
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .contact-form {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #228B22;
        box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .form-button {
        background: #228B22;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        font-size: 1rem;
    }
    
    .form-button:hover {
        background: #1a5f1a;
    }
    
    .contact-info {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .info-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: #228B22;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .info-content h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    
    .info-content p {
        color: #6b7280;
        line-height: 1.5;
    }
    
    .faq-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .faq-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    
    .faq-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .faq-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .faq-question {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .faq-answer {
        color: #6b7280;
        line-height: 1.6;
    }
    
    .office-hours {
        background: #f9fafb;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }
    
    .hours-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .hours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .hours-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .hours-item:last-child {
        border-bottom: none;
    }
    
    .day {
        font-weight: 500;
        color: #374151;
    }
    
    .time {
        color: #6b7280;
    }
    
    @media (max-width: 768px) {
        .page-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Contact Us</h1>
        <p class="page-subtitle">Get in touch with our team for support, questions, or partnership opportunities.</p>
    </div>

    <div class="contact-grid">
        <div class="contact-form">
            <h2 class="form-title">Send us a Message</h2>
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <select id="subject" name="subject" class="form-input" required>
                        <option value="">Select a subject</option>
                        <option value="general">General Inquiry</option>
                        <option value="technical">Technical Support</option>
                        <option value="billing">Billing Question</option>
                        <option value="partnership">Partnership</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-textarea" placeholder="Tell us how we can help you..." required></textarea>
                </div>
                
                <button type="submit" class="form-button">Send Message</button>
            </form>
        </div>

        <div class="contact-info">
            <h2 class="info-title">Get in Touch</h2>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Office Address</h3>
                    <p>123 Agriculture Street<br>Quezon City, Metro Manila<br>Philippines 1100</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h3>Phone Number</h3>
                    <p>+63 2 8123 4567<br>+63 917 123 4567</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email Address</h3>
                    <p>{{ \App\Helpers\SystemHelper::getContactEmail() ?: 'info@soilsense.ph' }}<br>{{ \App\Helpers\SystemHelper::getContactEmail() ?: 'support@soilsense.ph' }}</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 3:00 PM</p>
                </div>
            </div>
        </div>
    </div>

    <div class="faq-section">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <div class="faq-question">How accurate are the soil analysis results?</div>
            <div class="faq-answer">Our soil analysis system achieves 95% accuracy through advanced sensor technology and AI-powered algorithms. We continuously calibrate our sensors and update our models to maintain high precision.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">What types of crops can the system recommend?</div>
            <div class="faq-answer">Our system can recommend crops for various soil types including rice, corn, vegetables, root crops, and more. The recommendations are based on your specific soil conditions and local climate data.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">How long does it take to get soil test results?</div>
            <div class="faq-answer">Real-time soil testing provides immediate results for basic NPK levels. For comprehensive analysis including detailed recommendations, results are typically available within 24-48 hours.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">Is technical support available for farmers?</div>
            <div class="faq-answer">Yes, we provide comprehensive technical support including on-site training, phone support, and online resources. Our team includes agricultural experts who can help with implementation and troubleshooting.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">Can I export my soil data for record keeping?</div>
            <div class="faq-answer">Absolutely! You can export your soil data in various formats including PDF reports, CSV files, and integrate with other farm management systems for comprehensive record keeping.</div>
        </div>
    </div>

    <div class="office-hours">
        <h3 class="hours-title">Office Hours</h3>
        <div class="hours-grid">
            <div class="hours-item">
                <span class="day">Monday</span>
                <span class="time">8:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Tuesday</span>
                <span class="time">8:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Wednesday</span>
                <span class="time">8:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Thursday</span>
                <span class="time">8:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Friday</span>
                <span class="time">8:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Saturday</span>
                <span class="time">9:00 AM - 3:00 PM</span>
            </div>
            <div class="hours-item">
                <span class="day">Sunday</span>
                <span class="time">Closed</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
@endsection 