<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'receipt_no',
        'bill_type',
        'total_amount',
        'discount',
        'less_advance',
        'paid_amount',
        'due_amount',
        'advance_amount',
        'old_due_paid',
        'payment_mode',
        'billing_date',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'less_advance' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'old_due_paid' => 'decimal:2',
        'billing_date' => 'date',
    ];

    /**
     * Get the school that owns this receipt.
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
     * Get the user who created this receipt.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
