<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'discount_type',
        'same_month_discount',
        'next_month_discount',
        'valid_till_day',
    ];

    protected $casts = [
        'same_month_discount' => 'decimal:2',
        'next_month_discount' => 'decimal:2',
    ];

    /**
     * Get the school that owns this discount rule.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Calculate discount based on payment date.
     */
    public function calculateDiscount($billingDate, $dueMonth)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $billingMonth = $billingDate->month;
        $billingYear = $billingDate->year;

        // Same month payment
        if ($billingYear == $dueMonth['year'] && $billingMonth == $dueMonth['month']) {
            return $this->same_month_discount;
        }

        // Next month before valid day
        $nextMonth = $dueMonth['month'] == 12 ? 1 : $dueMonth['month'] + 1;
        $nextMonthYear = $dueMonth['month'] == 12 ? $dueMonth['year'] + 1 : $dueMonth['year'];

        if ($billingYear == $nextMonthYear && $billingMonth == $nextMonth && $billingDate->day <= $this->valid_till_day) {
            return $this->next_month_discount;
        }

        return 0;
    }
}
