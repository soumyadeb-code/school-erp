<?php

namespace App\Http\Controllers;

use App\Models\BillLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BillLayoutController extends Controller
{
    /**
     * Show the bill layout designer page.
     */
    public function designer(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $billType = $request->get('bill_type', 'admission');
        
        // Get existing layouts for this school and bill type
        $layouts = BillLayout::getLayouts($schoolId, $billType);
        
        // If no layouts exist, get defaults
        $defaultLayouts = BillLayout::getDefaultAdmissionLayout();
        
        // Merge defaults with saved layouts
        $variables = [];
        $allVariables = array_keys($defaultLayouts);
        $variableLabels = BillLayout::getVariableLabels();
        
        foreach ($allVariables as $var) {
            $variables[$var] = [
                'label' => $variableLabels[$var] ?? ucfirst(str_replace('_', ' ', $var)),
                'x' => $layouts[$var]->x_position ?? $defaultLayouts[$var]['x'],
                'y' => $layouts[$var]->y_position ?? $defaultLayouts[$var]['y'],
                'font_size' => $layouts[$var]->font_size ?? $defaultLayouts[$var]['font_size'],
            ];
        }
        
        return view('school-admin.bill-layouts.designer', compact('billType', 'variables'));
    }

    /**
     * Save bill layout coordinates.
     */
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $billType = $request->bill_type;
        
        // Handle JSON variables from drag-drop interface
        if ($request->has('variables_json') && $request->variables_json) {
            $variables = json_decode($request->variables_json, true);
            
            foreach ($variables as $variableName => $data) {
                if (isset($data['placed']) && $data['placed']) {
                    BillLayout::updateOrCreate(
                        [
                            'school_id' => $schoolId,
                            'bill_type' => $billType,
                            'variable_name' => $variableName
                        ],
                        [
                            'x_position' => $data['x'] ?? 0,
                            'y_position' => $data['y'] ?? 0,
                            'font_size' => $data['fontSize'] ?? 12,
                            'is_active' => true
                        ]
                    );
                }
            }
            
            return redirect()->route('school-admin.bill-layouts.designer', ['bill_type' => $billType])
                ->with('success', 'Bill layout saved successfully!');
        }
        
        // Handle regular form variables
        $validator = Validator::make($request->all(), [
            'bill_type' => 'required|string|in:admission,registration,monthly',
            'variables' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $variables = $request->variables;

        // Save each variable's position
        foreach ($variables as $variableName => $data) {
            BillLayout::updateOrCreate(
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

        return redirect()->route('school-admin.bill-layouts.designer', ['bill_type' => $billType])
            ->with('success', 'Bill layout saved successfully!');
    }

    /**
     * Upload PDF template.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_type' => 'required|string|in:admission,registration,monthly',
            'pdf_template' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $billType = $request->bill_type;
        
        // Save the PDF template
        $file = $request->file('pdf_template');
        $filename = $billType . '_template.pdf';
        $destinationPath = public_path('pdf-templates');
        
        // Create directory if not exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        
        // Delete old template if exists
        $oldPath = $destinationPath . '/' . $filename;
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }
        
        // Move uploaded file
        $file->move($destinationPath, $filename);

        return redirect()->route('school-admin.bill-layouts.designer', ['bill_type' => $billType])
            ->with('success', 'PDF template uploaded successfully! Now drag and drop fields onto the PDF.');
    }

    /**
     * Reset bill layout to default.
     */
    public function reset(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $billType = $request->get('bill_type', 'admission');
        
        // Delete existing layouts for this bill type
        BillLayout::where('school_id', $schoolId)
            ->where('bill_type', $billType)
            ->delete();
        
        return redirect()->route('school-admin.bill-layouts.designer', ['bill_type' => $billType])
            ->with('success', 'Bill layout reset to defaults!');
    }

    /**
     * Get variable label for display.
     */
    public static function getVariableLabel($variableName)
    {
        $labels = [
            'receipt_no' => 'Receipt No',
            'student_id' => 'Student ID',
            'student_name' => 'Student Name',
            'class' => 'Class',
            'fee_amount' => 'Fee Amount',
            'discount' => 'Discount',
            'advance' => 'Advance',
            'old_due' => 'Old Due',
            'new_due' => 'New Due',
            'payment_mode' => 'Payment Mode',
            'total_amount' => 'Total Amount',
            'amount_paid' => 'Amount Paid',
            'billing_date' => 'Billing Date',
            'fee_type' => 'Fee Type',
            'academic_year' => 'Academic Year',
        ];
        
        return $labels[$variableName] ?? ucfirst(str_replace('_', ' ', $variableName));
    }

    /**
     * API: Get layout positions for PDF generation.
     */
    public function getLayouts(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $billType = $request->get('bill_type', 'admission');
        
        $layouts = BillLayout::getLayouts($schoolId, $billType);
        
        // If no layouts found, return defaults
        if ($layouts->isEmpty()) {
            return response()->json(BillLayout::getDefaultAdmissionLayout());
        }
        
        $result = [];
        foreach ($layouts as $var => $layout) {
            $result[$var] = [
                'x' => $layout->x_position,
                'y' => $layout->y_position,
                'font_size' => $layout->font_size
            ];
        }
        
        return response()->json($result);
    }
}
