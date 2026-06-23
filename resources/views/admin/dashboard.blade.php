@extends('admin.layout')
@section('content')
<style>
    body {
        background: #f6fdf7;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }
    .dashboard-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .dashboard-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #228B22 60%, #a7f3d0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.6rem;
        color: #fff;
        box-shadow: 0 4px 24px rgba(34,139,34,0.13);
        border: 3px solid #fff;
    }
    .dashboard-greeting {
        font-size: 1.7rem;
        font-weight: 800;
        color: #228B22;
        letter-spacing: 0.01em;
    }
    .dashboard-sub {
        color: #4b5563;
        font-size: 1.08rem;
        margin-top: 0.2rem;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 2rem;
        margin-bottom: 2.5rem;
    }
    .stat-card {
        background: rgba(255,255,255,0.85);
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(34,139,34,0.10);
        padding: 2.2rem 1.7rem 1.7rem 1.7rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        overflow: hidden;
        border: 1.5px solid #e6f9ec;
        transition: box-shadow 0.2s, border 0.2s, transform 0.18s;
        backdrop-filter: blur(6px);
    }
    .stat-card:hover {
        box-shadow: 0 12px 36px rgba(34,139,34,0.18);
        border: 1.5px solid #228B22;
        transform: translateY(-4px) scale(1.03);
    }
    .stat-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.3rem;
        margin-bottom: 0.8rem;
        color: #fff;
        background: linear-gradient(135deg, #228B22 60%, #6ee7b7 100%);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(34,139,34,0.10);
        border: 2px solid #e6f9ec;
    }
    .stat-label {
        color: #6b7280;
        font-size: 1.08rem;
        margin-bottom: 0.2rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .stat-value {
        font-size: 2.3rem;
        font-weight: 900;
        color: #228B22;
        letter-spacing: 0.01em;
        text-shadow: 0 2px 8px rgba(34,139,34,0.06);
    }
    .quick-links {
        margin-bottom: 2.5rem;
    }
    .quick-links-title {
        font-size: 1.18rem;
        font-weight: 800;
        color: #228B22;
        margin-bottom: 1.1rem;
        letter-spacing: 0.01em;
    }
    .quick-links-list {
        display: flex;
        gap: 1.3rem;
        flex-wrap: wrap;
    }
    .quick-link {
        background: linear-gradient(90deg, #228B22 80%, #a7f3d0 100%);
        color: #fff;
        font-weight: 700;
        border-radius: 12px;
        padding: 0.85rem 1.5rem;
        text-decoration: none;
        box-shadow: 0 2px 12px rgba(34,139,34,0.10);
        transition: background 0.18s, color 0.18s, transform 0.16s;
        border: none;
        outline: none;
        font-size: 1.09rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        letter-spacing: 0.01em;
    }
    .quick-link i {
        font-size: 1.25em;
        margin-right: 0.3em;
    }
    .quick-link:hover {
        background: linear-gradient(90deg, #166534 80%, #6ee7b7 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 4px 16px rgba(34,139,34,0.13);
    }
    .recent-activity {
        background: rgba(255,255,255,0.92);
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(34,139,34,0.10);
        padding: 2.2rem 1.7rem;
        margin-bottom: 2rem;
        border: 1.5px solid #e6f9ec;
        backdrop-filter: blur(4px);
    }
    .recent-title {
        font-size: 1.18rem;
        font-weight: 800;
        color: #228B22;
        margin-bottom: 1.2rem;
        letter-spacing: 0.01em;
    }
    .recent-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .recent-item {
        padding: 0.8rem 0;
        border-bottom: 1px solid #e6f9ec;
        color: #374151;
        font-size: 1.08rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .recent-item:last-child {
        border-bottom: none;
    }
    .recent-item .recent-time {
        color: #a0aec0;
        font-size: 0.98rem;
        margin-left: 1.2rem;
        white-space: nowrap;
    }
    @media (max-width: 900px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 0.7rem; }
        .dashboard-grid { grid-template-columns: 1fr; }
    }
</style>
<div class="dashboard-header">
    <div class="dashboard-avatar"><i class="fas fa-user-shield"></i></div>
    <div>
        <div class="dashboard-greeting">Welcome back, Admin!</div>
        <div class="dashboard-sub">Here's an overview of your system today.</div>
    </div>
</div>
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ number_format($totalUsers) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-brain"></i></div>
        <div class="stat-label">ANN Accuracy</div>
        <div class="stat-value">{{ $annAccuracy }}%</div>
    </div>
</div>
<div class="quick-links">
    <div class="quick-links-title">Quick Links</div>
    <div class="quick-links-list">
        <a href="{{ route('admin.users.index') }}" class="quick-link"><i class="fas fa-users"></i> User Management</a>
        <a href="{{ route('admin.soil.index') }}" class="quick-link"><i class="fas fa-seedling"></i> Soil Data</a>
       
        <a href="#settings" class="quick-link"><i class="fas fa-cogs"></i> System Settings</a>
        
    </div>
</div>
<div class="recent-activity">
    <div class="recent-title">Recent Activity</div>
    <ul class="recent-list">
        @forelse($recentActivity as $activity)
            <li class="recent-item">{!! $activity['description'] !!} <span class="recent-time">{{ $activity['time']->format('M d, Y H:i') }}</span></li>
        @empty
            <li class="recent-item">No recent activity.</li>
        @endforelse
    </ul>
</div>
@endsection 