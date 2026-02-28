<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'class_id',
        'roll',
        'section',
        'status',
        'promoted_to_enrollment_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

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

    /**
     * Get the class.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the enrollment this student was promoted to.
     */
    public function promotedToEnrollment(): HasOne
    {
        return $this->hasOne(StudentEnrollment::class, 'promoted_to_enrollment_id');
    }

    /**
     * Get the enrollment this student was promoted from.
     */
    public function promotedFromEnrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'promoted_to_enrollment_id');
    }

    /**
     * Scope to get current year enrollment for a student.
     */
    public function scopeCurrentYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Scope to get students by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
