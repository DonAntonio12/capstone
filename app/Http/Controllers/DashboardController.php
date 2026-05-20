<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\SensorReading;
use App\Models\Prediction;
use App\Models\SoilType;
use App\Models\SoilTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's farms
        $farms = Farm::where('user_id', $user->id)->get();
        
        // Get total farms count
        $totalFarms = $farms->count();
        
        // Get recent readings from all farms
        $recentReadings = SensorReading::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get recent soil tests for the logged-in user
        $recentUserTests = SoilTest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get active predictions
        $activePredictions = Prediction::whereIn('farm_id', $farms->pluck('id'))
            ->where('prediction_for_date', '>=', now())
            ->count();
            
        // Combine readings and predictions for recent activity
        $recentActivity = collect();
        
        // Add readings to activity
        foreach ($recentReadings as $reading) {
            $recentActivity->push([
                'type' => 'reading',
                'description' => sprintf(
                    'NPK Levels - N: %s, P: %s, K: %s',
                    $reading->n,
                    $reading->p,
                    $reading->k
                ),
                'time' => $reading->created_at->format('M d, Y H:i')
            ]);
        }
        
        // Add predictions to activity
        $predictions = Prediction::whereIn('farm_id', $farms->pluck('id'))
            ->latest('prediction_date')
            ->take(5)
            ->get();
            
        foreach ($predictions as $prediction) {
            $recentActivity->push([
                'type' => 'prediction',
                'description' => sprintf(
                    'Predicted NPK Levels for %s',
                    $prediction->prediction_for_date->format('M d, Y')
                ),
                'time' => $prediction->prediction_date->format('M d, Y H:i')
            ]);
        }
        
        // Sort activities by time and take the 5 most recent
        $recentActivity = $recentActivity->sortByDesc('time')->take(5);
        $soilTypes = SoilType::orderBy('name')->get();
        return view('dashboard', [
            'totalFarms' => $totalFarms,
            'recentReadings' => $recentReadings->count(),
            'activePredictions' => $activePredictions,
            'recentActivity' => $recentActivity,
            'soilTypes' => $soilTypes,
            'recentUserTests' => $recentUserTests
        ]);
    }
} 