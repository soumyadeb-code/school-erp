<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'academic_year_id',
        'total_advance',
    ];

    protected $casts = [
        'total_advance' => 'decimal:2',
    ];

    /**
     * Get the school that owns this advance.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the academic year.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
