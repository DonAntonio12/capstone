@extends('layouts.user')

@section('title', 'Profile - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<style>
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
    
    .page-container {
        max-width: 1000px;
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
    
    .profile-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .profile-sidebar {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: fit-content;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #228B22;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 1.5rem;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    
    .profile-email {
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .profile-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #228B22;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .action-button {
        background: #228B22;
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        text-decoration: none;
        text-align: center;
    }
    
    .action-button:hover {
        background: #1a5f1a;
    }
    
    .action-button.secondary {
        background: transparent;
        color: #228B22;
        border: 1px solid #228B22;
    }
    
    .action-button.secondary:hover {
        background: #228B22;
        color: white;
    }
    
    .profile-content {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .content-tabs {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .tab-button {
        background: none;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: color 0.2s ease;
        border-bottom: 2px solid transparent;
    }
    
    .tab-button.active {
        color: #228B22;
        border-bottom-color: #228B22;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
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
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #228B22;
        box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.1);
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
    
    .danger-zone {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .danger-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #dc2626;
        margin-bottom: 0.5rem;
    }
    
    .danger-text {
        color: #6b7280;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    .danger-button {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .danger-button:hover {
        background: #b91c1c;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
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
    
    .activity-content h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    
    .activity-content p {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .settings-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .settings-item:last-child {
        border-bottom: none;
    }
    
    .settings-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    
    .settings-info p {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: #d1d5db;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .toggle-switch.active {
        background: #228B22;
    }
    
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform 0.2s ease;
    }
    
    .toggle-switch.active::after {
        transform: translateX(26px);
    }
    
    @media (max-width: 768px) {
        .page-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .profile-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Profile Settings</h1>
        <p class="page-subtitle">Manage your account information and preferences</p>
    </div>

    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-avatar">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Photo" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="profile-name">{{ Auth::user()->name }}</div>
            <div class="profile-email">{{ Auth::user()->email }}</div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Tests</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Farms</div>
                </div>
            </div>
            
            <div class="profile-actions">
                <a href="#" class="action-button" onclick="showTab('profile')">Edit Profile</a>
                <a href="#" class="action-button secondary" onclick="showTab('security')">Security</a>
                <a href="#" class="action-button secondary" onclick="showTab('notifications')">Notifications</a>
            </div>
        </div>

        <div class="profile-content">
            <div class="content-tabs">
                <button class="tab-button active" onclick="showTab('profile')">Profile Information</button>
                <button class="tab-button" onclick="showTab('security')">Security</button>
                <button class="tab-button" onclick="showTab('notifications')">Notifications</button>
                <button class="tab-button" onclick="showTab('activity')">Activity</button>
            </div>

            <!-- Profile Information Tab -->
            <div id="profile-tab" class="tab-content active">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" class="form-input" accept="image/*">
                        @if ($errors->has('profile_photo'))
                            <div style="color: #dc2626; font-size: 0.95em; margin-top: 0.5em;">{{ $errors->first('profile_photo') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                    </div>
                    <button type="submit" class="form-button">Update Profile</button>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security-tab" class="tab-content">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('put')
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="form-button">Update Password</button>
                </form>
                
                <div class="danger-zone">
                    <h3 class="danger-title">Delete Account</h3>
                    <p class="danger-text">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="password" id="delete-account-password">
                        <button type="submit" class="danger-button" id="delete-account-btn">Delete Account</button>
                    </form>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications-tab" class="tab-content">
                <div class="settings-item">
                    <div class="settings-info">
                        <h3>Email Notifications</h3>
                        <p>Receive email updates about your soil tests and recommendations</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSetting(this)"></div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-info">
                        <h3>SMS Alerts</h3>
                        <p>Get SMS notifications for critical soil health alerts</p>
                    </div>
                    <div class="toggle-switch" onclick="toggleSetting(this)"></div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-info">
                        <h3>Weekly Reports</h3>
                        <p>Receive weekly summaries of your farm's soil health</p>
                    </div>
                    <div class="toggle-switch active" onclick="toggleSetting(this)"></div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-info">
                        <h3>Marketing Communications</h3>
                        <p>Receive updates about new features and agricultural tips</p>
                    </div>
                    <div class="toggle-switch" onclick="toggleSetting(this)"></div>
                </div>
            </div>

            <!-- Activity Tab -->
            <div id="activity-tab" class="tab-content">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-vial"></i>
                    </div>
                    <div class="activity-content">
                        <h3>Soil Test Completed</h3>
                        <p>Farm #1 - NPK levels analyzed</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="activity-content">
                        <h3>Crop Recommendation Updated</h3>
                        <p>New recommendations based on latest soil data</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="activity-content">
                        <h3>AI Prediction Generated</h3>
                        <p>Future soil health prediction for Farm #2</p>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="activity-content">
                        <h3>Profile Updated</h3>
                        <p>Your profile information was modified</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script>
    function showTab(tabName) {
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Remove active class from all tab buttons
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => button.classList.remove('active'));
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
    }
    
    function toggleSetting(element) {
        element.classList.toggle('active');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('delete-account-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    html: `<div style='margin-bottom:10px;'>Once your account is deleted, all of its resources and data will be permanently deleted.</div>` +
                          `<input type='password' id='swal-password' class='swal2-input' placeholder='Enter your password' autocomplete='current-password'>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete my account',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    preConfirm: () => {
                        const password = Swal.getPopup().querySelector('#swal-password').value;
                        if (!password) {
                            Swal.showValidationMessage('Password is required');
                        }
                        return password;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-account-password').value = result.value;
                        document.getElementById('delete-account-form').submit();
                    }
                });
            });
        }
    });
</script>
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            confirmButtonColor: '#228B22',
            customClass: {
                popup: 'swal2-rounded swal2-shadow'
            }
        });
    });
</script>
@endif
@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#dc2626',
            customClass: {
                popup: 'swal2-rounded swal2-shadow'
            }
        });
    });
</script>
@endif
@endsection 