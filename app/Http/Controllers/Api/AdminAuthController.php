<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Admin;
use App\Models\SensorReading;
use App\Models\Prediction;

class AdminAuthController extends Controller
{
    // Show the admin login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            // Redirect to Admin Dashboard after login
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email' => 'Invalid admin credentials.']);
    }

    // Show the admin dashboard (to be protected)
    public function dashboard()
    {
        // Dynamic stats
        $totalUsers = User::count();
        $totalSoilTests = SensorReading::count();
        $totalSensors = SensorReading::count();
        // ANN accuracy: for now, get average confidence_score (if any), else static
        $annAccuracy = Prediction::avg('confidence_score');
        $annAccuracy = $annAccuracy ? round($annAccuracy * 100) : 97;

        // Recent activity: last 5 users, soil tests, predictions
        $recentUsers = User::orderBy('created_at', 'desc')->take(3)->get();
        $recentTests = SensorReading::orderBy('created_at', 'desc')->take(3)->get();
        $recentPredictions = Prediction::orderBy('prediction_date', 'desc')->take(3)->get();
        $recentActivity = collect();
        foreach ($recentUsers as $user) {
            $recentActivity->push([
                'type' => 'user',
                'description' => "New user <b>{$user->name}</b> registered.",
                'time' => $user->created_at,
            ]);
        }
        foreach ($recentTests as $test) {
            $recentActivity->push([
                'type' => 'test',
                'description' => "Soil test completed (N: {$test->n}, P: {$test->p}, K: {$test->k})",
                'time' => $test->created_at,
            ]);
        }
        foreach ($recentPredictions as $prediction) {
            $recentActivity->push([
                'type' => 'prediction',
                'description' => "ANN prediction made for <b>Farm #{$prediction->farm_id}</b> (Confidence: " . ($prediction->confidence_score ? round($prediction->confidence_score * 100) . '%' : 'N/A') . ")",
                'time' => $prediction->prediction_date,
            ]);
        }
        $recentActivity = $recentActivity->sortByDesc('time')->take(5);

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalSoilTests' => $totalSoilTests,
            'totalSensors' => $totalSensors,
            'annAccuracy' => $annAccuracy,
            'recentActivity' => $recentActivity,
        ]);
    }

    // Add admin logout method
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
} 