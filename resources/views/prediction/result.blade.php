@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Prediction Results</h2>
    @if(isset($result['soil']))
        <h4>Soil Prediction</h4>
        <b>Type:</b> {{ $result['soil']['soil_type'] }}<br>
        <b>Texture:</b> {{ $result['soil']['texture'] }}<br>
        <b>Ideal NPK & pH:</b>
        N: {{ $result['soil']['ideal_npk_ph']['N'] }},
        P: {{ $result['soil']['ideal_npk_ph']['P'] }},
        K: {{ $result['soil']['ideal_npk_ph']['K'] }},
        pH: {{ $result['soil']['ideal_npk_ph']['pH'] }}<br>
        <b>Recommended Crops:</b> {{ $result['soil']['recommended_crops'] }}<br>
    @endif

    @if(isset($result['fertilizer']))
        <h4>Fertilizer Recommendation</h4>
        <b>Recommended Fertilizer:</b> {{ $result['fertilizer']['recommended_fertilizer'] }}<br>
        <b>Deficiency:</b>
        N: {{ $result['fertilizer']['deficiency']['N'] }},
        P: {{ $result['fertilizer']['deficiency']['P'] }},
        K: {{ $result['fertilizer']['deficiency']['K'] }}<br>
        <b>Recommended Amounts (kg/ha):</b>
        <ul>
            @foreach($result['fertilizer']['recommended_amounts'] as $fert => $amt)
                <li>{{ $fert }}: {{ $amt }}</li>
            @endforeach
        </ul>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection 