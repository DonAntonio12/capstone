@extends('admin.layout')

@section('content')
<div class="user-details-card glass-card">
    <a href="{{ route('admin.users.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back to User Management</a>
    <h2 class="user-details-title"><i class="fas fa-user"></i> User Details</h2>
    <div class="user-details-info">
        <div class="user-detail-row"><span class="user-detail-label">Name:</span> <span class="user-detail-value">{{ $user->name }}</span></div>
        <div class="user-detail-row"><span class="user-detail-label">Email:</span> <span class="user-detail-value">{{ $user->email }}</span></div>
        <div class="user-detail-row"><span class="user-detail-label">Role:</span> <span class="user-detail-value">{{ ucfirst($user->role) }}</span></div>
        <div class="user-detail-row"><span class="user-detail-label">Address:</span> <span class="user-detail-value">{{ $user->address }}</span></div>
        <div class="user-detail-row"><span class="user-detail-label">Created At:</span> <span class="user-detail-value">{{ $user->created_at->format('F d, Y h:i A') }}</span></div>
        <div class="user-detail-row"><span class="user-detail-label">Last Updated:</span> <span class="user-detail-value">{{ $user->updated_at->format('F d, Y h:i A') }}</span></div>
    </div>
</div>
<style>
.user-details-card {
    max-width: 540px;
    margin: 2.5rem auto;
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    border-radius: 1.7rem;
    background: rgba(255,255,255,0.97);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
    backdrop-filter: blur(10px);
    border: 1.5px solid #e6f9ec;
    color: #1a2b1a;
    font-size: 1.13rem;
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
}
.user-details-title {
    font-size: 2.1rem;
    font-weight: 800;
    color: #228B22;
    margin-bottom: 1.7rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    letter-spacing: 0.01em;
}
.user-details-info {
    margin-top: 1.2rem;
}
.user-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.1rem 0;
    border-bottom: 1px solid #e6f9ec;
    font-size: 1.15rem;
    line-height: 1.5;
}
.user-detail-label {
    font-weight: 700;
    color: #228B22;
    min-width: 120px;
    letter-spacing: 0.01em;
}
.user-detail-value {
    color: #1a2b1a;
    font-weight: 500;
    word-break: break-word;
    max-width: 320px;
    text-align: right;
}
.back-btn {
    display: inline-block;
    margin-bottom: 1.7rem;
    color: #228B22;
    background: none;
    border: none;
    font-weight: 700;
    font-size: 1.08rem;
    text-decoration: none;
    transition: color 0.2s;
    letter-spacing: 0.01em;
}
.back-btn:hover {
    color: #166534;
    text-decoration: underline;
}
@media (max-width: 600px) {
    .user-details-card { padding: 1.2rem 0.7rem; }
    .user-details-title { font-size: 1.3rem; }
    .user-detail-row { font-size: 1rem; padding: 0.7rem 0; }
}
</style>
@endsection 