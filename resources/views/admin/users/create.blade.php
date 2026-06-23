@extends('admin.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<style>
    .admin-header {
        text-align: center;
        font-size: 2.1rem;
        font-weight: 800;
        margin-bottom: 2.2rem;
        color: #228B22;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        letter-spacing: 0.01em;
    }
    .user-form-card {
        max-width: 500px;
        background: rgba(255,255,255,0.92);
        padding: 2.2rem 2rem;
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(34,139,34,0.10);
        margin: 0 auto;
        border: 1.5px solid #e6f9ec;
        backdrop-filter: blur(4px);
    }
    .user-form-label {
        font-weight: 600;
        color: #228B22;
        margin-bottom: 0.3rem;
        display: block;
    }
    .user-form-input, .user-form-select {
        width: 100%;
        padding: 0.7rem;
        border-radius: 8px;
        border: 1.5px solid #e6f9ec;
        margin-bottom: 1.2rem;
        font-size: 1.08rem;
        background: #f6fdf7;
        transition: border 0.16s;
    }
    .user-form-input:focus, .user-form-select:focus {
        border: 1.5px solid #228B22;
        outline: none;
        background: #fff;
    }
    .user-form-btn {
        background: linear-gradient(90deg, #228B22 80%, #a7f3d0 100%);
        color: #fff;
        font-weight: 700;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-size: 1.08rem;
        border: none;
        box-shadow: 0 2px 8px rgba(34,139,34,0.08);
        transition: background 0.18s, color 0.18s, transform 0.16s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .user-form-btn:hover {
        background: linear-gradient(90deg, #166534 80%, #6ee7b7 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
    }
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #e6f9ec;
        color: #228B22;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: background 0.16s, color 0.16s;
        box-shadow: 0 2px 8px rgba(34,139,34,0.06);
        text-decoration: none;
    }
    .back-btn:hover {
        background: #a7f3d0;
        color: #166534;
    }
</style>
<a href="{{ route('admin.users.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back to User Management</a>
<div class="admin-header"><i class="fas fa-user-plus" style="margin-right:0.5rem;"></i>Add User</div>
<form id="user-create-form" method="POST" action="{{ route('admin.users.store') }}" class="user-form-card">
    @csrf
    <label class="user-form-label">Name</label>
    <input type="text" name="name" class="user-form-input" required>
    <label class="user-form-label">Email</label>
    <input type="email" name="email" class="user-form-input" required>
    <label class="user-form-label">Password</label>
    <input type="password" name="password" class="user-form-input" required>
    <label class="user-form-label">Confirm Password</label>
    <input type="password" name="password_confirmation" class="user-form-input" required>
    <label class="user-form-label">Address</label>
    <input type="text" name="address" class="user-form-input" required>
    <label class="user-form-label">Role</label>
    <select name="role" class="user-form-select" required>
        <option value="admin">Admin</option>
        <option value="farmer">Farmer</option>
        <option value="technician">Technician</option>
        <option value="guest">Guest</option>
    </select>
    <button type="submit" class="user-form-btn"><i class="fas fa-plus-circle"></i> Create User</button>
</form>
<script>
    document.getElementById('user-create-form').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Add User?',
            text: 'Are you sure you want to add this user?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#228B22',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add user',
            background: '#f6fdf7',
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    });
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            confirmButtonColor: '#228B22',
            background: '#f6fdf7',
        });
    @elseif($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#228B22',
            background: '#f6fdf7',
        });
    @endif
</script>
@endsection 