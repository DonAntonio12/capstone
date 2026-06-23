@extends('admin.layout')

@section('content')
<h2 style="font-size:2rem;font-weight:700;color:#228B22;margin-bottom:0.5rem;">Soil Data for {{ $user->name }}</h2>
<div style="color:#888;margin-bottom:2rem;">{{ $user->email }}</div>
<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <thead style="background:#f9fafb;">
        <tr style="color:#388e3c;text-align:left;">
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
            <th style="padding:0.7rem 0.5rem;">Fertilizer Rec.</th>
            <th style="padding:0.7rem 0.5rem;">Prediction (ANN)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($soilData as $data)
        <tr style="border-bottom:1px solid #f3f4f6;">
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
            <td style="padding:0.6rem 0.5rem;max-width:120px;overflow-wrap:break-word;">{{ $data->predictions->first()->prediction ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="12" style="padding:1.2rem;text-align:center;color:#888;">No soil data found for this user.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection 