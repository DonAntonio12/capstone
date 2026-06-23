@extends('admin.layout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<style>
    .soil-form-header {
        text-align: center;
        font-size: 2.1rem;
        font-weight: 800;
        margin-bottom: 2.2rem;
        color: #228B22;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        letter-spacing: 0.01em;
    }
    .soil-form-card {
        background: rgba(255,255,255,0.92);
        padding: 2.2rem 2rem;
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(34,139,34,0.10);
        margin: 0 auto;
        border: 1.5px solid #e6f9ec;
        backdrop-filter: blur(4px);
        max-width: 650px;
    }
    .soil-form-label {
        font-weight: 600;
        color: #228B22;
        margin-bottom: 0.3rem;
        display: block;
    }
    .soil-form-input, .soil-form-select, .soil-form-textarea {
        width: 100%;
        padding: 0.7rem;
        border-radius: 8px;
        border: 1.5px solid #e6f9ec;
        margin-bottom: 1.2rem;
        font-size: 1.08rem;
        background: #f6fdf7;
        transition: border 0.16s;
    }
    .soil-form-input:focus, .soil-form-select:focus, .soil-form-textarea:focus {
        border: 1.5px solid #228B22;
        outline: none;
        background: #fff;
    }
    .soil-form-btn {
        background: linear-gradient(90deg, #228B22 80%, #a7f3d0 100%);
        color: #fff;
        font-weight: 700;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-size: 1.08rem;
        border: none;
        box-shadow: 0 2px 8px rgba(34,139,34,0.08);
        transition: background 0.18s, color 0.18s, transform 0.16s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .soil-form-btn:hover {
        background: linear-gradient(90deg, #166534 80%, #6ee7b7 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
    }
    .soil-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.2rem;
    }
    @media (max-width: 700px) {
        .soil-form-row { grid-template-columns: 1fr; }
    }
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #e6f9ec;
        color: #228B22;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: background 0.16s, color 0.16s;
        box-shadow: 0 2px 8px rgba(34,139,34,0.06);
        text-decoration: none;
    }
    .back-btn:hover {
        background: #a7f3d0;
        color: #166534;
    }
</style>
<a href="{{ route('admin.soil.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Soil Data</a>
<div class="soil-form-header"><i class="fas fa-plus-circle" style="margin-right:0.5rem;"></i>Add Soil Data</div>
@if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">
        <ul style="margin:0;padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form id="soil-create-form" method="POST" action="{{ route('admin.soil.store') }}" class="soil-form-card">
    @csrf
    <div class="soil-form-row">
        <div>
            <label class="soil-form-label">User</label>
            <select name="user_id" class="soil-form-select" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="soil-form-label">Nitrogen (N)</label>
            <input type="number" step="0.01" name="nitrogen" class="soil-form-input" required />
        </div>
        <div>
            <label class="soil-form-label">Phosphorus (P)</label>
            <input type="number" step="0.01" name="phosphorus" class="soil-form-input" required />
        </div>
        <div>
            <label class="soil-form-label">Potassium (K)</label>
            <input type="number" step="0.01" name="potassium" class="soil-form-input" required />
        </div>
        <div>
            <label class="soil-form-label">Soil Temperature (°C)</label>
            <input type="number" step="0.01" name="soil_temperature" class="soil-form-input" />
        </div>
        <div>
            <label class="soil-form-label">Soil Moisture (%)</label>
            <input type="number" step="0.01" name="soil_moisture" class="soil-form-input" />
        </div>
        <div>
            <label class="soil-form-label">Latitude</label>
            <input type="number" step="0.000001" name="latitude" class="soil-form-input" />
        </div>
        <div>
            <label class="soil-form-label">Longitude</label>
            <input type="number" step="0.000001" name="longitude" class="soil-form-input" />
        </div>
        <div>
            <label class="soil-form-label">Reading Time</label>
            <input type="datetime-local" name="reading_time" class="soil-form-input" required />
        </div>
        <div>
            <label class="soil-form-label">Collection Duration (seconds)</label>
            <input type="number" name="collection_duration" class="soil-form-input" />
        </div>
        <div style="grid-column:1/3;">
            <label class="soil-form-label">Notes</label>
            <textarea name="notes" rows="2" class="soil-form-textarea"></textarea>
        </div>
    </div>
    <div style="margin-top:2rem;text-align:right;">
        <button type="submit" class="soil-form-btn"><i class="fas fa-save"></i> Save</button>
        <a href="{{ route('admin.soil.index') }}" style="margin-left:1.2rem;color:#888;text-decoration:underline;">Cancel</a>
    </div>
</form>
<script>
    document.getElementById('soil-create-form').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Add Soil Data?',
            text: 'Are you sure you want to add this soil data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#228B22',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add',
            background: '#f6fdf7',
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
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
    @elseif($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#228B22',
            background: '#f6fdf7',
        });
    @endif
</script>
@endsection 