<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use App\Helpers\SystemHelper;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        return view('admin.settings', [
            'settings' => $settings ? $settings->toArray() : []
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:255',
            'maintenance_mode' => 'required|boolean',
            'default_user_role' => 'required|string',
            'about' => 'nullable|string',
            'custom_settings' => 'nullable|string',
        ]);

        $settings = SystemSetting::first() ?? new SystemSetting();
        $settings->fill($validated);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = 'system_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('public/logos', $filename);
            $settings->logo_url = Storage::url($path);
        } elseif ($request->input('remove_logo')) {
            $settings->logo_url = null;
        }

        // Handle custom_settings as JSON
        if (isset($validated['custom_settings'])) {
            $settings->custom_settings = json_decode($validated['custom_settings'], true);
        }

        $settings->save();
        
        // Clear cache para ma-refresh ang settings
        SystemHelper::clearCache();
        
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }
} 