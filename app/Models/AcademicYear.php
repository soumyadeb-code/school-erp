<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'year',
        'is_active',
        'is_locked',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the school that owns this academic year.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all admission fees for this academic year.
     */
    public function admissionFees(): HasMany
    {
        return $this->hasMany(AdmissionFee::class);
    }

    /**
     * Get all registration fees for this academic year.
     */
    public function registrationFees(): HasMany
    {
        return $this->hasMany(RegistrationFee::class);
    }

    /**
     * Get all class fees for this academic year.
     */
    public function classFees(): HasMany
    {
        return $this->hasMany(ClassFee::class);
    }

    /**
     * Get all bookset prices for this academic year.
     */
    public function booksetPrices(): HasMany
    {
        return $this->hasMany(BooksetPrice::class);
    }
}
