@extends('layouts.user')

@section('title', 'Soil Testing - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<style>
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
    .container {
        max-width: 900px;
        margin: 2.5rem auto 0 auto;
        padding: 0 1.5rem 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .card {
        width: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 2.5rem 2.5rem 2rem 2.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .title {
        font-size: 2.1rem;
        font-weight: 800;
        color: #228B22;
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
        text-align: center;
    }
    .desc {
        color: #6b7280;
        margin-bottom: 2rem;
        line-height: 1.6;
        text-align: center;
        font-size: 1.1rem;
    }
    .form-group {
        margin-bottom: 1.2rem;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.3rem;
        min-width: 110px;
    }
    .form-select, .form-input {
        width: 220px;
        padding: 0.7rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        background: #f9fafb;
        color: #374151;
    }
    .form-btn {
        background: #228B22;
        color: white;
        border: none;
        padding: 0.8rem 2.2rem;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 1.1rem;
        transition: background 0.2s;
        margin-top: 0.5rem;
    }
    .form-btn:hover {
        background: #166534;
    }
    .progress-bar {
        width: 100%;
        height: 10px;
        background: #e5e7eb;
        border-radius: 6px;
        margin-bottom: 1.2rem;
        overflow: hidden;
    }
    .progress-inner {
        height: 100%;
        background: #228B22;
        width: 0%;
        transition: width 0.3s;
    }
    .sensor-cards {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        margin-top: 0.5rem;
    }
    .sensor-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.2rem 0.5rem 1.2rem 0.5rem;
        text-align: center;
        min-width: 90px;
        box-shadow: 0 1px 3px rgba(34,139,34,0.04);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .sensor-label {
        font-size: 1.05rem;
        color: #64748b;
        margin-bottom: 0.3rem;
        font-weight: 500;
    }
    .sensor-value {
        font-size: 2.1rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
        letter-spacing: -1px;
    }
    .sensor-optimal { color: #228B22; }
    .sensor-warning { color: #eab308; }
    .sensor-danger { color: #dc2626; }
    .result-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        margin-top: 1.5rem;
        width: 100%;
        /* display: none; */
    }
    .result-title {
        color: #1e293b;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1.2rem;
        text-align: center;
    }
    .result-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .result-label {
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
    }
    .result-value {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.1rem;
    }
    .recommend {
        color: #228B22;
        font-weight: 700;
    }
    .prediction {
        color: #228B22;
        font-weight: 700;
        margin-top: 1.2rem;
    }
    .timer {
        font-size: 1.2rem;
        font-family: monospace;
        color: #228B22;
        margin-left: 1rem;
    }
    .save-msg {
        color: #228B22;
        font-weight: 600;
        margin-left: 1rem;
    }
    .hidden { display: none; }
    
    /* Graph Styles */
    .graph-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .graph-title {
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.8rem;
        font-size: 1.1rem;
    }
    .chart-wrapper {
        position: relative;
        height: 150px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .sensor-chart-wrapper {
        position: relative;
        height: 150px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    canvas {
        max-height: 150px !important;
        height: 150px !important;
    }
    
    @media (max-width: 900px) {
        .container { max-width: 100vw; padding: 0 0.5rem; }
        .card { padding: 1.2rem; }
        .sensor-cards { grid-template-columns: 1fr 1fr; }
        .result-section { padding: 1rem; }
    }
    @media (max-width: 600px) {
        .container { padding: 0.2rem; }
        .card { padding: 0.5rem; }
        .sensor-cards { grid-template-columns: 1fr; gap: 0.7rem; }
        .result-section { padding: 0.5rem; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="title">🧪 Real-Time Soil Testing</div>
        <div class="desc">Connect your NPK and pH sensors to the system. The sensors will automatically collect soil data and provide real-time analysis and recommendations.</div>
        <div class="form-group">
            <label class="form-label">Test Duration</label>
            <div style="padding: 0.7rem 1rem; background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; color: #374151; font-size: 1rem; max-width: 220px; display: inline-block;">
                20 seconds
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Location</label>
            <span id="locationDisplay" class="ml-2" style="margin-left:1rem;"></span>
            <button id="getLocationBtn" class="form-btn" type="button" style="margin-left:1rem;">Get Location</button>
        </div>
        <div class="form-group">
            <button id="startBtn" class="form-btn" type="button" disabled>Start Testing</button>
            <span id="timer" class="timer"></span>
        </div>
        <div class="progress-bar"><div id="progressInner" class="progress-inner"></div></div>
        <div class="sensor-cards">
            <div class="sensor-card">
                <div class="sensor-label">N (%)</div>
                <div id="nVal" class="sensor-value">-</div>
            </div>
            <div class="sensor-card">
                <div class="sensor-label">P (ppm)</div>
                <div id="pVal" class="sensor-value">-</div>
            </div>
            <div class="sensor-card">
                <div class="sensor-label">K (ppm)</div>
                <div id="kVal" class="sensor-value">-</div>
            </div>
            <div class="sensor-card">
                <div class="sensor-label">pH</div>
                <div id="phVal" class="sensor-value">-</div>
            </div>
        </div>
        <div id="spinner" class="spinner hidden"></div>
        <div id="resultSection" class="result-section">
            <div class="result-title">Test Results</div>
            <div class="result-grid">
                        <div>
            <div class="result-label" style="font-weight:bold;">Measured Values</div>
            <div class="result-label">N (%): <span class="result-value" id="nAvg">-</span></div>
            <div class="result-label">P (ppm): <span class="result-value" id="pAvg">-</span></div>
            <div class="result-label">K (ppm): <span class="result-value" id="kAvg">-</span></div>
            <div class="result-label">pH: <span class="result-value" id="phAvg">-</span></div>
        </div>
                        <div>
            <div class="result-label" style="font-weight:bold;">Soil Analysis (ANN)</div>
            <div class="result-label">Soil Type: <span class="result-value" id="soilType">-</span></div>
            <div class="result-label">Texture: <span class="result-value" id="soilTexture">-</span></div>
            <div class="result-label">Ideal NPK & pH: <span class="result-value" id="idealNpkPh">-</span></div>
            <div class="result-label">Recommended Crops: <span class="result-value" id="recommendedCrops">-</span></div>
        </div>
        <div>
            <div class="result-label" style="font-weight:bold;">Fertilizer Recommendation (ANN)</div>
            <div class="result-label">Recommended Fertilizer: <span class="result-value" id="recommendedFertilizer">-</span></div>
            <div class="result-label">NPK Deficiency: <span class="result-value" id="npkDeficiency">-</span></div>
            <div class="result-label">Recommended Amounts: <span class="result-value" id="fertilizerAmounts">-</span></div>
        </div>
            </div>
            <div style="margin-top:1.2rem;">
                <div class="result-label" style="font-weight:bold;">Location & Timestamp</div>
                <div class="result-label">Location: <span class="result-value" id="finalLocation">-</span></div>
                <div class="result-label">Timestamp: <span class="result-value" id="finalTimestamp">-</span></div>
            </div>
            
            <!-- Graph Section -->
            <div style="margin-top:1.5rem;">
                <div class="result-label" style="font-weight:bold;">📊 Sensor Data vs Predicted Values</div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <div style="flex: 1;">
                        <div class="result-label" style="font-weight:bold; margin-bottom: 0.5rem;">NPK Values</div>
                        <div style="height: 150px; overflow: hidden;">
                            <canvas id="npkChart" width="300" height="150" style="border: 1px solid #ddd; border-radius: 8px; background: white; max-height: 150px;"></canvas>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <div class="result-label" style="font-weight:bold; margin-bottom: 0.5rem;">pH Level</div>
                        <div style="height: 150px; overflow: hidden;">
                            <canvas id="phChart" width="300" height="150" style="border: 1px solid #ddd; border-radius: 8px; background: white; max-height: 150px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-top:1.5rem;">
                <button id="saveBtn" class="form-btn">Save Result</button>
                <span id="saveMsg" class="save-msg"></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// --- State ---
let timer = null;
let polling = null;
let duration = 0;
let remaining = 0;
let readings = [];
let userLocation = null;
let userAddress = '';
let finalResult = null;
let testStarted = false;
let testEnded = false;
let timestamp = null;
let lastAnnResult = null; // Store the last ANN result for saving

// --- Elements ---
const getLocationBtn = document.getElementById('getLocationBtn');
const locationDisplay = document.getElementById('locationDisplay');
const startBtn = document.getElementById('startBtn');
const timerDisplay = document.getElementById('timer');
const progressInner = document.getElementById('progressInner');
const spinner = document.getElementById('spinner');
const nVal = document.getElementById('nVal');
const pVal = document.getElementById('pVal');
const kVal = document.getElementById('kVal');
const phVal = document.getElementById('phVal');
const nAvg = document.getElementById('nAvg');
const pAvg = document.getElementById('pAvg');
const kAvg = document.getElementById('kAvg');
const phAvg = document.getElementById('phAvg');
const soilType = document.getElementById('soilType');
const recommendation = document.getElementById('recommendation');
const prediction = document.getElementById('prediction');
const finalLocation = document.getElementById('finalLocation');
const finalTimestamp = document.getElementById('finalTimestamp');
const resultSection = document.getElementById('resultSection');
const saveBtn = document.getElementById('saveBtn');
const saveMsg = document.getElementById('saveMsg');

document.addEventListener('DOMContentLoaded', function() {
    // Remove crop selector code entirely
});

// --- Duration is fixed to 30 seconds ---
// Removed duration select logic since duration is now fixed

// --- Location detection ---
function detectLocation(auto = false) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            userLocation = {
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude
            };
            // Reverse geocode to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${userLocation.latitude}&lon=${userLocation.longitude}`)
                .then(res => res.json())
                .then(data => {
                    userAddress = data.display_name || `${userLocation.latitude.toFixed(6)}, ${userLocation.longitude.toFixed(6)}`;
                    locationDisplay.textContent = userAddress;
                    checkReady();
                    checkSaveReady();
                })
                .catch(() => {
                    userAddress = `${userLocation.latitude.toFixed(6)}, ${userLocation.longitude.toFixed(6)}`;
                    locationDisplay.textContent = userAddress;
                    checkReady();
                    checkSaveReady();
                });
        }, function(err) {
            locationDisplay.textContent = 'Location error';
            userLocation = null;
            userAddress = '';
            checkReady();
            checkSaveReady();
        });
    } else {
        locationDisplay.textContent = 'Not supported';
        userLocation = null;
        userAddress = '';
        checkReady();
        checkSaveReady();
    }
}
getLocationBtn.onclick = function() { detectLocation(false); };
window.addEventListener('DOMContentLoaded', function() { detectLocation(true); });

// --- Enable Start button only if ready ---
function checkReady() {
    startBtn.disabled = !userLocation; // Only check for location since duration is fixed
}
function getDuration() {
    return 20; // Fixed 20 seconds
}

// --- Start Testing ---
startBtn.onclick = function() {
    duration = 20; // Fixed 20 seconds
    if (!userLocation) return;
    readings = [];
    testStarted = true;
    testEnded = false;
    timestamp = new Date();
    startBtn.disabled = true;
    saveBtn.disabled = true;
    saveMsg.textContent = '';
    resultSection.style.display = 'block'; // Ensure it's visible
    spinner.classList.remove('hidden');
    progressInner.style.width = '0%';
    setLoadingState(true); // Show loading on cards

    // --- Synchronize timer and backend response ---
    let secondsLeft = 20;
    let timerDone = false;
    let responseDone = false;
    let backendResult = null;
    timerDisplay.textContent = formatTime(secondsLeft);
    if (timer) clearInterval(timer);
    timer = setInterval(function() {
        secondsLeft--;
        timerDisplay.textContent = formatTime(secondsLeft);
        if (secondsLeft <= 0) {
            clearInterval(timer);
            timerDisplay.textContent = '00:00';
            timerDone = true;
            maybeShowResult();
        }
    }, 1000);

    // Get CSRF token safely
    let csrfMeta = document.querySelector('meta[name="csrf-token"]');
    let csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
    if (!csrfToken) {
        spinner.classList.add('hidden');
        startBtn.disabled = false;
        alert('CSRF token not found. Please refresh the page.');
        return;
    }
    // DEBUG: Log fetch config
    console.log('Sending POST to /testing/collect', {
        duration, userLocation, userAddress, csrfToken
    });
    fetch('/testing/collect', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            duration: duration,
            latitude: userLocation.latitude,
            longitude: userLocation.longitude,
            address: userAddress
        })
    })
    .then(res => {
        console.log('Response from /testing/collect:', res);
        return res.json();
    })
    .then(data => {
        backendResult = data;
        responseDone = true;
        maybeShowResult();
    })
    .catch(err => {
        spinner.classList.add('hidden');
        setLoadingState(false);
        startBtn.disabled = false;
        console.error('Fetch error:', err);
        alert('Failed to collect sensor data: ' + err);
    });

    // Helper to show result only when both timer and response are done
    function maybeShowResult() {
        if (!(timerDone && responseDone)) return;
        spinner.classList.add('hidden');
        if (backendResult && backendResult.success) {
            readings = backendResult.data.readings || [];
            if (readings.length === 0) {
                setLoadingState(false);
                alert('No sensor data collected!');
                startBtn.disabled = false;
                return;
            }
            // Calculate averages for test results
            let sum = {n:0, p:0, k:0, ph:0};
            readings.forEach(r => {
                sum.n += r.n;
                sum.p += r.p;
                sum.k += r.k;
                sum.ph += r.ph;
            });
            let avg = {
                n: (sum.n/readings.length).toFixed(4),
                p: (sum.p/readings.length).toFixed(1),
                k: (sum.k/readings.length).toFixed(1),
                ph: (sum.ph/readings.length).toFixed(2)
            };
            // Update test results with averaged values
            nAvg.textContent = avg.n;
            pAvg.textContent = avg.p;
            kAvg.textContent = avg.k;
            phAvg.textContent = avg.ph;
            // Update real-time display with the averaged values (for consistency)
            nVal.textContent = avg.n;
            pVal.textContent = avg.p;
            kVal.textContent = avg.k;
            phVal.textContent = avg.ph;
            computeFinal();
        } else {
            setLoadingState(false);
            alert('Failed to collect sensor data: ' + (backendResult && backendResult.message ? backendResult.message : 'Unknown error'));
            startBtn.disabled = false;
        }
    }
}

// --- Real-time polling ---
function fetchLatest() {
    fetch('/testing/latest')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const n = parseFloat(data.data.n);
                const p = parseFloat(data.data.p);
                const k = parseFloat(data.data.k);
                const ph = parseFloat(data.data.ph);
                
                nVal.textContent = data.data.n;
                pVal.textContent = data.data.p;
                kVal.textContent = data.data.k;
                phVal.textContent = data.data.ph;
                colorSensor(nVal, data.data.n, 'n');
                colorSensor(pVal, data.data.p, 'p');
                colorSensor(kVal, data.data.k, 'k');
                colorSensor(phVal, data.data.ph, 'ph');
                
                // No real-time graph updates - only show comparison after testing
                
                readings.push({
                    n: n,
                    p: p,
                    k: k,
                    ph: ph
                });
            }
        });
}

