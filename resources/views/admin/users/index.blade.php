@extends('admin.layout')
@section('content')
<style>
    .admin-header {
        text-align: center;
        font-size: 2.3rem;
        font-weight: 800;
        margin-bottom: 2.2rem;
        color: #228B22;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        letter-spacing: 0.01em;
    }
    .user-table-card {
        background: rgba(255, 255, 255, 0.92);
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(34,139,34,0.10);
        overflow: hidden;
        border: 1.5px solid #e6f9ec;
        margin-bottom: 2.5rem;
        backdrop-filter: blur(4px);
    }
    .user-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1.08rem;
    }
    .user-table thead {
        background: linear-gradient(90deg, #228B22 80%, #a7f3d0 100%);
        color: #fff;
    }
    .user-table th, .user-table td {
        padding: 1rem;
        text-align: left;
    }
    .user-table tbody tr {
        border-bottom: 1px solid #e6f9ec;
        transition: background 0.15s;
    }
    .user-table tbody tr:hover {
        background: #f6fdf7;
    }
    .user-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 7px;
        padding: 0.45rem 1.1rem;
        cursor: pointer;
        transition: background 0.16s, color 0.16s, box-shadow 0.16s;
        box-shadow: 0 2px 8px rgba(34,139,34,0.06);
        margin-right: 0.5rem;
    }
    .user-action-btn.edit {
        background: linear-gradient(90deg, #FFD600 80%, #fffbe6 100%);
        color: #222;
    }
    .user-action-btn.edit:hover {
        background: linear-gradient(90deg, #FBBF24 80%, #fffbe6 100%);
        color: #166534;
    }
    .user-action-btn.delete {
        background: linear-gradient(90deg, #e53e3e 80%, #fecaca 100%);
        color: #fff;
    }
    .user-action-btn.delete:hover {
        background: linear-gradient(90deg, #b91c1c 80%, #fecaca 100%);
        color: #fff;
    }
    .add-user-btn {
        background: linear-gradient(90deg, #228B22 80%, #a7f3d0 100%);
        color: #fff;
        font-weight: 700;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 1.08rem;
        box-shadow: 0 2px 8px rgba(34,139,34,0.08);
        transition: background 0.18s, color 0.18s, transform 0.16s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .add-user-btn:hover {
        background: linear-gradient(90deg, #166534 80%, #6ee7b7 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
    }
    .alert-success {
        margin-bottom: 1.5rem;
        background: #e6ffed;
        color: #228B22;
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.05rem;
        box-shadow: 0 2px 8px rgba(34,139,34,0.06);
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .alert-success i {
        font-size: 1.2em;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<div class="admin-header"><i class="fas fa-users" style="margin-right:0.5rem;"></i>User Management</div>
@if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
<div style="margin-bottom: 2.2rem; margin-top: 1.5rem;">
    <a href="{{ route('admin.users.create') }}" class="add-user-btn"><i class="fas fa-user-plus"></i> Add User</a>
</div>
<div class="user-table-card">
<table class="user-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td style="color: #232d25;">{{ $user->name }}</td>
            <td style="color: #232d25;">{{ $user->email }}</td>
            <td style="color: #232d25;">{{ ucfirst($user->role) }}</td>
            <td>
                <a href="{{ route('admin.users.show', $user) }}" class="user-action-btn view" style="background:linear-gradient(90deg,#38b000 80%,#d9f99d 100%);color:#fff;"><i class="fas fa-eye"></i> View</a>
                <a href="{{ route('admin.users.edit', $user) }}" class="user-action-btn edit"><i class="fas fa-edit"></i> Edit</a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" class="delete-user-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="user-action-btn delete"><i class="fas fa-trash-alt"></i> Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
<div style="margin-top:2rem;">
    {{ $users->links() }}
</div>
<script>
    document.querySelectorAll('.delete-user-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete User?',
                text: 'Are you sure you want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#228B22',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
                background: '#f6fdf7',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
    @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#228B22',
            background: '#f6fdf7',
        });
    @endif
</script>
@endsection 