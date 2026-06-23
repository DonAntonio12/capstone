@extends('layouts.user')

@section('title', 'Test History - ' . \App\Helpers\SystemHelper::getSiteName())

@section('styles')
<style>
    body {
        background-image: url('data:image/svg+xml;utf8,<svg width="120" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.18" stroke="%239CA3AF" stroke-width="1.2"><rect x="5" y="5" width="12" height="12" rx="2"/><circle cx="35" cy="12" r="6"/><ellipse cx="60" cy="10" rx="7" ry="4"/><path d="M80 8 Q85 2 90 8 Q95 14 90 20 Q85 26 80 20 Q75 14 80 8 Z"/><rect x="100" y="5" width="10" height="10" rx="3"/><path d="M15 40 Q20 35 25 40 Q30 45 25 50 Q20 55 15 50 Q10 45 15 40 Z"/><ellipse cx="45" cy="45" rx="6" ry="3"/><circle cx="70" cy="48" r="5"/><rect x="90" y="40" width="12" height="8" rx="2"/><path d="M60 30 Q62 28 64 30 Q66 32 64 34 Q62 36 60 34 Q58 32 60 30 Z"/><path d="M110 30 Q112 28 114 30 Q116 32 114 34 Q112 36 110 34 Q108 32 110 30 Z"/><path d="M30 25 Q32 23 34 25 Q36 27 34 29 Q32 31 30 29 Q28 27 30 25 Z"/></g></svg>');
        background-repeat: repeat;
        background-size: 220px 110px;
    }
    
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
    
    .filter-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: end;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-label {
        font-weight: 500;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .filter-input {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.2s ease;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #228B22;
        box-shadow: 0 0 0 3px rgba(34, 139, 34, 0.1);
    }
    
    .filter-btn {
        background: #228B22;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        font-size: 0.9rem;
    }
    
    .filter-btn:hover {
        background: #1a5f1a;
    }
    
    .history-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .history-table th {
        background: #f9fafb;
        color: #111827;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .history-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.9rem;
        color: #374151;
    }
    
    .history-table tr:last-child td {
        border-bottom: none;
    }
    
    .history-table tr:hover {
        background: #f9fafb;
    }
    
    .recommend {
        color: #228B22;
        font-weight: 600;
    }
    
    .view-btn {
        background: #228B22;
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .view-btn:hover {
        background: #1a5f1a;
    }
    
    .no-results {
        text-align: center;
        color: #6b7280;
        padding: 2rem;
        font-style: italic;
    }
    
    /* Modal Styles */
    .modal-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .modal-bg.active {
        display: flex;
    }
    
    .modal-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        padding: 0.25rem;
        border-radius: 4px;
        transition: background 0.2s ease;
    }
    
    .modal-close:hover {
        background: #f3f4f6;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
        padding-right: 2rem;
    }
    
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }
    
    .modal-table th,
    .modal-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .modal-table th {
        background: #f9fafb;
        color: #111827;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .modal-table td {
        color: #374151;
        font-size: 0.9rem;
    }
    
    .modal-table tr:last-child th,
    .modal-table tr:last-child td {
        border-bottom: none;
    }
    
    .modal-recommend {
        color: #228B22;
        font-weight: 600;
        padding: 1rem;
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    
    .modal-prediction {
        color: #374151;
        font-weight: 500;
        padding: 1rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
        .container { 
            padding: 1rem; 
        }
        .card { 
            padding: 1.5rem; 
        }
        
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
    }
    
    @media (max-width: 600px) {
        .container { 
            padding: 1rem; 
        }
        .card { 
            padding: 1.5rem; 
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="title">Test History</div>
        <div class="desc">View and filter your previous soil tests. Click 'View Details' for more info.</div>
        <!-- Filter Section -->
        <form class="filter-section" onsubmit="filterHistory(event)">
            <div class="filter-group">
                <label class="filter-label" for="filter-from">From</label>
                <input type="date" id="filter-from" class="filter-input">
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filter-to">To</label>
                <input type="date" id="filter-to" class="filter-input">
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filter-soil">Soil Type</label>
                <select id="filter-soil" class="filter-input">
                    <option value="">All</option>
                    <option value="Loam">Loam</option>
                    <option value="Clay">Clay</option>
                    <option value="Sandy">Sandy</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filter-n">Nitrogen</label>
                <select id="filter-n" class="filter-input">
                    <option value="">All</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filter-p">Phosphorus</label>
                <select id="filter-p" class="filter-input">
                    <option value="">All</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label" for="filter-k">Potassium</label>
                <select id="filter-k" class="filter-input">
                    <option value="">All</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <button type="submit" class="filter-btn">Filter</button>
        </form>
        <!-- Table Section -->
        <table class="history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Soil Type</th>
                    <th>N</th>
                    <th>P</th>
                    <th>K</th>
                    <th>Recommendation</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="history-tbody">
                <!-- JS will render rows here -->
            </tbody>
        </table>
        <div id="no-results" class="no-results" style="display:none;">No results found.</div>
    </div>
</div>
<!-- Modal for Details -->
<div id="modal-bg" class="modal-bg" onclick="if(event.target===this)closeModal()">
    <div class="modal-card">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <div class="modal-title">Test Details</div>
        <table class="modal-table">
            <tr><th>Date</th><td id="modal-date"></td></tr>
            <tr><th>Soil Type</th><td id="modal-soil"></td></tr>
            <tr><th>Nitrogen (N)</th><td id="modal-n"></td></tr>
            <tr><th>Phosphorus (P)</th><td id="modal-p"></td></tr>
            <tr><th>Potassium (K)</th><td id="modal-k"></td></tr>
        </table>
        <div class="modal-recommend" id="modal-recommend"></div>
        <div class="modal-prediction" id="modal-prediction"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Sample data for demonstration
    const historyData = [
        {
            date: '2024-06-01', soil: 'Loam', n: 'Low', p: 'Medium', k: 'High',
            recommend: 'Apply Urea or Ammonium Sulfate',
            prediction: 'If Nitrogen is corrected, yield may improve by 20% (ANN-based).'
        },
        {
            date: '2024-05-28', soil: 'Clay', n: 'Medium', p: 'Medium', k: 'Low',
            recommend: 'Add Muriate of Potash',
            prediction: 'Potassium correction may increase yield by 10%.'
        },
        {
            date: '2024-05-20', soil: 'Sandy', n: 'Low', p: 'Low', k: 'Low',
            recommend: 'Apply Complete Fertilizer (14-14-14)',
            prediction: 'Balanced fertilizer will improve overall soil health.'
        }
    ];
    let filteredData = [...historyData];
    function filterHistory(e) {
        if (e) e.preventDefault();
        const from = document.getElementById('filter-from').value;
        const to = document.getElementById('filter-to').value;
        const soil = document.getElementById('filter-soil').value;
        const n = document.getElementById('filter-n').value;
        const p = document.getElementById('filter-p').value;
        const k = document.getElementById('filter-k').value;
        filteredData = historyData.filter(row => {
            let pass = true;
            if (from && row.date < from) pass = false;
            if (to && row.date > to) pass = false;
            if (soil && soil !== 'All' && row.soil !== soil) pass = false;
            if (n && n !== 'All' && row.n !== n) pass = false;
            if (p && p !== 'All' && row.p !== p) pass = false;
            if (k && k !== 'All' && row.k !== k) pass = false;
            return pass;
        });
        renderTable();
    }
    function renderTable() {
        const tbody = document.getElementById('history-tbody');
        tbody.innerHTML = '';
        if (filteredData.length === 0) {
            document.getElementById('no-results').style.display = 'block';
            return;
        } else {
            document.getElementById('no-results').style.display = 'none';
        }
        filteredData.forEach((row, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.date}</td>
                <td>${row.soil}</td>
                <td>${row.n}</td>
                <td>${row.p}</td>
                <td>${row.k}</td>
                <td class="recommend">${row.recommend}</td>
                <td><button class="view-btn" onclick="showModal(${idx})">View Details</button></td>
            `;
            tbody.appendChild(tr);
        });
    }
    function showModal(idx) {
        const row = filteredData[idx];
        document.getElementById('modal-date').innerText = row.date;
        document.getElementById('modal-soil').innerText = row.soil;
        document.getElementById('modal-n').innerText = row.n;
        document.getElementById('modal-p').innerText = row.p;
        document.getElementById('modal-k').innerText = row.k;
        document.getElementById('modal-recommend').innerText = row.recommend;
        document.getElementById('modal-prediction').innerText = row.prediction;
        document.getElementById('modal-bg').classList.add('active');
    }
    function closeModal() {
        document.getElementById('modal-bg').classList.remove('active');
    }
    window.onload = function() {
        renderTable();
    };
</script>
@endsection 