// --- Color logic for sensor cards ---
function colorSensor(el, val, type) {
    el.classList.remove('sensor-optimal','sensor-warning','sensor-danger');
    val = parseFloat(val);
    if (type==='n') {
        if (val >= 100) el.classList.add('sensor-optimal');
        else if (val >= 50) el.classList.add('sensor-warning');
        else el.classList.add('sensor-danger');
    } else if (type==='p') {
        if (val >= 50) el.classList.add('sensor-optimal');
        else if (val >= 30) el.classList.add('sensor-warning');
        else el.classList.add('sensor-danger');
    } else if (type==='k') {
        if (val >= 200) el.classList.add('sensor-optimal');
        else if (val >= 150) el.classList.add('sensor-warning');
        else el.classList.add('sensor-danger');
    } else if (type==='ph') {
        if (val >= 6.0 && val <= 7.0) el.classList.add('sensor-optimal');
        else if ((val >= 5.5 && val < 6.0) || (val > 7.0 && val <= 7.5)) el.classList.add('sensor-warning');
        else el.classList.add('sensor-danger');
    }
}

// --- Compute and show final results ---
function computeFinal() {
    if (readings.length === 0) {
        alert('No sensor data collected!');
        return;
    }
    
    // Use the already calculated averages from the test results
    const avgN = parseFloat(nAvg.textContent);
    const avgP = parseFloat(pAvg.textContent);
    const avgK = parseFloat(kAvg.textContent);
    const avgPh = parseFloat(phAvg.textContent);
    
    // Update comparison graphs will be done after ANN response
    
    // --- ANN Integration ---
    let organicCarbon = 1.5; // Default, or replace with real value if available
    fetch('http://127.0.0.1:8001/predict', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            nitrogen: avgN,
            phosphorus: avgP,
            potassium: avgK,
            ph: avgPh,
            organic_carbon: organicCarbon
        })
    })
    .then(res => res.json())
    .then(data => {
        lastAnnResult = data; // Store for saving
        console.log('ANN response:', data);
        console.log('Soil data:', data.soil);
        console.log('Soil type:', data.soil ? data.soil.soil_type : 'NO SOIL DATA');
        
        // Update soil analysis fields
        const soilType = document.getElementById('soilType');
        const soilTexture = document.getElementById('soilTexture');
        const idealNpkPh = document.getElementById('idealNpkPh');
        const recommendedCrops = document.getElementById('recommendedCrops');
        const recommendedFertilizer = document.getElementById('recommendedFertilizer');
        const npkDeficiency = document.getElementById('npkDeficiency');
        const fertilizerAmounts = document.getElementById('fertilizerAmounts');
        const finalLocation = document.getElementById('finalLocation');
        const finalTimestamp = document.getElementById('finalTimestamp');
        
        // Set soil type with detailed logging
        if (data.soil && data.soil.soil_type && data.soil.soil_type !== '') {
            console.log('Setting soil type to:', data.soil.soil_type);
            soilType.textContent = data.soil.soil_type;
        } else {
            console.log('No soil type found, setting to "-"');
            soilType.textContent = '-';
        }
        
        // Set texture
        if (data.soil && data.soil.texture) {
            soilTexture.textContent = data.soil.texture;
        } else {
            soilTexture.textContent = '-';
        }
        
        // Set ideal NPK & pH
        if (data.soil && data.soil.ideal_npk_ph) {
            const ideal = data.soil.ideal_npk_ph;
            idealNpkPh.textContent = `N: ${ideal.N}%, P: ${ideal.P} ppm, K: ${ideal.K} ppm, pH: ${ideal.pH}`;
        } else {
            idealNpkPh.textContent = '-';
        }
        
        // Set recommended crops
        if (data.soil && data.soil.recommended_crops) {
            recommendedCrops.textContent = data.soil.recommended_crops;
        } else {
            recommendedCrops.textContent = '-';
        }
        
        // Set fertilizer recommendations
        if (data.fertilizer && data.fertilizer.recommended_fertilizer) {
            recommendedFertilizer.textContent = data.fertilizer.recommended_fertilizer;
        } else {
            recommendedFertilizer.textContent = '-';
        }
        
        // Set NPK deficiency
        if (data.fertilizer && data.fertilizer.deficiency) {
            const def = data.fertilizer.deficiency;
            npkDeficiency.textContent = `N: ${def.N.toFixed(4)}%, P: ${def.P.toFixed(1)} ppm, K: ${def.K.toFixed(1)} ppm`;
        } else {
            npkDeficiency.textContent = '-';
        }
        
        // Set fertilizer amounts
        if (data.fertilizer && data.fertilizer.recommended_amounts) {
            const amounts = data.fertilizer.recommended_amounts;
            const amountList = Object.entries(amounts).map(([fert, amt]) => `${fert}: ${amt} kg/ha`).join(', ');
            fertilizerAmounts.textContent = amountList || '-';
        } else {
            fertilizerAmounts.textContent = '-';
        }
        
        // Set location
        if (userAddress && userAddress.length > 5) {
            finalLocation.textContent = userAddress;
        } else if (userLocation) {
            finalLocation.textContent = `${userLocation.latitude.toFixed(6)}, ${userLocation.longitude.toFixed(6)}`;
        } else {
            finalLocation.textContent = '-';
        }
        
        // Set timestamp
        if (timestamp) {
            finalTimestamp.textContent = timestamp.toLocaleString();
        } else {
            finalTimestamp.textContent = new Date().toLocaleString();
        }
        
        // Update comparison charts with measured vs ideal values
        if (data.soil && data.soil.ideal_npk_ph) {
            const ideal = data.soil.ideal_npk_ph;
            updateNPKChart(avgN, avgP, avgK, ideal.N, ideal.P, ideal.K);
            updatePHChart(avgPh, ideal.pH);
        }
        
        saveBtn.disabled = false;
        resultSection.style.display = 'block';
    })
    .catch(err => {
        lastAnnResult = null;
        soilType.textContent = '-';
        recommendation.textContent = 'ANN service error.';
        prediction.textContent = '';
        resultSection.style.display = 'block';
        saveBtn.disabled = false;
    });
}
// --- Remove old analyzeSoil logic from result display ---

