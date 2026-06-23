@extends('layouts.user')

@section('title', 'Real-Time Soil Testing - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<!-- CSRF Token Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    
    .title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .desc {
        color: #6b7280;
        margin-bottom: 2rem;
        line-height: 1.6;
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
    
    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #228B22;
        box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.1);
    }
    
    .form-btn {
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
    
    .form-btn:hover {
        background: #1a5f1a;
        transform: translateY(-1px);
    }
    
    .form-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Test Configuration Styles */
    .test-config {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .test-config h4 {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }
    
    /* Location and Map Styles */
    .location-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .location-section h4 {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    #map { 
        height: 300px; 
        width: 100%; 
        border-radius: 8px; 
        margin-bottom: 1rem; 
        border: 1px solid #e5e7eb;
    }
    
    .location-info { 
        text-align: center; 
        color: #228B22; 
        font-size: 1rem; 
        margin-bottom: 1rem; 
        padding: 0.5rem;
        background: #f0fdf4;
        border-radius: 6px;
    }
    
    .location-btn {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }
    
    .location-btn:hover {
        background: #2563eb;
    }
    
    /* Test Status Styles */
    .test-status {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
        display: none;
    }
    
    .test-status .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f59e0b;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Test Results Styles */
    .test-results {
        background: #f0fdf4;
        border: 1px solid #22c55e;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1rem 0;
        display: none;
    }
    
    .test-results h4 {
        color: #1e293b;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .sensor-data, .soil-analysis, .thresholds-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .sensor-data h5, .soil-analysis h5, .thresholds-section h5 {
        color: #1e293b;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .data-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .data-row:last-child {
        border-bottom: none;
    }
    
    .data-label {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .data-value {
        font-weight: 600;
        color: #1e293b;
    }
    
    .recommend {
        color: #228B22;
        font-weight: 600;
    }
    
    .prediction {
        color: #228B22;
        font-weight: 600;
        margin-top: 1.2rem;
    }
    
    /* Debug Section */
    .debug-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .debug-section h4 {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .debug-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .debug-btn {
        width: 100%;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    
    .debug-btn.primary {
        background: #3b82f6;
        color: white;
    }
    
    .debug-btn.primary:hover {
        background: #2563eb;
    }
    
    .debug-btn.success {
        background: #10b981;
        color: white;
    }
    
    .debug-btn.success:hover {
        background: #059669;
    }
    
    .debug-btn.danger {
        background: #ef4444;
        color: white;
    }
    
    .debug-btn.danger:hover {
        background: #dc2626;
    }
    
    .status-message {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 6px;
        display: none;
    }
    
    .status-message.success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-message.error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .status-message.warning {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fed7aa;
    }
    
    .status-message.info {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    @media (max-width: 768px) {
        .container { 
            padding: 1rem; 
        }
        .card { 
            padding: 1.5rem; 
        }
        .config-grid {
            grid-template-columns: 1fr;
        }
        .results-grid {
            grid-template-columns: 1fr;
        }
        .debug-grid {
            grid-template-columns: 1fr;
        }
    }
    
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="title">🌱 Smart Soil Monitoring System</div>
        <div class="desc">Connect your NPK and pH sensors to the system. The sensors will automatically collect soil data and provide real-time analysis and recommendations.</div>
        
        <!-- Location and Map Section -->
        <div class="location-section">
            <h4>📍 Current Location</h4>
            <div id="map"></div>
            <div id="locationInfo" class="location-info">Click "Get Current Location" to see your position</div>
            <div style="text-align: center;">
                <button id="getLocationBtn" class="location-btn">Get Current Location</button>
                <button id="clearLocationBtn" class="location-btn" style="background: #6b7280;">Clear Location</button>
            </div>
        </div>
        
        <!-- Test Configuration -->
        <div class="test-config">
            <h4>⚙️ Test Configuration</h4>
            <div class="config-grid">
                <div class="form-group">
                    <label class="form-label">Test Duration</label>
                    <div style="padding: 0.75rem; background: #f8fafc; border: 1px solid #d1d5db; border-radius: 6px; color: #374151; font-size: 1rem;">
                        30 seconds (Fixed)
                    </div>
                </div>
                <div class="form-group">
                    <button id="startTestBtn" class="form-btn">Start Testing</button>
                </div>
            </div>
        </div>

        <!-- Test Status -->
        <div id="testStatus" class="test-status">
            <div class="spinner"></div>
            <span>Test in progress...</span>
        </div>

        <!-- Test Results -->
        <div id="testResults" class="test-results">
            <h4>📊 Test Results</h4>
            <div class="results-grid">
                <!-- Sensor Data -->
                <div class="sensor-data">
                    <h5>Sensor Readings</h5>
                    <div class="data-row">
                        <span class="data-label">N:</span>
                        <span id="nValue" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">P:</span>
                        <span id="pValue" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">K:</span>
                        <span id="kValue" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">pH Level:</span>
                        <span id="phValue" class="data-value">-</span>
                    </div>
                </div>

                <!-- Analysis -->
                <div class="soil-analysis">
                    <h5>Soil Analysis</h5>
                    <div class="data-row">
                        <span class="data-label">Location:</span>
                        <span id="testLocation" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Type of soil:</span>
                        <span id="soilType" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Fertilizer Recommendation:</span>
                        <div id="recommendations" class="data-value" style="text-align: right; font-size: 0.85rem;">-</div>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Prediction:</span>
                        <span id="prediction" class="data-value">-</span>
                    </div>
                </div>

                <!-- Thresholds -->
                <div class="thresholds-section">
                    <h5>Thresholds</h5>
                    <div class="data-row">
                        <span class="data-label">N:</span>
                        <span id="nThreshold" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">P:</span>
                        <span id="pThreshold" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">K:</span>
                        <span id="kThreshold" class="data-value">-</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">pH:</span>
                        <span id="phThreshold" class="data-value">-</span>
                    </div>
                </div>
            </div>

            <!-- Test Info -->
            <div style="margin-top: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 6px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.9rem;">
                    <div>
                        <span style="color: #64748b;">Test Time:</span>
                        <span id="testTime" style="font-weight: 600; margin-left: 0.5rem;">-</span>
                    </div>
                    <div>
                        <span style="color: #64748b;">Readings Count:</span>
                        <span id="readingsCount" style="font-weight: 600; margin-left: 0.5rem;">-</span>
                    </div>
                    <div>
                        <span style="color: #64748b;">Coordinates:</span>
                        <span id="coordinates" style="font-weight: 600; margin-left: 0.5rem;">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Section -->
        <div class="debug-section">
            <h4>🔧 Debug & Connection</h4>
            <div class="debug-grid">
                <button id="testConnectionBtn" class="debug-btn primary">Test ESP32 Connection</button>
                <button id="getLatestDataBtn" class="debug-btn success">Get Latest Data</button>
                <button id="clearResultsBtn" class="debug-btn danger">Clear Results</button>
                <button id="refreshBtn" class="debug-btn primary">Refresh Page</button>
            </div>
        </div>

        <!-- Status Messages -->
        <div id="statusMessage" class="status-message"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1jRVv6G8z1p3p1p2Vb0z0XQ=" crossorigin=""></script>
<script>
    let testInProgress = false;
    let testInterval = null;
    let map = null;
    let currentMarker = null;
    let currentLocation = null;

    // Initialize map
    function initMap() {
        if (map) return;
        
        map = L.map('map').setView([14.5995, 120.9842], 10); // Default to Philippines
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    }

    // Initialize map on page load
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });

    // Get CSRF token function
    function getCSRFToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        // Fallback: try to get from cookie
        const token = document.cookie.split(';').find(c => c.trim().startsWith('XSRF-TOKEN='));
        if (token) {
            return decodeURIComponent(token.split('=')[1]);
        }
        return null;
    }

    // Get Current Location Button
    document.getElementById('getLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            showStatus('Getting your location...', 'info');
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    currentLocation = {
                        lat: lat,
                        lng: lng,
                        address: `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                    };
                    
                    // Update map
                    if (currentMarker) {
                        map.removeLayer(currentMarker);
                    }
                    
                    currentMarker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                    
                    // Update location info
                    document.getElementById('locationInfo').textContent = `Location: ${currentLocation.address}`;
                    document.getElementById('testLocation').textContent = currentLocation.address;
                    document.getElementById('coordinates').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    
                    showStatus('Location obtained successfully!', 'success');
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    showStatus('Failed to get location: ' + error.message, 'error');
                }
            );
        } else {
            showStatus('Geolocation is not supported by this browser.', 'error');
        }
    });

    // Clear Location Button
    document.getElementById('clearLocationBtn').addEventListener('click', function() {
        if (currentMarker) {
            map.removeLayer(currentMarker);
            currentMarker = null;
        }
        currentLocation = null;
        document.getElementById('locationInfo').textContent = 'Click "Get Current Location" to see your position';
        document.getElementById('testLocation').textContent = '-';
        document.getElementById('coordinates').textContent = '-';
        showStatus('Location cleared.', 'info');
    });

    // Start Test Button
    document.getElementById('startTestBtn').addEventListener('click', async function() {
        if (testInProgress) {
            showStatus('Test already in progress!', 'warning');
            return;
        }

        if (!currentLocation) {
            showStatus('Please get your current location first!', 'warning');
            return;
        }

        const duration = 30; // Fixed 30 seconds

        try {
            testInProgress = true;
            showTestStatus(true);
            showStatus(`Starting test for ${duration} seconds...`, 'info');

            // Get CSRF token
            const csrfToken = getCSRFToken();
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }

            // Call Laravel command to start Python script
            const response = await fetch('/api/sensor-test/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    duration: parseInt(duration),
                    location: currentLocation
                })
            });

            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.message || `HTTP ${response.status}: ${response.statusText}`);
            }

            if (result.success) {
                showStatus('Test started successfully!', 'success');
                // Wait for test to complete
                await waitForTestCompletion(duration);
            } else {
                throw new Error(result.message || 'Failed to start test');
            }

        } catch (error) {
            console.error('Test error:', error);
            showStatus('Failed to start test: ' + error.message, 'error');
        } finally {
            testInProgress = false;
            showTestStatus(false);
        }
    });

    // Wait for test completion
    async function waitForTestCompletion(duration) {
        const startTime = Date.now();
        const testDuration = duration * 1000;

        while (Date.now() - startTime < testDuration) {
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            // Check if test results are available
            try {
                const results = await fetch('/api/sensor-readings/test-results');
                if (results.ok) {
                    const data = await results.json();
                    if (data.success) {
                        displayTestResults(data.data);
                        showStatus('Test completed successfully!', 'success');
                        return;
                    }
                }
            } catch (error) {
                console.log('Checking for results...');
            }
        }

        // After duration, check for results one more time
        try {
            const results = await fetch('/api/sensor-readings/test-results');
            if (results.ok) {
                const data = await results.json();
                if (data.success) {
                    displayTestResults(data.data);
                    showStatus('Test completed successfully!', 'success');
                } else {
                    showStatus('Test completed but no results found.', 'warning');
                }
            }
        } catch (error) {
            showStatus('Test completed but failed to get results.', 'error');
        }
    }

    // Display test results
    function displayTestResults(data) {
        document.getElementById('testResults').style.display = 'block';
        
        // Sensor data
        document.getElementById('nValue').textContent = data.sensor_data.n + ' mg/kg';
        document.getElementById('pValue').textContent = data.sensor_data.p + ' mg/kg';
        document.getElementById('kValue').textContent = data.sensor_data.k + ' mg/kg';
        document.getElementById('phValue').textContent = data.sensor_data.ph;
        
        // Analysis
        document.getElementById('soilType').textContent = data.analysis.soil_type;
        document.getElementById('recommendations').textContent = data.analysis.recommendations;
        document.getElementById('prediction').textContent = data.analysis.prediction || 'Optimal growth expected';
        
        // Thresholds based on soil type
        const thresholds = getThresholdsForSoilType(data.analysis.soil_type);
        document.getElementById('nThreshold').textContent = thresholds.n;
        document.getElementById('pThreshold').textContent = thresholds.p;
        document.getElementById('kThreshold').textContent = thresholds.k;
        document.getElementById('phThreshold').textContent = thresholds.ph;
        
        // Test info
        document.getElementById('testTime').textContent = data.timestamp;
        document.getElementById('readingsCount').textContent = data.readings_count;
        
        // Update location if not already set
        if (currentLocation) {
            document.getElementById('testLocation').textContent = currentLocation.address;
            document.getElementById('coordinates').textContent = `${currentLocation.lat.toFixed(6)}, ${currentLocation.lng.toFixed(6)}`;
        }
    }

    // Get thresholds based on soil type
    function getThresholdsForSoilType(soilType) {
        const thresholds = {
            'Loam': {
                n: '45-50 mg/kg',
                p: '45-50 mg/kg',
                k: '45-50 mg/kg',
                ph: '6.0-7.0'
            },
            'Clay': {
                n: '40-45 mg/kg',
                p: '40-45 mg/kg',
                k: '40-45 mg/kg',
                ph: '6.0-7.5'
            },
            'Sandy': {
                n: '50-55 mg/kg',
                p: '50-55 mg/kg',
                k: '50-55 mg/kg',
                ph: '5.5-6.5'
            },
            'Silt': {
                n: '45-50 mg/kg',
                p: '45-50 mg/kg',
                k: '45-50 mg/kg',
                ph: '6.0-7.0'
            },
            'Acidic, Low Fertility': {
                n: '30-40 mg/kg',
                p: '20-30 mg/kg',
                k: '100-150 mg/kg',
                ph: '5.0-6.0'
            },
            'Acidic, Moderate Fertility': {
                n: '40-60 mg/kg',
                p: '30-50 mg/kg',
                k: '150-200 mg/kg',
                ph: '5.0-6.0'
            },
            'Alkaline, Low Fertility': {
                n: '30-40 mg/kg',
                p: '20-30 mg/kg',
                k: '100-150 mg/kg',
                ph: '7.5-8.5'
            },
            'Alkaline, Moderate Fertility': {
                n: '40-60 mg/kg',
                p: '30-50 mg/kg',
                k: '150-200 mg/kg',
                ph: '7.5-8.5'
            },
            'Neutral, High Fertility': {
                n: '100-150 mg/kg',
                p: '50-80 mg/kg',
                k: '200-300 mg/kg',
                ph: '6.0-7.0'
            },
            'Neutral, Moderate Fertility': {
                n: '50-100 mg/kg',
                p: '30-50 mg/kg',
                k: '150-200 mg/kg',
                ph: '6.0-7.0'
            },
            'Neutral, Low Fertility': {
                n: '30-50 mg/kg',
                p: '20-30 mg/kg',
                k: '100-150 mg/kg',
                ph: '6.0-7.0'
            }
        };
        
        return thresholds[soilType] || thresholds['Loam'];
    }

    // Test Connection Button
    document.getElementById('testConnectionBtn').addEventListener('click', async function() {
        try {
            showStatus('Testing ESP32 connection...', 'info');
            
            const response = await fetch('/api/sensor-readings/latest');
            const data = await response.json();
            
            if (data.success) {
                showStatus('ESP32 connection successful! Latest data available.', 'success');
            } else {
                showStatus('ESP32 connection failed. No data available.', 'error');
            }
        } catch (error) {
            showStatus('Connection test failed: ' + error.message, 'error');
        }
    });

    // Get Latest Data Button
    document.getElementById('getLatestDataBtn').addEventListener('click', async function() {
        try {
            showStatus('Fetching latest data...', 'info');
            
            const response = await fetch('/api/sensor-readings/latest');
            const data = await response.json();
            
            if (data.success) {
                displayTestResults(data.data);
                showStatus('Latest data loaded successfully!', 'success');
            } else {
                showStatus('No latest data available.', 'warning');
            }
        } catch (error) {
            showStatus('Failed to get latest data: ' + error.message, 'error');
        }
    });

    // Clear Results Button
    document.getElementById('clearResultsBtn').addEventListener('click', function() {
        document.getElementById('testResults').style.display = 'none';
        showStatus('Results cleared.', 'info');
    });

    // Refresh Button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });

    // Helper functions
    function showTestStatus(show) {
        const statusDiv = document.getElementById('testStatus');
        if (show) {
            statusDiv.style.display = 'block';
        } else {
            statusDiv.style.display = 'none';
        }
    }

    function showStatus(message, type) {
        const statusDiv = document.getElementById('statusMessage');
        statusDiv.textContent = message;
        statusDiv.className = `status-message ${type}`;
        statusDiv.style.display = 'block';
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 5000);
    }
</script>
@endsection 