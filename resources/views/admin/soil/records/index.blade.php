@extends('admin.layout')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
    <h2 style="font-size:2rem;font-weight:700;color:#222;">Soil Data Records</h2>
    <a href="{{ route('admin.soil_data_records.create') }}" class="sidebar-link" style="background:#FFD600;padding:0.7rem 1.2rem;border-radius:8px;font-weight:600;text-decoration:none;">+ Add Soil Data</a>
</div>
@if(session('success'))
    <div style="background:#e6ffed;color:#166534;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">{{ session('success') }}</div>
@endif
<!-- Filter Section -->
<form method="GET" action="" style="display:flex;gap:1.2rem;flex-wrap:wrap;align-items:flex-end;margin-bottom:2rem;">
    <div>
        <label style="font-weight:600;">User</label><br>
        <select name="user_id" style="padding:0.5rem 0.7rem;border-radius:7px;min-width:120px;">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="font-weight:600;">Soil Type</label><br>
        <select name="soil_type_id" style="padding:0.5rem 0.7rem;border-radius:7px;min-width:120px;">
            <option value="">All Types</option>
            @foreach($soilTypes as $type)
                <option value="{{ $type->id }}" @if(request('soil_type_id') == $type->id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="font-weight:600;">Date Range</label><br>
        <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding:0.5rem 0.7rem;border-radius:7px;" />
        <span style="margin:0 0.5rem;">to</span>
        <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding:0.5rem 0.7rem;border-radius:7px;" />
    </div>
    <div>
        <button type="submit" style="background:#388e3c;color:#fff;font-weight:600;border:none;border-radius:7px;padding:0.6rem 1.5rem;">Filter</button>
    </div>
</form>
<!-- Data Table -->
<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <thead style="background:#f9fafb;">
        <tr style="color:#388e3c;text-align:left;">
            <th style="padding:0.7rem 0.5rem;">User</th>
            <th style="padding:0.7rem 0.5rem;">Farm</th>
            <th style="padding:0.7rem 0.5rem;">Soil Type</th>
            <th style="padding:0.7rem 0.5rem;">N</th>
            <th style="padding:0.7rem 0.5rem;">P</th>
            <th style="padding:0.7rem 0.5rem;">K</th>
            <th style="padding:0.7rem 0.5rem;">pH</th>
            <th style="padding:0.7rem 0.5rem;">Temp (°C)</th>
            <th style="padding:0.7rem 0.5rem;">Moisture (%)</th>
            <th style="padding:0.7rem 0.5rem;">Location</th>
            <th style="padding:0.7rem 0.5rem;">Date/Time</th>
            <th style="padding:0.7rem 0.5rem;">Notes</th>
            <th style="padding:0.7rem 0.5rem;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($records as $data)
        <tr style="border-bottom:1px solid #f3f4f6;">
            <td style="padding:0.6rem 0.5rem;">{{ $data->user->name ?? '-' }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->farm->name ?? '-' }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->soilType->name ?? '-' }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->n }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->p }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->k }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->ph ?? '-' }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->soil_temperature }}</td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->soil_moisture }}</td>
            <td style="padding:0.6rem 0.5rem;">
                @if($data->latitude && $data->longitude)
                    <a href="https://maps.google.com/?q={{ $data->latitude }},{{ $data->longitude }}" target="_blank">{{ $data->latitude }}, {{ $data->longitude }}</a>
                @else
                    -
                @endif
            </td>
            <td style="padding:0.6rem 0.5rem;">{{ $data->created_at ? $data->created_at->format('Y-m-d H:i') : '-' }}</td>
            <td style="padding:0.6rem 0.5rem;max-width:120px;overflow-wrap:break-word;">{{ $data->notes }}</td>
            <td style="padding:0.6rem 0.5rem;">
                <a href="{{ route('admin.soil_data_records.edit', $data->id) }}" style="color:#388e3c;font-weight:600;margin-right:0.7rem;">Edit</a>
                <form action="{{ route('admin.soil_data_records.destroy', $data->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this soil data record?')" style="color:#e53e3e;background:none;border:none;font-weight:600;cursor:pointer;">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="13" style="padding:1.2rem;text-align:center;color:#888;">No soil data records found.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
<div style="margin-top:1.5rem;">
    {{ $records->links() }}
</div>
@endsection 