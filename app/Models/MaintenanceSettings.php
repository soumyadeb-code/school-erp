<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSettings extends Model
{
    protected $fillable = [
        'school_id',
        'page_title',
        'school_title',
        'maintenance_message',
        'email',
        'phone',
        'is_active',
        'is_global',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_global' => 'boolean',
    ];

    /**
     * Get the school that owns the maintenance settings.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get global maintenance settings (for whole system)
     */
    public static function getGlobalSettings()
    {
        return self::where('is_global', true)->first();
    }

    /**
     * Get maintenance settings for a specific school
     */
    public static function getSchoolSettings($schoolId)
    {
        return self::where('school_id', $schoolId)->first();
    }

    /**
     * Get current active maintenance settings (checks global first, then school-specific)
     */
    public static function getActiveSettings($schoolId = null)
    {
        // Check global maintenance first
        $global = self::where('is_global', true)->where('is_active', true)->first();
        if ($global) {
            return $global;
        }

        // Then check school-specific maintenance
        if ($schoolId) {
            return self::where('school_id', $schoolId)->where('is_active', true)->first();
        }

        return null;
    }

    /**
     * Check if maintenance mode is enabled (global or school-specific)
     */
    public static function isMaintenanceEnabled($schoolId = null)
    {
        // Check global first
        if (self::where('is_global', true)->where('is_active', true)->exists()) {
            return true;
        }

        // Then check school-specific
        if ($schoolId) {
            return self::where('school_id', $schoolId)->where('is_active', true)->exists();
        }

        return false;
    }

    /**
     * Scope to get only global settings
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Scope to get only school-specific settings
     */
    public function scopeSchoolSpecific($query)
    {
        return $query->where('is_global', false);
    }
}
