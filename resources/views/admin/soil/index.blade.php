@extends('admin.layout')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
    <h2 style="font-size:2rem;font-weight:700;color:#fff;">Common Soil Types</h2>
    <a href="{{ route('admin.soil_types.create') }}" class="sidebar-link" style="background:#FFD600;padding:0.7rem 1.2rem;border-radius:8px;font-weight:600;text-decoration:none;">+ Add Soil Type</a>
</div>
@if(session('success'))
    <div style="background:#e6ffed;color:#166534;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">{{ session('success') }}</div>
@endif
<div class="soil-section">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;background:#232d25;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.10);min-width:700px;">
            <thead style="background:#059669;">
                <tr>
                    <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Type of Soil</th>
                    <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Picture</th>
                    <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">NPK & pH</th>
                    <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Details</th>
                    <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Actions</th>
                </tr>
            </thead>
            <tbody style="font-size:1.05rem;color:#f3fdf7;">
                @forelse(App\Models\SoilType::orderBy('name')->get() as $type)
                <tr style="border-bottom:1px solid #444;background:#232d25;">
                    <td style="padding:0.7rem 0.7rem;font-weight:600;color:#fff;">{{ $type->name }}</td>
                    <td style="padding:0.7rem 0.7rem;">
                        @if($type->image_url)
                            <img src="{{ asset($type->image_url) }}" alt="{{ $type->name }}" class="soil-thumb" style="width:60px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #e0e7eb;box-shadow:0 1px 4px rgba(0,0,0,0.06);" onclick="showSoilModal({{ $type->id }})">
                        @else
                            <span style="color:#aaa;">No image</span>
                        @endif
                    </td>
                    <td style="padding:0.7rem 0.7rem;color:#e0ffe0;">
                        @if($type->thresholds)
                            @foreach($type->thresholds as $param => $range)
                                <div><b>{{ $param }}</b>: {{ is_array($range) ? implode(' - ', $range) : $range }}</div>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding:0.7rem 0.7rem;">
                        <button class="see-more-btn" onclick="showSoilModal({{ $type->id }})">See More</button>
                    </td>
                    <td style="padding:0.7rem 0.7rem;">
                        <a href="{{ route('admin.soil_types.edit', $type->id) }}" style="color:#FFD600;font-weight:600;margin-right:0.7rem;">Edit</a>
                        <form action="{{ route('admin.soil_types.destroy', $type->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this soil type?')" style="color:#e53e3e;background:none;border:none;font-weight:600;cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding:1.2rem;text-align:center;color:#888;background:#232d25;">No soil types found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Modal for soil details -->
    <div id="soilModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(31,41,55,0.55);align-items:center;justify-content:center;">
        <div id="soilModalContent" style="background:#232d25;border-radius:16px;max-width:420px;width:95vw;padding:2rem 1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;animation:modalIn 0.2s;">
            <button onclick="closeSoilModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;color:#FFD600;cursor:pointer;">&times;</button>
            <div id="soilModalImageWrap" style="text-align:center;margin-bottom:1.2rem;"></div>
            <div id="soilModalDetails" style="color:#f3fdf7;"></div>
        </div>
    </div>
    <script>
        const soilTypes = @json(App\Models\SoilType::orderBy('name')->get()->keyBy('id'));
        function showSoilModal(id) {
            const d = soilTypes[id];
            let img = d.image_url ? `<img src='/${d.image_url.replace(/^\/+/, "")}' alt='${d.name}' style='width:180px;height:180px;object-fit:cover;border-radius:12px;border:2.5px solid #FFD600;box-shadow:0 2px 12px rgba(5,150,105,0.13);margin-bottom:1rem;'>` : '';
            let npk = d.thresholds ? Object.entries(d.thresholds).map(([k,v]) => `<b>${k}</b>: ${Array.isArray(v) ? v.join(' - ') : v}`).join('<br>') : '-';
            let crops = d.best_crops ? d.best_crops.join(', ') : '-';
            document.getElementById('soilModalImageWrap').innerHTML = img;
            document.getElementById('soilModalDetails').innerHTML = `
                <div style='font-size:1.2rem;font-weight:700;color:#FFD600;margin-bottom:0.5rem;'>${d.name}</div>
                <div style='margin-bottom:0.7rem;'><b>NPK & pH:</b><br>${npk}</div>
                <div style='margin-bottom:0.7rem;'><b>Remarks:</b> ${d.remarks || '-'}</div>
                <div style='margin-bottom:0.7rem;'><b>Recommended Crops:</b> ${crops}</div>
                <div style='margin-bottom:0.7rem;'><b>Why suitable?</b> ${d.why_suitable || '-'}</div>
                <div style='margin-bottom:0.7rem;'><b>Description:</b> ${d.description || '-'}</div>
            `;
            document.getElementById('soilModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeSoilModal() {
            document.getElementById('soilModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('soilModal').addEventListener('click', function(e) {
                if (e.target === this) closeSoilModal();
            });
        });
    </script>
    <style>
    .see-more-btn {
        background: #FFD600;
        color: #232d25;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        font-size: 0.98rem;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 1px 3px rgba(5,150,105,0.08);
    }
    .see-more-btn:hover {
        background: #ffe066;
    }
    @media (max-width: 900px) {
        .soil-section table { min-width: 600px; }
    }
    @media (max-width: 600px) {
        .soil-section table { min-width: 400px; }
    }
    @keyframes modalIn {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    </style>
</div>
@endsection 