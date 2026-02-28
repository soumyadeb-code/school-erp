<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_enrollment_id',
        'to_enrollment_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'from_class_id',
        'to_class_id',
        'promotion_date',
        'remarks',
    ];

    protected $casts = [
        'promotion_date' => 'date',
    ];

    /**
     * Get the student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the from enrollment.
     */
    public function fromEnrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'from_enrollment_id');
    }

    /**
     * Get the to enrollment.
     */
    public function toEnrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'to_enrollment_id');
    }

    /**
     * Get the from academic year.
     */
    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    /**
     * Get the to academic year.
     */
    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    /**
     * Get the from class.
     */
    public function fromClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    /**
     * Get the to class.
     */
    public function toClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }
}
