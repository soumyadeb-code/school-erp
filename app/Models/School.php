<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'password',
        'joining_date',
        'expiry_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Check if school subscription is expired.
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expiry_date);
    }

    /**
     * Check if school is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    /**
     * Get the super admin who created this school.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all users (admins) for this school.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all classes for this school.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Get all academic years for this school.
     */
    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    /**
     * Get active academic year.
     */
    public function activeAcademicYear(): HasOne
    {
        return $this->hasOne(AcademicYear::class)->where('is_active', true);
    }

    /**
     * Get all students for this school.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all receipts for this school.
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}
