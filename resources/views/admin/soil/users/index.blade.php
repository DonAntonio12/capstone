@extends('admin.layout')

@section('content')
<h2 style="font-size:2rem;font-weight:700;color:#228B22;margin-bottom:2rem;">All Users</h2>
<div style="background:#fff;padding:2rem 1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.06);max-width:700px;margin-bottom:2rem;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="color:#222;text-align:left;background:#f9fafb;">
                <th style="padding:0.7rem 0.5rem;">Name</th>
                <th style="padding:0.7rem 0.5rem;">Email</th>
                <th style="padding:0.7rem 0.5rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:0.6rem 0.5rem; color:#000;">{{ $user->name }}</td>
                <td style="padding:0.6rem 0.5rem; color:#000;">{{ $user->email }}</td>
                <td style="padding:0.6rem 0.5rem; color:#000;">
                    <a href="{{ route('admin.soil_users.show', $user->id) }}" style="color:#388e3c;font-weight:600;">View Soil Data</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 