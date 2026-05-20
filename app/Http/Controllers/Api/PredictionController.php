<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\Farm;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_id' => 'required|exists:farms,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm = Farm::findOrFail($request->farm_id);
        $this->authorize('view', $farm);

        $query = $farm->predictions();

        if ($request->start_date) {
            $query->where('prediction_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('prediction_date', '<=', $request->end_date);
        }

        $predictions = $query->orderBy('prediction_date', 'desc')->get();
        return response()->json(['predictions' => $predictions]);
    }

    public function generatePrediction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_id' => 'required|exists:farms,id',
            'collection_session_id' => 'required|string',
            'prediction_for_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm = Farm::findOrFail($request->farm_id);
        $this->authorize('update', $farm);

        // Get the sensor readings for this session
        $readings = $farm->sensorReadings()
            ->where('collection_session_id', $request->collection_session_id)
            ->orderBy('reading_time', 'asc')
            ->get();

        if ($readings->isEmpty()) {
            return response()->json(['error' => 'No sensor readings found for this session'], 404);
        }

        // TODO: Implement ANN model prediction here
        // For now, we'll use a simple average as placeholder
        $avgNitrogen = $readings->avg('nitrogen');
        $avgPhosphorus = $readings->avg('phosphorus');
        $avgPotassium = $readings->avg('potassium');

        // Create prediction record
        $prediction = $farm->predictions()->create([
            'user_id' => Auth::id(),
            'collection_session_id' => $request->collection_session_id,
            'predicted_nitrogen' => $avgNitrogen,
            'predicted_phosphorus' => $avgPhosphorus,
            'predicted_potassium' => $avgPotassium,
            'prediction_date' => now(),
            'prediction_for_date' => $request->prediction_for_date,
            'confidence_score' => 0.85, // Placeholder
            'model_parameters' => [
                'model_version' => '1.0',
                'input_features' => ['nitrogen', 'phosphorus', 'potassium', 'soil_temperature', 'soil_moisture'],
                'prediction_type' => 'short_term'
            ],
            'recommendations' => $this->generateRecommendations($avgNitrogen, $avgPhosphorus, $avgPotassium)
        ]);

        return response()->json(['prediction' => $prediction], 201);
    }

    public function show(Prediction $prediction)
    {
        $this->authorize('view', $prediction->farm);
        return response()->json(['prediction' => $prediction->load('sensorReadings')]);
    }

    private function generateRecommendations($nitrogen, $phosphorus, $potassium)
    {
        // TODO: Implement more sophisticated recommendation logic
        $recommendations = [];

        // Example recommendation logic
        if ($nitrogen < 40) {
            $recommendations[] = "Consider applying nitrogen-rich fertilizer";
        }
        if ($phosphorus < 20) {
            $recommendations[] = "Soil phosphorus levels are low. Consider phosphate fertilizer application";
        }
        if ($potassium < 150) {
            $recommendations[] = "Potassium levels are below optimal. Consider potash application";
        }

        if (empty($recommendations)) {
            $recommendations[] = "Current nutrient levels are within optimal range. Continue regular monitoring.";
        }

        return implode("\n", $recommendations);
    }
} 