<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BooksetPrice extends Model
{
    use HasFactory;

protected $fillable = [
        'school_id',
        'academic_year_id',
        'class_id',
        'medium',
        'book_price',
        'notebook_price',
        'total_price',
    ];

    protected $casts = [
        'book_price' => 'decimal:2',
        'notebook_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the school that owns this bookset price.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the academic year.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the class.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
