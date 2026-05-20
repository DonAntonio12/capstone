<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    public function predict(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8000/predict', [
            'nitrogen' => $request->input('nitrogen'),
            'phosphorus' => $request->input('phosphorus'),
            'potassium' => $request->input('potassium'),
            'ph' => $request->input('ph'),
            'organic_carbon' => $request->input('organic_carbon'),
            'crop' => $request->input('crop'), // optional
        ]);
        $result = $response->json();
        return view('prediction.result', compact('result'));
    }
} 