// --- Save to history ---
saveBtn.onclick = function() {
    if (!lastAnnResult || !lastAnnResult.soil) return;
    if (!userAddress || userAddress.length < 5) {
        saveMsg.textContent = 'Please wait for the address to load before saving.';
        saveBtn.disabled = true;
        return;
    }
    saveBtn.disabled = true;
    saveMsg.textContent = '';
    let csrfMeta = document.querySelector('meta[name="csrf-token"]');
    let csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
    let addressToSave = userAddress || (userLocation ? `${userLocation.latitude}, ${userLocation.longitude}` : '');
    let idealN = lastAnnResult.soil.ideal_npk_ph.N;
    let idealP = lastAnnResult.soil.ideal_npk_ph.P;
    let idealK = lastAnnResult.soil.ideal_npk_ph.K;
    let idealPH = lastAnnResult.soil.ideal_npk_ph.pH;
    const saveData = {
        n: nAvg.textContent,
        p: pAvg.textContent,
        k: kAvg.textContent,
        ph: phAvg.textContent,
        latitude: userLocation.latitude,
        longitude: userLocation.longitude,
        address: addressToSave,
        soil_type: soilType.textContent,
        recommendation: document.getElementById('recommendedFertilizer').textContent + ' - ' + document.getElementById('npkDeficiency').textContent,
        prediction: document.getElementById('soilTexture').textContent + ' - ' + document.getElementById('recommendedCrops').textContent,
        ideal_n: idealN,
        ideal_p: idealP,
        ideal_k: idealK,
        ideal_ph: idealPH
    };
    
    console.log('Saving data:', saveData);
    
    fetch('/testing/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(saveData)
    })
    .then(res => {
        console.log('Save response status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Save response data:', data);
        if (data.success) {
            saveMsg.textContent = 'Result saved!';
            saveBtn.disabled = true;
        } else {
            saveMsg.textContent = 'Save failed: ' + (data.message || 'Unknown error');
            saveBtn.disabled = false;
        }
    })
    .catch(err => {
        console.error('Save error:', err);
        saveMsg.textContent = 'Save failed: ' + err.message;
        saveBtn.disabled = false;
    });
};

// --- Timer formatting ---
function formatTime(sec) {
    let m = Math.floor(sec/60);
    let s = sec%60;
    return `${m}:${s.toString().padStart(2,'0')}`;
}

// Add spinner HTML for loading state
function setLoadingState(isLoading) {
    const loadingHTML = '<span class="spinner" style="display:inline-block;width:18px;height:18px;border:2px solid #e5e7eb;border-radius:50%;border-top-color:#228B22;animation:spin 1s linear infinite;"></span> Loading...';
    if (isLoading) {
        nVal.innerHTML = loadingHTML;
        pVal.innerHTML = loadingHTML;
        kVal.innerHTML = loadingHTML;
        phVal.innerHTML = loadingHTML;
    } else {
        nVal.textContent = '-';
        pVal.textContent = '-';
        kVal.textContent = '-';
        phVal.textContent = '-';
    }
}

// Add spinner CSS
const style = document.createElement('style');
style.innerHTML = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(style);

// Disable Save Result button until address is available
function checkSaveReady() {
    if (userAddress && userAddress.length > 5) {
        saveBtn.disabled = false;
        saveMsg.textContent = '';
    } else {
        saveBtn.disabled = true;
        saveMsg.textContent = 'Waiting for address...';
    }
}

// --- Graph Functions ---
let npkChart = null;
let phChart = null;

