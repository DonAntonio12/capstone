<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SensorReadingController extends Controller
{
    /**
     * Store a new sensor reading
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'n' => 'required|numeric|min:0|max:1000',
            'p' => 'required|numeric|min:0|max:1000',
            'k' => 'required|numeric|min:0|max:1000',
            'ph' => 'required|numeric|min:0|max:14',
            'soil_type' => 'nullable|string|max:255',
            'recommendations' => 'nullable|string',
            'readings_count' => 'nullable|integer|min:1',
            'timestamp' => 'nullable|date',
            'location' => 'nullable|array',
            'location.lat' => 'nullable|numeric|between:-90,90',
            'location.lng' => 'nullable|numeric|between:-180,180',
            'location.address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sensorReading = SensorReading::create([
                'n' => $request->n,
                'p' => $request->p,
                'k' => $request->k,
                'ph' => $request->ph,
                'soil_type' => $request->soil_type,
                'recommendations' => $request->recommendations,
                'readings_count' => $request->readings_count,
                'location_data' => $request->location ? json_encode($request->location) : null,
                'created_at' => $request->timestamp ? $request->timestamp : now(),
            ]);

            Log::info('Sensor reading stored', [
                'id' => $sensorReading->id,
                'readings_count' => $request->readings_count,
                'location' => $request->location
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sensor reading stored successfully',
                'data' => $sensorReading
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to store sensor reading', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to store sensor reading',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest sensor readings
     */
    public function getLatest(): JsonResponse
    {
        try {
            $latestReading = SensorReading::latest()->first();

            if (!$latestReading) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sensor readings found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $latestReading
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get latest sensor reading', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get latest sensor reading',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sensor readings for a specific farm
     */
    public function getByFarm(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $limit = $request->get('limit', 10);
            
            $readings = SensorReading::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $readings
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get sensor readings by farm', [
                'error' => $e->getMessage(),
                'farm_id' => $request->farm_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get sensor readings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get test results with analysis
     */
    public function getTestResults(): JsonResponse
    {
        try {
            $latestReading = SensorReading::whereNotNull('soil_type')
                ->whereNotNull('recommendations')
                ->latest()
                ->first();

            if (!$latestReading) {
                return response()->json([
                    'success' => false,
                    'message' => 'No test results found'
                ], 404);
            }

            // Get location data
            $locationData = null;
            if ($latestReading->location_data) {
                $locationData = json_decode($latestReading->location_data, true);
            }

            // Generate prediction based on soil type and NPK values
            $prediction = $this->generatePrediction($latestReading->soil_type, $latestReading->n, $latestReading->p, $latestReading->k, $latestReading->ph);

            // Format the response for the frontend
            $result = [
                'id' => $latestReading->id,
                'timestamp' => $latestReading->created_at->format('Y-m-d H:i:s'),
                'readings_count' => $latestReading->readings_count,
                'sensor_data' => [
                    'n' => round($latestReading->n, 2),
                    'p' => round($latestReading->p, 2),
                    'k' => round($latestReading->k, 2),
                    'ph' => round($latestReading->ph, 2),
                ],
                'analysis' => [
                    'soil_type' => $latestReading->soil_type,
                    'recommendations' => $latestReading->recommendations,
                    'prediction' => $prediction
                ],
                'location' => $locationData
            ];

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get test results', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get test results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate prediction based on soil analysis
     */
    private function generatePrediction(string $soilType, float $n, float $p, float $k, float $ph): string
    {
        // Simple prediction logic based on soil type and nutrient levels
        if (str_contains($soilType, 'High Fertility')) {
            return 'Excellent crop yield expected with optimal growth conditions';
        } elseif (str_contains($soilType, 'Moderate Fertility')) {
            if ($n >= 50 && $p >= 30 && $k >= 150 && $ph >= 6.0 && $ph <= 7.0) {
                return 'Good crop yield expected with proper fertilization';
            } else {
                return 'Moderate crop yield expected, follow recommendations for improvement';
            }
        } elseif (str_contains($soilType, 'Low Fertility')) {
            return 'Low crop yield expected, immediate soil improvement needed';
        } else {
            // Neutral soil types
            if ($n >= 100 && $p >= 50 && $k >= 200 && $ph >= 6.0 && $ph <= 7.0) {
                return 'Optimal growth conditions, excellent yield potential';
            } elseif ($n >= 50 && $p >= 30 && $k >= 150) {
                return 'Good growth conditions with proper management';
            } else {
                return 'Moderate growth expected, soil improvement recommended';
            }
        }
    }
} 