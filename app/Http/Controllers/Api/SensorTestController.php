<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Farm;

class SensorTestController extends Controller
{
    /**
     * Start a sensor test with specified duration
     */
    public function startTest(Request $request): JsonResponse
    {
        // DEBUG LOG: Log the incoming request
        \Log::info('SensorTestController@startTest called', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->validate([
            'duration' => 'required|integer|min:20|max:20', // exactly 20 seconds
            'location' => 'nullable|array',
            'location.lat' => 'nullable|numeric|between:-90,90',
            'location.lng' => 'nullable|numeric|between:-180,180',
            'location.address' => 'nullable|string|max:255',
        ]);

        try {
            $duration = $request->input('duration');
            $location = $request->input('location');

            Log::info('Starting sensor test', [
                'duration' => $duration,
                'location' => $location,
            ]);

            // Check if Python script exists
            $pythonScript = base_path('scripts/sensor_test.py');
            if (!file_exists($pythonScript)) {
                Log::error('Python script not found, cannot start real sensor test');
                return response()->json([
                    'success' => false,
                    'message' => 'Python script for real sensor data not found. Please check your server setup.',
                    'data_source' => 'none'
                ], 500);
            }

            // Store location data in session for later use
            if ($location) {
                session(['test_location' => $location]);
            }

            // Run the Laravel command to start the Python script
            $exitCode = Artisan::call('sensor:test', [
                'duration' => $duration
            ]);

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sensor test started successfully',
                    'data' => [
                        'duration' => $duration,
                        'estimated_completion' => now()->addSeconds($duration)->toISOString(),
                        'data_source' => 'real',
                        'location' => $location
                    ]
                ]);
            } else {
                Log::error('Sensor test command failed', [
                    'exit_code' => $exitCode,
                    'duration' => $duration,
                    'location' => $location,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to start sensor test',
                    'error' => 'Command execution failed'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Sensor test start error', [
                'error' => $e->getMessage(),
                'duration' => $request->input('duration'),
                'location' => $request->input('location'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to start sensor test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get test status
     */
    public function getTestStatus(): JsonResponse
    {
        try {
            // Check if there are recent test results
            $latestTest = \App\Models\SensorReading::whereNotNull('soil_type')
                ->whereNotNull('recommendations')
                ->latest()
                ->first();

            if (!$latestTest) {
                return response()->json([
                    'success' => false,
                    'message' => 'No test results found'
                ], 404);
            }

            $timeSinceTest = now()->diffInSeconds($latestTest->created_at);

            return response()->json([
                'success' => true,
                'data' => [
                    'last_test_time' => $latestTest->created_at->toISOString(),
                    'seconds_ago' => $timeSinceTest,
                    'has_results' => true,
                    'readings_count' => $latestTest->readings_count
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get test status error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get test status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint to check if API is working
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'API is working correctly',
            'timestamp' => now()->toISOString()
        ]);
    }
} 