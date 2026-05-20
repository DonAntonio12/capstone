<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    protected $fillable = [
        'site_name',
        'logo_url',
        'contact_email',
        'contact_phone',
        'maintenance_mode',
        'default_user_role',
        'about',
        'custom_settings',
    ];
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'custom_settings' => 'array',
    ];
} 