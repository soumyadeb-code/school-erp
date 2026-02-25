<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'destination',
        'price',
        'status',
    ];

    /**
     * Get the school that owns this bus fee.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all students using this bus route.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'bus_destination_id');
    }
}
