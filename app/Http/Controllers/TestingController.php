<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\SoilTest;
use App\Models\SensorReading;

class TestingController extends Controller
{
    // Show the testing page
    public function index()
    {
        return view('user.testing');
    }

    // Return the latest real sensor data (from SensorReading, not simulated)
    public function latest()
    {
        // Get the latest sensor reading (assume real values from ESP32 via Python script)
        $latest = SensorReading::latest()->first();
        if (!$latest) {
            return response()->json(['success' => false, 'message' => 'No sensor data found.']);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'n' => $latest->n,
                'p' => $latest->p,
                'k' => $latest->k,
                'ph' => $latest->ph,
                'timestamp' => $latest->created_at,
                'latitude' => optional(json_decode($latest->location_data, true))['lat'] ?? null,
                'longitude' => optional(json_decode($latest->location_data, true))['lng'] ?? null,
            ]
        ]);
    }

    // Start the Python sensor test script (auto-run)
    public function start(Request $request)
    {
        $data = $request->validate([
            'duration' => 'required|integer|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);
        $duration = $data['duration'];
        $lat = $data['latitude'] ?? null;
        $lng = $data['longitude'] ?? null;
        $address = $data['address'] ?? null;

        $python = 'C:\\Users\\Kenneth\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
        $script = base_path('scripts/sensor_test.py');
        $cmd = [$python, $script, '--duration', $duration];
        
        \Log::info('TestingController@start: Running command (Symfony)', ['cmd' => $cmd]);
        try {
            $process = new Process($cmd);
            $process->setTimeout($duration + 20); // add buffer
            $process->start();
            // Optionally, you can use $process->wait() if you want to block
            \Log::info('TestingController@start: Process started (Symfony)', [
                'pid' => method_exists($process, 'getPid') ? $process->getPid() : null
            ]);
            return response()->json(['success' => true, 'message' => 'Sensor test started.']);
        } catch (ProcessFailedException $e) {
            \Log::error('TestingController@start: ProcessFailedException', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to start sensor test', 'error' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            \Log::error('TestingController@start: Throwable', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to start sensor test', 'error' => $e->getMessage()], 500);
        }
    }

    // Save the final averaged result to the database
    public function save(Request $request)
    {
        $data = $request->validate([
            'n' => 'required|numeric',
            'p' => 'required|numeric',
            'k' => 'required|numeric',
            'ph' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'soil_type' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'prediction' => 'nullable|string',
            'ideal_n' => 'nullable|numeric',
            'ideal_p' => 'nullable|numeric',
            'ideal_k' => 'nullable|numeric',
            'ideal_ph' => 'nullable|numeric',
        ]);
        $data['user_id'] = Auth::id() ?? 1; // fallback to 1 if not logged in
        $soilTest = SoilTest::create($data);
        return response()->json(['success' => true, 'message' => 'Test result saved!', 'id' => $soilTest->id]);
    }

    public function collect(Request $request)
    {
        $data = $request->validate([
            'duration' => 'required|integer|min:20|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);
        $duration = $data['duration'];
        $lat = $data['latitude'] ?? null;
        $lng = $data['longitude'] ?? null;
        $address = $data['address'] ?? null;

        $python = 'C:\\Users\\Kenneth\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
        $script = base_path('scripts/sensor_test.py');
        $cmd = [$python, $script, '--duration', $duration, '--web'];
        \Log::info('TestingController@collect: Running command (sync)', ['cmd' => $cmd]);
        try {
            $process = new \Symfony\Component\Process\Process($cmd);
            // Allow 30 seconds for the test (20 seconds + 10 seconds buffer)
            $process->setTimeout(30);
            $process->run(); // synchronous, wait for finish
            $output = $process->getOutput();
            $error = $process->getErrorOutput();
            \Log::info('TestingController@collect: Process finished', [
                'output' => $output,
                'error' => $error,
                'exit_code' => $process->getExitCode(),
                'duration' => $duration,
                'actual_time' => '~20 seconds total'
            ]);
            
            if ($process->getExitCode() !== 0) {
                \Log::error('TestingController@collect: Process failed', [
                    'exit_code' => $process->getExitCode(),
                    'error' => $error,
                    'output' => $output
                ]);
                return response()->json(['success' => false, 'message' => 'Test script error', 'error' => $error], 500);
            }
            
            // Parse JSON output
            $lines = explode("\n", trim($output));
            $jsonLine = end($lines);
            $readings = json_decode($jsonLine, true);
            
            if (!$readings) {
                \Log::error('TestingController@collect: Invalid JSON output', [
                    'output' => $output,
                    'json_line' => $jsonLine
                ]);
                return response()->json(['success' => false, 'message' => 'No valid sensor data returned', 'raw' => $output], 500);
            }
            
            // Handle readings from ESP32
            if (isset($readings['readings']) && is_array($readings['readings'])) {
                $validReadings = [];

                foreach ($readings['readings'] as $reading) {
                    $n  = array_key_exists('n', $reading)  ? $reading['n']  : null;
                    $p  = array_key_exists('p', $reading)  ? $reading['p']  : null;
                    $k  = array_key_exists('k', $reading)  ? $reading['k']  : null;
                    $ph = array_key_exists('ph', $reading) ? $reading['ph'] : null;

                    // Skip entries that have no NPK or pH data at all
                    if ($n === null && $p === null && $k === null && $ph === null) {
                        continue;
                    }

                    // Preserve real zero values; only provide defaults for completely missing (null) values
                    $validReadings[] = [
                        'n' => $n !== null ? $n : 0.0,
                        'p' => $p !== null ? $p : 0.0,
                        'k' => $k !== null ? $k : 0.0,
                        'ph' => $ph !== null ? $ph : 0.0,
                        'temperature' => $reading['temperature'] ?? 25.0,
                        'humidity' => $reading['humidity'] ?? 60.0,
                    ];
                }

                // If after processing wala talagang kahit isang reading, mag-error
                if (count($validReadings) === 0) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'No sensor data detected. Please ensure NPK sensors are properly inserted into the soil and connected to the ESP32.',
                        'error' => 'Sensors not in contact with soil',
                        'suggestion' => 'Check that NPK sensors are fully inserted into moist soil and properly connected to the ESP32 device.'
                    ], 400);
                }               

                $readings['readings'] = $validReadings;
            }
            
            \Log::info('TestingController@collect: Success', [
                'readings_count' => count($readings['readings'] ?? []),
                'sample_reading' => $readings['readings'][0] ?? 'none'
            ]);
            
            return response()->json(['success' => true, 'data' => $readings]);
        } catch (\Throwable $e) {
            \Log::error('TestingController@collect: Throwable', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to collect sensor data', 'error' => $e->getMessage()], 500);
        }
    }

    public function history(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $query = \App\Models\SoilTest::where('user_id', $userId);
        // Filtering
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->filled('soil_type')) {
            $query->where('soil_type', $request->input('soil_type'));
        }
        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->input('location') . '%');
        }
        $tests = $query->orderBy('created_at', 'desc')->get();
        $soilTypes = \App\Models\SoilTest::where('user_id', $userId)->distinct()->pluck('soil_type')->filter()->unique()->values();
        return view('user.history', [
            'tests' => $tests,
            'soilTypes' => $soilTypes,
            'filters' => [
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'soil_type' => $request->input('soil_type'),
                'location' => $request->input('location'),
            ]
        ]);
    }

    public function downloadHistory(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $query = \App\Models\SoilTest::where('user_id', $userId);
        
        // Filtering (same as history)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->filled('soil_type')) {
            $query->where('soil_type', $request->input('soil_type'));
        }
        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->input('location') . '%');
        }
        $tests = $query->orderBy('created_at', 'desc')->get();

        // Generate PDF using DomPDF directly
        $dompdf = new \Dompdf\Dompdf();
        $html = view('reports.soil-test-history', [
            'tests' => $tests,
            'filters' => [
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'soil_type' => $request->input('soil_type'),
                'location' => $request->input('location'),
            ],
            'siteName' => \App\Helpers\SystemHelper::getSiteName(),
            'generatedAt' => now()->format('Y-m-d H:i:s'),
            'totalTests' => $tests->count()
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="soil_test_history.pdf"'
        ]);
    }

    // Delete a test record
    public function destroy($id)
    {
        $test = \App\Models\SoilTest::where('user_id', Auth::id() ?? 1)->findOrFail($id);
        $test->delete();
        return redirect('/history')->with('success', 'Test record deleted!');
    }
}
