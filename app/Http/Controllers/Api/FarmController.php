<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Auth::user()->farms()->with(['sensorReadings', 'predictions'])->get();
        return response()->json(['farms' => $farms]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'area' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm = Auth::user()->farms()->create($request->all());
        return response()->json(['farm' => $farm], 201);
    }

    public function show(Farm $farm)
    {
        $this->authorize('view', $farm);
        return response()->json(['farm' => $farm->load(['sensorReadings', 'predictions'])]);
    }

    public function update(Request $request, Farm $farm)
    {
        $this->authorize('update', $farm);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'area' => 'sometimes|required|numeric|min:0',
            'location' => 'sometimes|required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm->update($request->all());
        return response()->json(['farm' => $farm]);
    }

    public function destroy(Farm $farm)
    {
        $this->authorize('delete', $farm);
        $farm->delete();
        return response()->json(null, 204);
    }
} 