@extends('layouts.user')

@section('title', 'Dashboard - ' . \App\Helpers\SystemHelper::getSiteName())

@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endsection

@section('styles')
<style>
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
        padding-top: 80px; /* Add padding for fixed navbar */
    }
    
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .welcome-section {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .welcome-subtitle {
        font-size: 1.1rem;
        color: #6b7280;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .map-container {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .map-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }
    
    .map-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .map-btn {
        background: #228B22;
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .map-btn:hover {
        background: #1a5f1a;
        transform: translateY(-1px);
    }
    
    .map-btn.secondary {
        background: #6b7280;
    }
    
    .map-btn.secondary:hover {
        background: #4b5563;
    }
    
    .map-fullscreen-btn {
        background: #228B22;
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }
    
    .map-fullscreen-btn:hover {
        background: #1a5f1a;
        transform: translateY(-1px);
    }
    
    .map-details {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 10;
        background: rgba(255,255,255,0.95);
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        color: #374151;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        max-width: 300px;
        word-wrap: break-word;
        min-width: 250px;
        max-width: 90vw;
        backdrop-filter: blur(5px);
    }
    
    @media (max-width: 768px) {
        .map-details {
            bottom: 8px;
            left: 8px;
            max-width: calc(100vw - 16px);
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
    
    #user-map-container {
        position: relative;
        width: 100%;
        min-height: 400px;
        background: #e5e7eb;
        overflow: visible !important;
    }
    
    #user-map {
        height: 400px !important;
        width: 100% !important;
        border-radius: 16px;
        overflow: hidden;
        z-index: 1;
        background: #e8f5e9; /* fallback bg */
    }
    #map-loading-spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
        display: none;
    }
    
    .overview-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .overview-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 1.5rem;
    }
    
    .step-card {
        text-align: center;
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    
    .step-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .step-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .step-desc {
        color: #6b7280;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    
    .soil-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .soil-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 1.5rem;
    }
    
    .soil-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }
    
    .soil-card:hover {
        transform: translateY(-2px);
    }
    
    .soil-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .soil-type {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        padding: 1rem 1rem 0.5rem;
    }
    
    .soil-desc {
        color: #6b7280;
        padding: 0 1rem 1rem;
        line-height: 1.5;
    }
    
    .soil-npk {
        padding: 0 1rem 0.5rem;
        font-size: 0.9rem;
    }
    
    .npk-item {
        color: #374151;
        margin-bottom: 0.25rem;
    }
    
    .soil-crops {
        padding: 0 1rem 1rem;
        font-size: 0.9rem;
        color: #228B22;
        font-weight: 500;
    }
    
    .recommend-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .recommend-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .recommend-table th,
    .recommend-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .recommend-table th {
        background: #f9fafb;
        color: #111827;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .recommend-table td {
        color: #374151;
        font-size: 0.9rem;
    }
    
    .recommend-table tr:last-child td {
        border-bottom: none;
    }
    
    .recommend {
        color: #228B22;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .welcome-title {
            font-size: 2rem;
        }
        
        .overview-steps {
            grid-template-columns: 1fr;
        }
        
        .soil-grid {
            grid-template-columns: 1fr;
        }
        
        .map-controls {
            flex-direction: column;
            align-items: stretch;
        }
        
        .map-buttons {
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .welcome-section {
            padding: 1.5rem;
        }
        
        .overview-card {
            padding: 1.5rem;
        }
        
        .soil-section {
            padding: 1.5rem;
        }
        
        .recommend-section {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
                    <h1 class="welcome-title">Welcome to {{ \App\Helpers\SystemHelper::getSiteName() }}</h1>
        <p class="welcome-subtitle">Your intelligent soil analysis companion for better farming decisions</p>
    </div>

    <div class="recommend-section">
        <div class="section-title">Recent Recommendations</div>
        <table class="recommend-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Soil Type</th>
                    <th>NPK Levels</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                @php($tests = $recentUserTests ?? collect())
                @forelse($tests as $test)
                <tr>
                    <td>{{ optional($test->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $test->soil_type ?? '-' }}</td>
                    <td>
                        N: {{ $test->n ?? '-' }}, 
                        P: {{ $test->p ?? '-' }}, 
                        K: {{ $test->k ?? '-' }}
                    </td>
                    <td class="recommend">{{ $test->recommendation ?? ($test->prediction ?? '-') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#6b7280;">No recent tests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- User Location Map Section -->
    <div class="map-container">
        <div class="map-controls">
            <div class="section-title">Your Current Location</div>
            <div class="map-buttons">
                <button id="locate-btn" class="map-btn">
                    📍 Locate Me
                </button>
                <button id="map-fullscreen-btn" class="map-btn secondary">
                    🔍 Fullscreen
                </button>
            </div>
        </div>
        <div id="user-map-container" style="position:relative;">
            <div id="user-map" style="height:400px; width:100%; border-radius:16px; overflow:hidden; z-index:3;"></div>
            <div id="map-loading-spinner">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="20" stroke="#43a047" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4"/>
                </svg>
            </div>
            <div id="map-details" class="map-details"></div>
        </div>
        <div id="map-status" style="text-align:center; color:#666; margin-top:1rem; font-size:0.95rem;"></div>
    </div>

    <!-- Overview Section -->
    <div class="overview-card">
        <div class="section-title">How {{ \App\Helpers\SystemHelper::getSiteName() }} Works</div>
        <div class="overview-steps">
            <div class="step-card">
                <div class="step-icon">🌱</div>
                <div class="step-title">1. Collect Sample</div>
                <div class="step-desc">Gather soil samples from different areas of your farm for comprehensive analysis</div>
            </div>
            <div class="step-card">
                <div class="step-icon">🔬</div>
                <div class="step-title">2. Test NPK</div>
                <div class="step-desc">Analyze nitrogen, phosphorus, and potassium levels using our advanced sensors</div>
            </div>
            <div class="step-card">
                <div class="step-icon">🤖</div>
                <div class="step-title">3. AI Analysis</div>
                <div class="step-desc">Get instant predictions and personalized recommendations using machine learning</div>
            </div>
            <div class="step-card">
                <div class="step-icon">📈</div>
                <div class="step-title">4. Optimize</div>
                <div class="step-desc">Implement recommendations to improve soil health and maximize crop yields</div>
            </div>
        </div>
    </div>

    <!-- Soil Types Section -->
    <div class="soil-section">
        <div class="section-title">Common Soil Types</div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.04);min-width:700px;">
                <thead style="background:#059669;">
                    <tr>
                        <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Type of Soil</th>
                        <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Picture</th>
                        <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">NPK & pH</th>
                        <th style="padding:0.9rem 0.7rem;font-size:1rem;font-weight:700;color:#fff;text-align:left;">Details</th>
                    </tr>
                </thead>
                <tbody style="font-size:1.05rem;color:#232d25;">
                    @forelse(App\Models\SoilType::orderBy('name')->get() as $type)
                    <tr style="border-bottom:1px solid #e5e7eb;background:#fff;">
                        <td style="padding:0.7rem 0.7rem;font-weight:600;color:#059669;">{{ $type->name }}</td>
                        <td style="padding:0.7rem 0.7rem;">
                            @if($type->image_url)
                                <img src="{{ asset($type->image_url) }}" alt="{{ $type->name }}" class="soil-thumb" style="width:60px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #e0e7eb;box-shadow:0 1px 4px rgba(0,0,0,0.06);" onclick="showSoilModal({{ $type->id }})">
                            @else
                                <span style="color:#aaa;">No image</span>
                            @endif
                        </td>
                        <td style="padding:0.7rem 0.7rem;color:#059669;">
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
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding:1.2rem;text-align:center;color:#888;background:#fff;">No soil types found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Modal for soil details -->
        <div id="soilModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(31,41,55,0.55);align-items:center;justify-content:center;">
            <div id="soilModalContent" style="background:#fff;border-radius:16px;max-width:420px;width:95vw;padding:2rem 1.5rem;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;animation:modalIn 0.2s;">
                <button onclick="closeSoilModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;color:#059669;cursor:pointer;">&times;</button>
                <div id="soilModalImageWrap" style="text-align:center;margin-bottom:1.2rem;"></div>
                <div id="soilModalDetails" style="color:#232d25;"></div>
            </div>
        </div>
        <script>
            const soilTypes = @json(App\Models\SoilType::orderBy('name')->get()->keyBy('id'));
            function showSoilModal(id) {
                const d = soilTypes[id];
                let img = d.image_url ? `<img src='/${d.image_url.replace(/^\/+/, "")}' alt='${d.name}' style='width:180px;height:180px;object-fit:cover;border-radius:12px;border:2.5px solid #059669;box-shadow:0 2px 12px rgba(5,150,105,0.13);margin-bottom:1rem;'>` : '';
                let npk = d.thresholds ? Object.entries(d.thresholds).map(([k,v]) => `<b>${k}</b>: ${Array.isArray(v) ? v.join(' - ') : v}`).join('<br>') : '-';
                let crops = d.best_crops ? d.best_crops.join(', ') : '-';
                document.getElementById('soilModalImageWrap').innerHTML = img;
                document.getElementById('soilModalDetails').innerHTML = `
                    <div style='font-size:1.2rem;font-weight:700;color:#059669;margin-bottom:0.5rem;'>${d.name}</div>
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
            background: #059669;
            color: #fff;
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
            background: #047857;
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

    <!-- Recommendations Section -->
   
    <div class="overview-card" style="margin-bottom:2rem;">
        <div class="section-title" style="margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
            <svg style="width:2rem;height:2rem;color:#6366f1;vertical-align:middle;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
            Paraan ng Pagsusuri ng Lupa
        </div>
        <ol style="margin:0;padding:0;list-style:none;">
            <!-- STEP 1 -->
            <li style="display:flex;align-items:stretch;gap:2rem;margin-bottom:2.2rem;flex-wrap:wrap;">
                <div style="flex:1 1 260px;min-width:220px;display:flex;align-items:flex-start;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:2.2rem;height:2.2rem;background:#e0e7ff;color:#6366f1;border-radius:50%;font-weight:bold;font-size:1.2rem;box-shadow:0 1px 4px rgba(99,102,241,0.08);">1</span>
                    <div style="font-size:1.08rem;font-weight:600;line-height:1.5;">Maghanda ng mga Kailangan:<br><span style="font-weight:400;">Soil test kit o sensor, malinis na lalagyan, ballpen at papel (o gamitin ang app).</span></div>
                </div>
                <div style="flex:1 1 220px;min-width:180px;max-width:340px;display:flex;flex-direction:column;align-items:center;">
                    <img src="/images/figure1.png" alt="Grid ng sampling points sa bukid" style="width:100%;max-width:260px;border:1.5px solid #6366f1;border-radius:10px;box-shadow:0 2px 8px rgba(99,102,241,0.08);background:#f3f4f6;">
                    <div style="font-size:0.98rem;color:#374151;margin-top:0.5rem;font-weight:500;text-align:center;">Fig. 1: Halimbawa ng grid ng mga sampling point sa bukid</div>
                </div>
            </li>
            <!-- STEP 2 -->
            <li style="display:flex;align-items:stretch;gap:2rem;margin-bottom:2.2rem;flex-wrap:wrap;">
                <div style="flex:1 1 260px;min-width:220px;display:flex;align-items:flex-start;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:2.2rem;height:2.2rem;background:#d1fae5;color:#10b981;border-radius:50%;font-weight:bold;font-size:1.2rem;box-shadow:0 1px 4px rgba(16,185,129,0.08);">2</span>
                    <div style="font-size:1.08rem;font-weight:600;line-height:1.5;">Pagkuha ng Sample ng Lupa:<br><span style="font-weight:400;">Pumili ng representative na bahagi ng sakahan, kumuha ng lupa mula 5-10 cm lalim, ilagay sa malinis na lalagyan.</span></div>
                </div>
                <div style="flex:1 1 220px;min-width:180px;max-width:340px;display:flex;flex-direction:column;align-items:center;">
                    <img src="/images/figure2.png" alt="Zigzag na pattern ng pagkuha ng sample" style="width:100%;max-width:260px;border:1.5px solid #10b981;border-radius:10px;box-shadow:0 2px 8px rgba(16,185,129,0.08);background:#f3f4f6;">
                    <div style="font-size:0.98rem;color:#374151;margin-top:0.5rem;font-weight:500;text-align:center;">Fig. 2: Zigzag na pattern ng pagkuha ng sample</div>
                </div>
            </li>
            <!-- STEP 3 -->
            <li style="display:flex;align-items:stretch;gap:2rem;margin-bottom:2.2rem;flex-wrap:wrap;">
                <div style="flex:1 1 260px;min-width:220px;display:flex;align-items:flex-start;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:2.2rem;height:2.2rem;background:#fef9c3;color:#eab308;border-radius:50%;font-weight:bold;font-size:1.2rem;box-shadow:0 1px 4px rgba(234,179,8,0.08);">3</span>
                    <div style="font-size:1.08rem;font-weight:600;line-height:1.5;">Pag-test gamit ang Sensor:<br><span style="font-weight:400;">Ikabit ang sensor, i-on ang device, ilagay ang probe sa sample, hintayin ang resulta sa dashboard.</span></div>
                </div>
                <div style="flex:1 1 220px;min-width:180px;max-width:340px;display:flex;flex-direction:column;align-items:center;">
                    <img src="/images/figure3.png" alt="Tamang lalim ng pagkuha ng sample" style="width:100%;max-width:260px;border:1.5px solid #eab308;border-radius:10px;box-shadow:0 2px 8px rgba(234,179,8,0.08);background:#f3f4f6;">
                    <div style="font-size:0.98rem;color:#374151;margin-top:0.5rem;font-weight:500;text-align:center;">Fig. 3: Tamang lalim ng pagkuha ng sample</div>
                </div>
            </li>
            <!-- STEP 4 -->
            <li style="display:flex;align-items:stretch;gap:2rem;margin-bottom:2.2rem;flex-wrap:wrap;">
                <div style="flex:1 1 260px;min-width:220px;display:flex;align-items:flex-start;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:2.2rem;height:2.2rem;background:#fca5a5;color:#ef4444;border-radius:50%;font-weight:bold;font-size:1.2rem;box-shadow:0 1px 4px rgba(239,68,68,0.08);">4</span>
                    <div style="font-size:1.08rem;font-weight:600;line-height:1.5;">Pag-record ng Resulta &amp; Paglinis:<br><span style="font-weight:400;">Tingnan at i-save ang moisture, pH, at temperature (kung available). Linisin ang sensor at iba pang ginamit na kagamitan.</span></div>
                </div>
                <div style="flex:1 1 220px;min-width:180px;max-width:340px;display:flex;flex-direction:column;align-items:center;">
                    <img src="/images/figure4.png" alt="Quartering method ng paghahalo ng sample" style="width:100%;max-width:260px;border:1.5px solid #ef4444;border-radius:10px;box-shadow:0 2px 8px rgba(239,68,68,0.08);background:#f3f4f6;">
                    <div style="font-size:0.98rem;color:#374151;margin-top:0.5rem;font-weight:500;text-align:center;">Fig. 4: Quartering method ng paghahalo ng sample</div>
                </div>
            </li>
            <!-- STEP 5 -->
            <li style="display:flex;align-items:stretch;gap:2rem;margin-bottom:2.2rem;flex-wrap:wrap;">
                <div style="flex:1 1 260px;min-width:220px;display:flex;align-items:flex-start;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:2.2rem;height:2.2rem;background:#e0e7ff;color:#6366f1;border-radius:50%;font-weight:bold;font-size:1.2rem;box-shadow:0 1px 4px rgba(99,102,241,0.08);">5</span>
                    <div style="font-size:1.08rem;font-weight:600;line-height:1.5;">Pag-interpret ng Resulta:<br><span style="font-weight:400;">Gamitin ang resulta para malaman ang nararapat na aksyon para sa lupa.</span></div>
                </div>
                <div style="flex:1 1 220px;min-width:180px;max-width:340px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Walang figure for step 5 -->
                </div>
            </li>
        </ol>
        <div style="margin-top:1.5rem;font-size:0.9rem;color:#6b7280;">Para sa karagdagang tulong, tingnan ang gabay o makipag-ugnayan sa admin.</div>
        <!-- INSTRUCTIONS: Palitan mo lang ang /images/figure1.png, /images/figure2.png, /images/figure3.png, /images/figure4.png sa public/images/ folder para i-update ang mga diagram. -->
    </div>

    <style>
        @keyframes progress-bar {
            0% { width: 0; }
            100% { width: 100%; }
        }
        .animate-progress-bar {
            animation: progress-bar 2s cubic-bezier(0.4,0,0.2,1) 1;
        }
    </style>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        var mapStatus = document.getElementById('map-status');
        var mapDiv = document.getElementById('user-map');
        var mapDetails = document.getElementById('map-details');
        var mapContainer = document.getElementById('user-map-container');
        var fullscreenBtn = document.getElementById('map-fullscreen-btn');
        var locateBtn = document.getElementById('locate-btn');
        var spinner = document.getElementById('map-loading-spinner');
        var isFullscreen = false;
        var map, marker, scale;
        var address = '';
        var currentPosition = null;
        
        function showSpinner() {
            if (spinner) spinner.style.display = 'block';
        }
        function hideSpinner() {
            if (spinner) spinner.style.display = 'none';
        }
        
        function updateDetails(lat, lng, zoom) {
            mapDetails.innerHTML =
                '<b>Latitude:</b> ' + lat.toFixed(6) + '<br>' +
                '<b>Longitude:</b> ' + lng.toFixed(6) + '<br>' +
                (address ? ('<b>Address:</b> ' + address + '<br>') : '') +
                '<b>Zoom:</b> ' + zoom;
        }
        
        function fetchAddress(lat, lng) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng)
                .then(res => res.json())
                .then(data => {
                    address = data.display_name || '';
                    if (map) updateDetails(lat, lng, map.getZoom());
                })
                .catch(error => {
                    console.log('Error fetching address:', error);
                });
        }
        
        function enterFullscreen() {
            if (mapContainer.requestFullscreen) {
                mapContainer.requestFullscreen();
            } else if (mapContainer.webkitRequestFullscreen) {
                mapContainer.webkitRequestFullscreen();
            } else if (mapContainer.msRequestFullscreen) {
                mapContainer.msRequestFullscreen();
            }
        }
        
        function exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
        
        function locateUser() {
            if (navigator.geolocation) {
                mapStatus.innerHTML = 'Locating your position...';
                locateBtn.disabled = true;
                locateBtn.innerHTML = '📍 Locating...';
                showSpinner();
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    currentPosition = { lat: lat, lng: lng };
                    if (map) {
                        map.setView([lat, lng], 16);
                        marker.setLatLng([lat, lng]);
                        marker.bindPopup('Your Location').openPopup();
                        updateDetails(lat, lng, map.getZoom());
                        fetchAddress(lat, lng);
                        mapStatus.innerHTML = 'Location updated successfully';
                    } else {
                        initializeMap(lat, lng);
                    }
                    locateBtn.disabled = false;
                    locateBtn.innerHTML = '📍 Locate Me';
                    hideSpinner();
                }, function(error) {
                    mapStatus.innerHTML = 'Location access denied or unavailable';
                    locateBtn.disabled = false;
                    locateBtn.innerHTML = '📍 Locate Me';
                    hideSpinner();
                });
            } else {
                mapStatus.innerHTML = 'Geolocation is not supported by this browser';
            }
        }
        
        function initializeMap(lat, lng) {
            showSpinner();
            map = L.map('user-map').setView([lat, lng], 16);
            // Alternative tile servers if you have loading issues:
            // HOT OSM: 'https://a.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png'
            // CartoDB: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'
            // Stamen Toner: 'https://stamen-tiles.a.ssl.fastly.net/toner/{z}/{x}/{y}.png'
            var tileLayer = L.tileLayer('https://a.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap, Tiles style by Humanitarian OpenStreetMap Team hosted by OpenStreetMap France'
            });
            tileLayer.on('load', function() { hideSpinner(); });
            tileLayer.addTo(map);
            marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup('Your Location').openPopup();
            updateDetails(lat, lng, map.getZoom());
            fetchAddress(lat, lng);
            if (mapDetails) {
                mapDetails.style.position = 'absolute';
                mapDetails.style.bottom = '16px';
                mapDetails.style.left = '16px';
                mapDetails.style.zIndex = '9999';
                if (window.innerWidth <= 768) {
                    mapDetails.style.bottom = '8px';
                    mapDetails.style.left = '8px';
                }
            }
            map.on('zoomend', function() {
                if (currentPosition) {
                    updateDetails(currentPosition.lat, currentPosition.lng, map.getZoom());
                }
            });
            mapStatus.innerHTML = 'Location detected successfully';
            // Force redraw after initial load
            setTimeout(() => { if (map) map.invalidateSize(); }, 500);
        }
        
        fullscreenBtn.addEventListener('click', function() {
            if (!isFullscreen) {
                enterFullscreen();
            } else {
                exitFullscreen();
            }
        });
        
        locateBtn.addEventListener('click', function() {
            locateUser();
        });
        
        document.addEventListener('fullscreenchange', function() {
            isFullscreen = !!document.fullscreenElement;
            if (isFullscreen) {
                mapDiv.style.height = '100vh';
                mapDiv.style.width = '100vw';
                mapDiv.style.borderRadius = '0';
                fullscreenBtn.textContent = '🔍 Exit Fullscreen';
            } else {
                mapDiv.style.height = '400px';
                mapDiv.style.width = '100%';
                mapDiv.style.borderRadius = '16px';
                fullscreenBtn.textContent = '🔍 Fullscreen';
            }
            if (map) {
                setTimeout(() => map.invalidateSize(), 300);
            }
        });
        // Initialize map with user's location
        if (navigator.geolocation) {
            showSpinner();
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                currentPosition = { lat: lat, lng: lng };
                initializeMap(lat, lng);
                hideSpinner();
            }, function(error) {
                mapStatus.innerHTML = 'Location access denied or unavailable. Click "Locate Me" to try again.';
                mapDiv.style.display = 'none';
                hideSpinner();
            });
        } else {
            mapStatus.innerHTML = 'Geolocation is not supported by this browser';
            mapDiv.style.display = 'none';
        }
    });
</script>
@endsection
