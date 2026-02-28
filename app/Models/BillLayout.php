<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'bill_type',
        'variable_name',
        'x_position',
        'y_position',
        'font_size',
        'is_active',
    ];

    /**
     * Get the school that owns the bill layout.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all layouts for a specific school and bill type.
     */
    public static function getLayouts($schoolId, $billType)
    {
        return self::where('school_id', $schoolId)
            ->where('bill_type', $billType)
            ->where('is_active', true)
            ->get()
            ->keyBy('variable_name');
    }

    /**
     * Get a specific variable position.
     */
    public static function getPosition($schoolId, $billType, $variableName)
    {
        $layout = self::where('school_id', $schoolId)
            ->where('bill_type', $billType)
            ->where('variable_name', $variableName)
            ->where('is_active', true)
            ->first();

        return $layout ? [
            'x' => $layout->x_position,
            'y' => $layout->y_position,
            'font_size' => $layout->font_size
        ] : null;
    }

    /**
     * Save or update multiple layouts at once.
     */
    public static function saveLayouts($schoolId, $billType, $layouts)
    {
        foreach ($layouts as $variableName => $data) {
            self::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'bill_type' => $billType,
                    'variable_name' => $variableName
                ],
                [
                    'x_position' => $data['x'] ?? 0,
                    'y_position' => $data['y'] ?? 0,
                    'font_size' => $data['font_size'] ?? 12,
                    'is_active' => true
                ]
            );
        }
    }

    /**
     * Default layout for admission bill.
     */
    public static function getDefaultAdmissionLayout()
    {
        return [
            'receipt_no' => ['x' => 400, 'y' => 700, 'font_size' => 12],
            'student_id' => ['x' => 150, 'y' => 650, 'font_size' => 12],
            'student_name' => ['x' => 150, 'y' => 620, 'font_size' => 12],
            'class' => ['x' => 150, 'y' => 590, 'font_size' => 12],
            'fee_amount' => ['x' => 150, 'y' => 560, 'font_size' => 12],
            'discount' => ['x' => 300, 'y' => 560, 'font_size' => 12],
            'advance' => ['x' => 450, 'y' => 560, 'font_size' => 12],
            'old_due' => ['x' => 150, 'y' => 530, 'font_size' => 12],
            'new_due' => ['x' => 300, 'y' => 530, 'font_size' => 12],
            'payment_mode' => ['x' => 450, 'y' => 530, 'font_size' => 12],
            'total_amount' => ['x' => 150, 'y' => 500, 'font_size' => 12],
            'amount_paid' => ['x' => 300, 'y' => 500, 'font_size' => 12],
            'billing_date' => ['x' => 450, 'y' => 500, 'font_size' => 12],
            'fee_type' => ['x' => 150, 'y' => 470, 'font_size' => 12],
            'academic_year' => ['x' => 300, 'y' => 470, 'font_size' => 12],
        ];
    }
}
