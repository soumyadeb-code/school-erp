<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'school_id',
        'class_name',
        'minimum_age',
        'status',
        'next_class_id',
    ];

    /**
     * Get the school that owns this class.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all students in this class.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get class fees for this class.
     */
    public function classFees(): HasMany
    {
        return $this->hasMany(ClassFee::class);
    }

    /**
     * Get bookset prices for this class.
     */
    public function booksetPrices(): HasMany
    {
        return $this->hasMany(BooksetPrice::class);
    }

    /**
     * Get the next class for promotion.
     */
    public function nextClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'next_class_id');
    }
}
