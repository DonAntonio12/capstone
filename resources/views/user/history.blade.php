@extends('layouts.user')

@section('title', 'Test History - ' . \App\Helpers\SystemHelper::getSiteName())

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
@endsection

@section('styles')
<style>
    body {
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ef 100%);
    }
    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2.5rem 1rem;
        margin-top: 2.5rem;
        min-height: calc(100vh - 90px); /* Fill viewport if few results, assuming navbar ~70-90px */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(34, 139, 34, 0.08), 0 1.5px 6px rgba(0,0,0,0.03);
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        transition: box-shadow 0.2s;
        /* Remove min-height here, let .container control vertical fill */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .card:hover {
        box-shadow: 0 8px 32px rgba(34, 139, 34, 0.13), 0 2px 8px rgba(0,0,0,0.06);
    }
    .title {
        font-size: 2.1rem;
        font-weight: 800;
        color: #228B22;
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }
    .desc {
        color: #6b7280;
        margin-bottom: 2.2rem;
        line-height: 1.7;
        font-size: 1.1rem;
    }
    .filter-section {
        display: flex;
        gap: 1.2rem;
        margin-bottom: 2.2rem;
        align-items: end;
        flex-wrap: wrap;
        background: #f3f6fa;
        border-radius: 12px;
        padding: 1.2rem 1rem 0.7rem 1rem;
        box-shadow: 0 1px 4px rgba(34,139,34,0.04);
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        min-width: 120px;
    }
    .filter-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.97rem;
        margin-bottom: 0.1rem;
    }
    .filter-input {
        padding: 0.6rem 1rem;
        border: 1.5px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        color: #374151;
        background-color: #f9fafb;
        transition: border-color 0.2s;
    }
    .filter-input:focus {
        border-color: #228B22;
        outline: none;
        box-shadow: 0 0 0 2px #d1fae5;
    }
    .form-btn {
        padding: 0.7rem 1.7rem;
        background: linear-gradient(90deg, #228B22 60%, #16a34a 100%);
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.18s, box-shadow 0.18s;
        box-shadow: 0 1px 4px rgba(34,139,34,0.08);
        margin-bottom: 0.2rem;
    }
    .form-btn:hover {
        background: linear-gradient(90deg, #16a34a 60%, #228B22 100%);
        box-shadow: 0 2px 8px rgba(34,139,34,0.13);
    }
    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(34,139,34,0.06);
        margin-bottom: 1.5rem;
    }
    .history-table th {
        background: #e9f5ee;
        color: #228B22;
        font-size: 1rem;
        font-weight: 700;
        padding: 1.1rem 0.7rem;
        text-align: left;
        border-bottom: 2px solid #d1fae5;
    }
    .history-table td {
        padding: 1rem 0.7rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 1rem;
        color: #374151;
        background: #fff;
        transition: background 0.18s;
    }
    .history-table tr:nth-child(even) td {
        background: #f7fafc;
    }
    .history-table tr:hover td {
        background: #e6f4ea;
        transition: background 0.18s;
    }
    .recommend {
        color: #16a34a;
        font-weight: 700;
    }
    .prediction {
        color: #2563eb;
        font-weight: 600;
    }
    .form-btn.delete-btn {
        background: linear-gradient(90deg, #dc2626 60%, #b91c1c 100%);
        color: #fff;
        margin-left: 0.2rem;
        font-size: 0.97rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(220,38,38,0.08);
    }
    .form-btn.delete-btn:hover {
        background: linear-gradient(90deg, #b91c1c 60%, #dc2626 100%);
    }
    .delete-btn i {
        margin-right: 0.4em;
        font-size: 1.1em;
        vertical-align: middle;
    }
    .no-results {
        text-align: center;
        color: #6b7280;
        padding: 2.5rem 0;
        font-style: italic;
        font-size: 1.1rem;
    }
    @media (max-width: 900px) {
        .container { padding: 1rem; margin-top: 1.2rem; min-height: calc(100vh - 60px); }
        .card { padding: 1.2rem; }
        .history-table th, .history-table td { padding: 0.7rem 0.4rem; font-size: 0.97rem; }
        .filter-section { flex-direction: column; align-items: stretch; gap: 0.7rem; }
        .filter-group { min-width: 100px; }
    }
    @media (max-width: 600px) {
        .container { padding: 0.5rem; margin-top: 0.7rem; min-height: calc(100vh - 40px); }
        .card { padding: 0.7rem; }
        .history-table th, .history-table td { padding: 0.5rem 0.2rem; font-size: 0.93rem; }
        .filter-section { padding: 0.7rem 0.3rem 0.3rem 0.3rem; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="title">Test History</div>
        <div class="desc">View your previous soil tests. Filter by date, soil type, or location.</div>
        <form method="get" class="filter-section" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:end;margin-bottom:1.5rem;">
            <div class="filter-group">
                <label class="filter-label" for="date_from">From</label>
                <input type="date" id="date_from" name="date_from" class="filter-input" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="filter-group">
                <label class="filter-label" for="date_to">To</label>
                <input type="date" id="date_to" name="date_to" class="filter-input" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="filter-group">
                <label class="filter-label" for="soil_type">Soil Type</label>
                <select id="soil_type" name="soil_type" class="filter-input">
                    <option value="">All</option>
                    @foreach($soilTypes as $type)
                        <option value="{{ $type }}" @if(($filters['soil_type'] ?? '') == $type) selected @endif>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label" for="location">Location</label>
                <input type="text" id="location" name="location" class="filter-input" placeholder="Search address..." value="{{ $filters['location'] ?? '' }}">
            </div>
            <button type="submit" class="form-btn">Filter</button>
        </form>
        <form id="download-form" method="get" action="{{ route('history.download') }}" style="margin-bottom:1.5rem;">
            <input type="hidden" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            <input type="hidden" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            <input type="hidden" name="soil_type" value="{{ $filters['soil_type'] ?? '' }}">
            <input type="hidden" name="location" value="{{ $filters['location'] ?? '' }}">
            <button type="submit" class="form-btn" style="background-color:#228B22;margin-bottom:0.5rem;">Download Report (PDF)</button>
        </form>
        @if(session('success'))
            <div style="background:#e6ffed;color:#166534;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">{{ session('success') }}</div>
        @endif
        @if($tests->count() > 0)
        <table class="history-table">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>N</th>
                    <th>P</th>
                    <th>K</th>
                    <th>pH</th>
                    <th>Soil Type</th>
                    <th>Recommendation</th>
                    <th>Prediction</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tests as $test)
                <tr>
                    <td>{{ $test->created_at ? $test->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                    <td>{{ $test->n }}</td>
                    <td>{{ $test->p }}</td>
                    <td>{{ $test->k }}</td>
                    <td>{{ $test->ph }}</td>
                    <td>{{ $test->soil_type }}</td>
                    <td class="recommend">{{ $test->recommendation }}</td>
                    <td class="prediction">{{ $test->prediction }}</td>
                    <td>{{ $test->address ?: ($test->latitude && $test->longitude ? $test->latitude . ', ' . $test->longitude : '-') }}</td>
                    <td style="white-space:nowrap;">
                        <form action="{{ route('history.destroy', $test->id) }}" method="POST" style="display:inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="form-btn delete-btn"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-results">No test results found.</div>
        @endif

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal-bg" style="display:none;">
    <div class="modal-card" style="max-width:350px;text-align:center;">
        <div class="modal-title">Confirm Delete</div>
        <div style="margin-bottom:1.5rem;">Are you sure you want to delete this record?</div>
        <button id="confirm-delete-btn" class="form-btn" style="background:#dc2626;margin-right:1rem;">Delete</button>
        <button id="cancel-delete-btn" class="form-btn" style="background:#888;">Cancel</button>
    </div>
</div>
@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Delete Record?',
                text: 'Are you sure you want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'swal2-confirm-btn',
                    cancelButton: 'swal2-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<style>
.swal2-border-radius { border-radius: 16px !important; }
.swal2-confirm-btn { background: #16a34a !important; border-radius: 8px !important; font-weight: 600; }
.swal2-cancel-btn { background: #dc2626 !important; border-radius: 8px !important; font-weight: 600; }
</style>
@endsection 