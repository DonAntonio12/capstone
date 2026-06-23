@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Soil & Fertilizer Prediction</h2>
    <form method="POST" action="{{ route('predict') }}">
        @csrf
        <div class="mb-3">
            <label>Nitrogen (%)</label>
            <input type="number" step="0.01" name="nitrogen" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phosphorus (ppm)</label>
            <input type="number" step="0.1" name="phosphorus" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Potassium (ppm)</label>
            <input type="number" step="0.1" name="potassium" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>pH</label>
            <input type="number" step="0.01" name="ph" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Organic Carbon (%)</label>
            <input type="number" step="0.01" name="organic_carbon" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Crop (optional, for fertilizer recommendation)</label>
            <input type="text" name="crop" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Predict</button>
    </form>
</div>
@endsection 