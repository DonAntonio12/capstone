<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoilType;

class AdminSoilTypeController extends Controller
{
    public function index()
    {
        $soilTypes = SoilType::orderBy('name')->paginate(20);
        return view('admin.soil_types.index', compact('soilTypes'));
    }

    public function create()
    {
        return view('admin.soil_types.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // Convert best_crops to array if string
        if (isset($input['best_crops']) && is_string($input['best_crops'])) {
            $input['best_crops'] = array_map('trim', explode(',', $input['best_crops']));
        }
        // Convert thresholds fields from min-max string to array
        if (isset($input['thresholds'])) {
            foreach ($input['thresholds'] as $k => $v) {
                if (is_string($v) && strpos($v, '-') !== false) {
                    $input['thresholds'][$k] = array_map('trim', explode('-', $v));
                }
            }
        }
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('soil_types', 'public');
            $input['image_url'] = 'storage/' . $path;
        }
        $validated = validator($input, [
            'name' => 'required|string|unique:soil_types,name',
            'description' => 'nullable|string',
            'thresholds' => 'nullable|array',
            'best_crops' => 'nullable|array',
            'image_url' => 'nullable|string',
        ])->validate();
        SoilType::create($validated);
        return redirect()->route('admin.soil.index')->with('success', 'Soil type added successfully.');
    }

    public function edit(SoilType $soilType)
    {
        return view('admin.soil_types.edit', compact('soilType'));
    }

    public function update(Request $request, SoilType $soilType)
    {
        $input = $request->all();
        // Convert best_crops to array if string
        if (isset($input['best_crops']) && is_string($input['best_crops'])) {
            $input['best_crops'] = array_map('trim', explode(',', $input['best_crops']));
        }
        // Convert thresholds fields from min-max string to array
        if (isset($input['thresholds'])) {
            foreach ($input['thresholds'] as $k => $v) {
                if (is_string($v) && strpos($v, '-') !== false) {
                    $input['thresholds'][$k] = array_map('trim', explode('-', $v));
                }
            }
        }
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('soil_types', 'public');
            $input['image_url'] = 'storage/' . $path;
        }
        $validated = validator($input, [
            'name' => 'required|string|unique:soil_types,name,' . $soilType->id,
            'description' => 'nullable|string',
            'thresholds' => 'nullable|array',
            'best_crops' => 'nullable|array',
            'image_url' => 'nullable|string',
        ])->validate();
        $soilType->update($validated);
        return redirect()->route('admin.soil.index')->with('success', 'Soil type updated successfully.');
    }

    public function destroy(SoilType $soilType)
    {
        $soilType->delete();
        return redirect()->route('admin.soil.index')->with('success', 'Soil type deleted successfully.');
    }
} 