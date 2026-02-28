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
     * Supports two columns: Original (left) and Duplicate (right)
     */
    public static function getDefaultAdmissionLayout()
    {
        return [
            // Original Copy - Left Side (x: 20-280)
            'receipt_no' => ['x' => 400, 'y' => 800, 'font_size' => 12],
            'student_roll' => ['x' => 50, 'y' => 750, 'font_size' => 11],
            'student_name' => ['x' => 50, 'y' => 720, 'font_size' => 12],
            'student_class' => ['x' => 50, 'y' => 690, 'font_size' => 11],
            'student_section' => ['x' => 180, 'y' => 690, 'font_size' => 11],
            'student_medium' => ['x' => 50, 'y' => 660, 'font_size' => 11],
            'purpose_for' => ['x' => 50, 'y' => 630, 'font_size' => 11],
            'mode_title' => ['x' => 50, 'y' => 600, 'font_size' => 11],
            'donate_amount' => ['x' => 50, 'y' => 570, 'font_size' => 11],
            'conveyance_fees' => ['x' => 50, 'y' => 540, 'font_size' => 11],
            'subtotal' => ['x' => 50, 'y' => 510, 'font_size' => 11],
            'less_amount' => ['x' => 50, 'y' => 480, 'font_size' => 11],
            'less_advance' => ['x' => 150, 'y' => 480, 'font_size' => 11],
            'due_paid' => ['x' => 50, 'y' => 450, 'font_size' => 11],
            'total_amount' => ['x' => 50, 'y' => 420, 'font_size' => 12],
            'advance_amount' => ['x' => 150, 'y' => 420, 'font_size' => 11],
            'due_amount' => ['x' => 250, 'y' => 420, 'font_size' => 11],
            'paid_by' => ['x' => 50, 'y' => 390, 'font_size' => 11],
            'received_by' => ['x' => 150, 'y' => 390, 'font_size' => 11],
            'date' => ['x' => 250, 'y' => 390, 'font_size' => 11],
            
            // Duplicate Copy - Right Side (x: 320-580)
            'receipt_no_dup' => ['x' => 450, 'y' => 800, 'font_size' => 12],
            'student_roll_dup' => ['x' => 350, 'y' => 750, 'font_size' => 11],
            'student_name_dup' => ['x' => 350, 'y' => 720, 'font_size' => 12],
            'student_class_dup' => ['x' => 350, 'y' => 690, 'font_size' => 11],
            'student_section_dup' => ['x' => 480, 'y' => 690, 'font_size' => 11],
            'student_medium_dup' => ['x' => 350, 'y' => 660, 'font_size' => 11],
            'purpose_for_dup' => ['x' => 350, 'y' => 630, 'font_size' => 11],
            'mode_title_dup' => ['x' => 350, 'y' => 600, 'font_size' => 11],
            'donate_amount_dup' => ['x' => 350, 'y' => 570, 'font_size' => 11],
            'conveyance_fees_dup' => ['x' => 350, 'y' => 540, 'font_size' => 11],
            'subtotal_dup' => ['x' => 350, 'y' => 510, 'font_size' => 11],
            'less_amount_dup' => ['x' => 350, 'y' => 480, 'font_size' => 11],
            'less_advance_dup' => ['x' => 450, 'y' => 480, 'font_size' => 11],
            'due_paid_dup' => ['x' => 350, 'y' => 450, 'font_size' => 11],
            'total_amount_dup' => ['x' => 350, 'y' => 420, 'font_size' => 12],
            'advance_amount_dup' => ['x' => 450, 'y' => 420, 'font_size' => 11],
            'due_amount_dup' => ['x' => 550, 'y' => 420, 'font_size' => 11],
            'paid_by_dup' => ['x' => 350, 'y' => 390, 'font_size' => 11],
            'received_by_dup' => ['x' => 450, 'y' => 390, 'font_size' => 11],
            'date_dup' => ['x' => 550, 'y' => 390, 'font_size' => 11],
        ];
    }

    /**
     * Get all variable labels for the designer.
     */
    public static function getVariableLabels()
    {
        return [
            // Original Copy labels
            'receipt_no' => 'Receipt No',
            'student_roll' => 'Roll No',
            'student_name' => 'Student Name',
            'student_class' => 'Class',
            'student_section' => 'Section',
            'student_medium' => 'Medium',
            'purpose_for' => 'Purpose For',
            'mode_title' => 'Mode Title',
            'donate_amount' => 'Donate Amount',
            'conveyance_fees' => 'Conveyance Fees',
            'subtotal' => 'Subtotal',
            'less_amount' => 'Less Amount',
            'less_advance' => 'Less Advance',
            'due_paid' => 'Due Paid',
            'total_amount' => 'Total Amount',
            'advance_amount' => 'Advance Amount',
            'due_amount' => 'Due Amount',
            'paid_by' => 'Paid By',
            'received_by' => 'Received By',
            'date' => 'Date',
            
            // Duplicate Copy labels
            'receipt_no_dup' => '[Dup] Receipt No',
            'student_roll_dup' => '[Dup] Roll No',
            'student_name_dup' => '[Dup] Student Name',
            'student_class_dup' => '[Dup] Class',
            'student_section_dup' => '[Dup] Section',
            'student_medium_dup' => '[Dup] Medium',
            'purpose_for_dup' => '[Dup] Purpose For',
            'mode_title_dup' => '[Dup] Mode Title',
            'donate_amount_dup' => '[Dup] Donate Amount',
            'conveyance_fees_dup' => '[Dup] Conveyance Fees',
            'subtotal_dup' => '[Dup] Subtotal',
            'less_amount_dup' => '[Dup] Less Amount',
            'less_advance_dup' => '[Dup] Less Advance',
            'due_paid_dup' => '[Dup] Due Paid',
            'total_amount_dup' => '[Dup] Total Amount',
            'advance_amount_dup' => '[Dup] Advance Amount',
            'due_amount_dup' => '[Dup] Due Amount',
            'paid_by_dup' => '[Dup] Paid By',
            'received_by_dup' => '[Dup] Received By',
            'date_dup' => '[Dup] Date',
        ];
    }
}
