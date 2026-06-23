@extends('admin.layout')

@section('content')
<h2 style="font-size:2rem;font-weight:700;color:#222;margin-bottom:2rem;">Edit Soil Data Record</h2>
@if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">
        <ul style="margin:0;padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('admin.soil_data_records.update', $sensorReading->id) }}" style="background:#fff;padding:2rem 1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.06);max-width:700px;">
    @csrf
    @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;">
        <div>
            <label>User</label>
            <select name="user_id" required style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($sensorReading->user_id == $user->id) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Soil Type</label>
            <select name="soil_type_id" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;">
                <option value="">Select Soil Type</option>
                @foreach($soilTypes as $type)
                    <option value="{{ $type->id }}" @if($sensorReading->soil_type_id == $type->id) selected @endif>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Nitrogen (N)</label>
            <input type="number" step="0.01" name="nitrogen" value="{{ $sensorReading->nitrogen }}" required style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Phosphorus (P)</label>
            <input type="number" step="0.01" name="phosphorus" value="{{ $sensorReading->phosphorus }}" required style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Potassium (K)</label>
            <input type="number" step="0.01" name="potassium" value="{{ $sensorReading->potassium }}" required style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>pH Level</label>
            <input type="number" step="0.01" name="ph_level" value="{{ $sensorReading->ph_level }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Soil Temperature (°C)</label>
            <input type="number" step="0.01" name="soil_temperature" value="{{ $sensorReading->soil_temperature }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Soil Moisture (%)</label>
            <input type="number" step="0.01" name="soil_moisture" value="{{ $sensorReading->soil_moisture }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Latitude</label>
            <input type="number" step="0.000001" name="latitude" value="{{ $sensorReading->latitude }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Longitude</label>
            <input type="number" step="0.000001" name="longitude" value="{{ $sensorReading->longitude }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Reading Time</label>
            <input type="datetime-local" name="reading_time" value="{{ $sensorReading->reading_time ? $sensorReading->reading_time->format('Y-m-d\TH:i') : '' }}" required style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div>
            <label>Collection Duration (seconds)</label>
            <input type="number" name="collection_duration" value="{{ $sensorReading->collection_duration }}" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;" />
        </div>
        <div style="grid-column:1/3;">
            <label>Notes</label>
            <textarea name="notes" rows="2" style="width:100%;padding:0.6rem 0.7rem;border-radius:7px;">{{ $sensorReading->notes }}</textarea>
        </div>
    </div>
    <div style="margin-top:2rem;text-align:right;">
        <button type="submit" style="background:#FFD600;color:#222;font-weight:700;border:none;border-radius:7px;padding:0.9rem 2.2rem;font-size:1.1rem;cursor:pointer;">Update</button>
        <a href="{{ route('admin.soil_data_records.index') }}" style="margin-left:1.2rem;color:#888;text-decoration:underline;">Cancel</a>
    </div>
</form>
@endsection 