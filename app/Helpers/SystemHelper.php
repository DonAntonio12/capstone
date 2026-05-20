<?php

namespace App\Helpers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SystemHelper
{
    public static function getSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            return SystemSetting::first();
        });
    }

    public static function getSiteName()
    {
        $settings = self::getSettings();
        return $settings ? $settings->site_name : 'SoilSense';
    }

    public static function getLogoUrl()
    {
        $settings = self::getSettings();
        return $settings ? $settings->logo_url : null;
    }

    public static function getContactEmail()
    {
        $settings = self::getSettings();
        return $settings ? $settings->contact_email : null;
    }

    public static function getContactPhone()
    {
        $settings = self::getSettings();
        return $settings ? $settings->contact_phone : null;
    }

    public static function getAbout()
    {
        $settings = self::getSettings();
        return $settings ? $settings->about : null;
    }

    public static function clearCache()
    {
        Cache::forget('system_settings');
    }
} 