// Initialize charts
function initializeCharts() {
    // NPK Comparison Bar Chart
    const npkCanvas = document.getElementById('npkChart');
    const npkCtx = npkCanvas.getContext('2d');
    npkChart = new Chart(npkCtx, {
        type: 'bar',
        data: {
            labels: ['N (%)', 'P (ppm)', 'K (ppm)'],
            datasets: [
                {
                    label: 'Detected by Sensor',
                    data: [0, 0, 0],
                    backgroundColor: ['#FF6B6B', '#4ECDC4', '#45B7D1'],
                    borderColor: ['#FF5252', '#26A69A', '#1976D2'],
                    borderWidth: 1
                },
                {
                    label: 'Predicted Ideal',
                    data: [0, 0, 0],
                    backgroundColor: ['#90EE90', '#98FB98', '#87CEEB'],
                    borderColor: ['#32CD32', '#00FA9A', '#4682B4'],
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            }
        }
    });

    // pH Comparison Chart
    const phCanvas = document.getElementById('phChart');
    const phCtx = phCanvas.getContext('2d');
    phChart = new Chart(phCtx, {
        type: 'bar',
        data: {
            labels: ['pH Level'],
            datasets: [
                {
                    label: 'Detected pH',
                    data: [0],
                    backgroundColor: '#FF9800',
                    borderColor: '#F57C00',
                    borderWidth: 1
                },
                {
                    label: 'Predicted pH',
                    data: [0],
                    backgroundColor: '#4CAF50',
                    borderColor: '#388E3C',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 14,
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 8,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            }
        }
    });
}

// Update NPK comparison chart
function updateNPKChart(measuredN, measuredP, measuredK, idealN, idealP, idealK) {
    if (npkChart) {
        npkChart.data.datasets[0].data = [measuredN, measuredP, measuredK]; // Measured values
        npkChart.data.datasets[1].data = [idealN, idealP, idealK]; // Ideal values
        npkChart.update('none');
    }
}

// Update pH comparison chart
function updatePHChart(measuredPh, idealPh) {
    if (phChart) {
        phChart.data.datasets[0].data = [measuredPh]; // Measured pH
        phChart.data.datasets[1].data = [idealPh]; // Ideal pH
        phChart.update('none');
    }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    } else {
        // Load Chart.js dynamically
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = function() {
            initializeCharts();
        };
        document.head.appendChild(script);
    }
});
</script>
@endsection 