<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'academic_year_id',
        'month',
        'tuition_fee',
        'bus_fee',
        'total_fee',
        'discount',
        'paid_amount',
        'receipt_no',
        'receipt_id',
        'payment_date',
        'status',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'bus_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the school that owns this payment.
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

    /**
     * Get the receipt.
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Get month name.
     */
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }
}
