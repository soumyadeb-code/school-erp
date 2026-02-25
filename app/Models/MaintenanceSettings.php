<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSettings extends Model
{
    protected $fillable = [
        'page_title',
        'school_title',
        'maintenance_message',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active maintenance settings
     */
    public static function getActiveSettings()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceEnabled()
    {
        return self::where('is_active', true)->exists();
    }
}
