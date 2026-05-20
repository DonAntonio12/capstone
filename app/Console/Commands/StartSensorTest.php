<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class StartSensorTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:test {duration} {--farm-id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start sensor testing with specified duration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duration = $this->argument('duration');
        $farmId = $this->option('farm-id');
        
        $this->info("Starting sensor test for {$duration} seconds...");
        
        // Path to the Python scripts
        $pythonScript = base_path('scripts/sensor_test.py');
        $testScript = base_path('scripts/test_sensor_data.py');
        
        // Check if Python script exists
        if (!file_exists($pythonScript)) {
            $this->warn("Real sensor script not found at: {$pythonScript}");
            
            // Try to use test script instead
            if (file_exists($testScript)) {
                $this->info("Using test script instead...");
                $command = "python {$testScript}";
            } else {
                $this->error("No sensor scripts found!");
                return 1;
            }
        } else {
            // Use real sensor script
            $command = "python {$pythonScript} --duration {$duration}";
        }
        
        $this->info("Executing: {$command}");
        
        try {
            $process = Process::run($command);
            
            if ($process->successful()) {
                $this->info("Sensor test completed successfully!");
                $this->info("Output: " . $process->output());
            } else {
                $this->error("Sensor test failed!");
                $this->error("Error: " . $process->errorOutput());
                Log::error("Sensor test failed", [
                    'duration' => $duration,
                    'farm_id' => $farmId,
                    'error' => $process->errorOutput()
                ]);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Exception occurred: " . $e->getMessage());
            Log::error("Sensor test exception", [
                'duration' => $duration,
                'farm_id' => $farmId,
                'exception' => $e->getMessage()
            ]);
            return 1;
        }
        
        return 0;
    }
} 