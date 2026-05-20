<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\Farm;
use App\Models\User;
use App\Models\SoilType;

class AdminSoilDataController extends Controller
{
    // List all soil data
    public function index()
    {
        $soilData = SensorReading::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.soil.index', compact('soilData'));
    }

    // Show create form
    public function create()
    {
        $farms = Farm::all();
        $users = User::all();
        return view('admin.soil.create', compact('farms', 'users'));
    }

    // Store new soil data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'n' => 'required|numeric',
            'p' => 'required|numeric',
            'k' => 'required|numeric',
            'ph' => 'required|numeric',
            'soil_type' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'readings_count' => 'nullable|integer',
        ]);
        SensorReading::create($validated);
        return redirect()->route('admin.soil.index')->with('success', 'Soil data added successfully.');
    }

    // Show edit form
    public function edit(SensorReading $sensorReading)
    {
        $farms = Farm::all();
        $users = User::all();
        return view('admin.soil.edit', compact('sensorReading', 'farms', 'users'));
    }

    // Update soil data
    public function update(Request $request, SensorReading $sensorReading)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nitrogen' => 'required|numeric',
            'phosphorus' => 'required|numeric',
            'potassium' => 'required|numeric',
            'soil_temperature' => 'nullable|numeric',
            'soil_moisture' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'reading_time' => 'required|date',
            'collection_duration' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        $sensorReading->update($validated);
        return redirect()->route('admin.soil.index')->with('success', 'Soil data updated successfully.');
    }

    // Delete soil data
    public function destroy(SensorReading $sensorReading)
    {
        $sensorReading->delete();
        return redirect()->route('admin.soil.index')->with('success', 'Soil data deleted successfully.');
    }

    // Soil Data Records Management (CRUD)
    public function recordsIndex(Request $request)
    {
        $query = SensorReading::query();
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);
        $records = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::orderBy('name')->get();
        $soilTypes = SoilType::orderBy('name')->get();
        return view('admin.soil.records.index', compact('records', 'users', 'soilTypes'));
    }

    public function recordsCreate()
    {
        $users = User::orderBy('name')->get();
        $soilTypes = SoilType::orderBy('name')->get();
        $farms = Farm::orderBy('name')->get();
        return view('admin.soil.records.create', compact('users', 'soilTypes', 'farms'));
    }

    public function recordsStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'soil_type_id' => 'nullable|exists:soil_types,id',
            'nitrogen' => 'required|numeric',
            'phosphorus' => 'required|numeric',
            'potassium' => 'required|numeric',
            'soil_temperature' => 'nullable|numeric',
            'soil_moisture' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'reading_time' => 'required|date',
            'collection_duration' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        SensorReading::create($validated);
        return redirect()->route('admin.soil_data_records.index')->with('success', 'Soil data record added.');
    }

    public function recordsEdit(SensorReading $sensorReading)
    {
        $users = User::orderBy('name')->get();
        $soilTypes = SoilType::orderBy('name')->get();
        $farms = Farm::orderBy('name')->get();
        return view('admin.soil.records.edit', compact('sensorReading', 'users', 'soilTypes', 'farms'));
    }

    public function recordsUpdate(Request $request, SensorReading $sensorReading)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'soil_type_id' => 'nullable|exists:soil_types,id',
            'nitrogen' => 'required|numeric',
            'phosphorus' => 'required|numeric',
            'potassium' => 'required|numeric',
            'soil_temperature' => 'nullable|numeric',
            'soil_moisture' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'reading_time' => 'required|date',
            'collection_duration' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        $sensorReading->update($validated);
        return redirect()->route('admin.soil_data_records.index')->with('success', 'Soil data record updated.');
    }

    public function recordsDestroy(SensorReading $sensorReading)
    {
        $sensorReading->delete();
        return redirect()->route('admin.soil_data_records.index')->with('success', 'Soil data record deleted.');
    }

    // List all users for soil data viewing
    public function userList()
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.soil.users.index', compact('users'));
    }

    // Show all soil data for a specific user
    public function userSoilData(\App\Models\User $user)
    {
        $soilData = \App\Models\SensorReading::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.soil.users.show', compact('user', 'soilData'));
    }
} 