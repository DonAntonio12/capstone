<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmController;
use App\Http\Controllers\Api\SensorReadingController;
use App\Http\Controllers\Api\SensorTestController;
use App\Http\Controllers\Api\PredictionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Test route to check if API is working
Route::get('/test', [SensorTestController::class, 'test']);

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Farm routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/farms', [FarmController::class, 'index']);
    Route::post('/farms', [FarmController::class, 'store']);
    Route::get('/farms/{farm}', [FarmController::class, 'show']);
    Route::put('/farms/{farm}', [FarmController::class, 'update']);
    Route::delete('/farms/{farm}', [FarmController::class, 'destroy']);
});

// Sensor test routes
Route::prefix('sensor-test')->group(function () {
    Route::post('/start', [SensorTestController::class, 'startTest']);
    Route::get('/status', [SensorTestController::class, 'getTestStatus']);
});

// Sensor reading routes
Route::prefix('sensor-readings')->group(function () {
    // Store new sensor reading (from Python script)
    Route::post('/', [SensorReadingController::class, 'store']);
    
    // Get latest sensor reading
    Route::get('/latest', [SensorReadingController::class, 'getLatest']);
    
    // Get sensor readings by farm
    Route::get('/farm', [SensorReadingController::class, 'getByFarm']);
    
    // Get test results with analysis
    Route::get('/test-results', [SensorReadingController::class, 'getTestResults']);
});

// Prediction routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/predictions', [PredictionController::class, 'index']);
    Route::post('/predictions', [PredictionController::class, 'store']);
    Route::get('/predictions/{prediction}', [PredictionController::class, 'show']);
}); 