<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\AdmissionFee;
use App\Models\RegistrationFee;
use App\Models\ClassFee;
use App\Models\BusFee;
use App\Models\BooksetPrice;
use App\Models\DiscountRule;
use App\Models\Student;
use App\Models\StudentDue;
use App\Models\StudentAdvance;
use App\Models\Receipt;
use App\Models\MonthlyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SchoolAdminController extends Controller
{
    /**
     * School Admin Dashboard.
     */
    public function dashboard()
    {
        $schoolId = auth()->user()->school_id;
        
        // Get current academic year
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        // Student statistics
        $totalStudents = Student::where('school_id', $schoolId)->count();
        $activeStudents = Student::where('school_id', $schoolId)
            ->where('status', 'active')->count();
        $tcStudents = Student::where('school_id', $schoolId)
            ->where('status', 'tc_issued')->count();
        
        // Financial statistics
        $totalIncome = Receipt::where('school_id', $schoolId)
            ->where('status', 'active')
            ->sum('paid_amount');
        
        $monthlyCollection = Receipt::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereMonth('billing_date', date('m'))
            ->whereYear('billing_date', date('Y'))
            ->sum('paid_amount');
        
        // Due students count
        $dueStudents = StudentDue::where('school_id', $schoolId)
            ->where('total_due', '>', 0)
            ->count();
        
        // Advance students count
        $advanceStudentsCount = StudentAdvance::where('school_id', $schoolId)
            ->where('total_advance', '>', 0)
            ->count();
        
        // Recent receipts
        $recentReceipts = Receipt::where('school_id', $schoolId)
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get();
        
        // Monthly income for chart (last 6 months)
        $monthlyChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyChartData[] = [
                'month' => $month->format('M'),
                'amount' => Receipt::where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->whereMonth('billing_date', $month->format('m'))
                    ->whereYear('billing_date', $month->format('Y'))
                    ->sum('paid_amount')
            ];
        }
        
        // Recent students
        $recentStudents = Student::where('school_id', $schoolId)
            ->latest()
            ->take(10)
            ->get();

        return view('school-admin.dashboard', compact(
            'currentYear',
            'totalStudents',
            'activeStudents',
            'tcStudents',
            'totalIncome',
            'monthlyCollection',
            'dueStudents',
            'advanceStudentsCount',
            'recentStudents',
            'monthlyChartData'
        ));
    }

    // ==================== CLASSES MANAGEMENT ====================
    
    /**
     * List all classes.
     */
    public function classes()
    {
        $schoolId = auth()->user()->school_id;
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        return view('school-admin.classes.index', compact('classes'));
    }

    /**
     * Store new class.
     */
    public function storeClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'minimum_age' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SchoolClass::create([
            'school_id' => auth()->user()->school_id,
            'class_name' => $request->class_name,
            'minimum_age' => $request->minimum_age,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Class created successfully.');
    }

    /**
     * Update class.
     */
    public function updateClass(Request $request, SchoolClass $class)
    {
        $this->authorizeSchool($class);
        
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'minimum_age' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $class->update([
            'class_name' => $request->class_name,
            'minimum_age' => $request->minimum_age,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Class updated successfully.');
    }

    /**
     * Edit class form.
     */
    public function editClass(SchoolClass $class)
    {
        $this->authorizeSchool($class);
        return view('school-admin.classes.edit', compact('class'));
    }

    /**
     * Delete class.
     */
    public function destroyClass(SchoolClass $class)
    {
        $this->authorizeSchool($class);
        $class->delete();
        
        return redirect()->back()->with('success', 'Class deleted successfully.');
    }

    // ==================== ACADEMIC YEARS MANAGEMENT ====================
    
    /**
     * List academic years.
     */
    public function academicYears()
    {
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        return view('school-admin.academic-years.index', compact('years'));
    }

    /**
     * Store new academic year.
     */
    public function storeAcademicYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|unique:academic_years,year,NULL,id,school_id,' . auth()->user()->school_id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as active, deactivate others
        if ($request->is_active) {
            AcademicYear::where('school_id', auth()->user()->school_id)
                ->update(['is_active' => false]);
        }

        AcademicYear::create([
            'school_id' => auth()->user()->school_id,
            'year' => $request->year,
            'is_active' => $request->is_active ?? false,
            'is_locked' => false,
        ]);

        return redirect()->back()->with('success', 'Academic year created successfully.');
    }

    /**
     * Toggle year lock status.
     */
    public function toggleYearLock(AcademicYear $year)
    {
        $this->authorizeSchool($year);
        
        $year->update(['is_locked' => !$year->is_locked]);
        
        return redirect()->back()->with('success', 'Academic year lock status updated.');
    }

    /**
     * Activate academic year.
     */
    public function activateAcademicYear(AcademicYear $year)
    {
        $this->authorizeSchool($year);
        
        // Deactivate all other years
        AcademicYear::where('school_id', auth()->user()->school_id)
            ->update(['is_active' => false]);
        
        // Activate the selected year
        $year->update(['is_active' => true]);
        
        return redirect()->back()->with('success', 'Academic year activated successfully.');
    }

    /**
     * Delete academic year.
     */
    public function destroyAcademicYear(AcademicYear $year)
    {
        $this->authorizeSchool($year);
        
        $year->delete();
        
        return redirect()->back()->with('success', 'Academic year deleted successfully.');
    }

    // ==================== ADMISSION FEES ====================
    
    /**
     * Admission fees page.
     */
    public function admissionFees()
    {
        $schoolId = auth()->user()->school_id;
        $academicYears = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        $admissionFees = AdmissionFee::where('school_id', $schoolId)
            ->with('academicYear')
            ->orderBy('academic_year_id', 'desc')
            ->get();
        
        return view('school-admin.fees.admission', compact('academicYears', 'admissionFees'));
    }

    /**
     * Store admission fee.
     */
    public function storeAdmissionFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'medium' => 'required|in:Bengali,English,Hindi',
            'amount' => 'required|numeric|min:0',
            'admission_start_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate
        $exists = AdmissionFee::where('school_id', auth()->user()->school_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('medium', $request->medium)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'This fee configuration already exists.')
                ->withInput();
        }

        AdmissionFee::create([
            'school_id' => auth()->user()->school_id,
            'academic_year_id' => $request->academic_year_id,
            'medium' => $request->medium,
            'amount' => $request->amount,
            'admission_start_date' => $request->admission_start_date,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Admission fee created successfully.');
    }

    /**
     * Edit admission fee form.
     */
    public function editAdmissionFee(AdmissionFee $admissionFee)
    {
        $this->authorizeSchool($admissionFee);
        
        $schoolId = auth()->user()->school_id;
        $academicYears = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        
        return view('school-admin.fees.admission-edit', compact('admissionFee', 'academicYears'));
    }

    /**
     * Update admission fee.
     */
    public function updateAdmissionFee(Request $request, AdmissionFee $admissionFee)
    {
        $this->authorizeSchool($admissionFee);
        
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'admission_start_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admissionFee->update([
            'amount' => $request->amount,
            'admission_start_date' => $request->admission_start_date,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Admission fee updated successfully.');
    }

    /**
     * Delete admission fee.
     */
    public function destroyAdmissionFee(AdmissionFee $admissionFee)
    {
        $this->authorizeSchool($admissionFee);
        $admissionFee->delete();
        
        return redirect()->back()->with('success', 'Admission fee deleted successfully.');
    }

    // ==================== REGISTRATION FEES ====================
    
    /**
     * Registration fees page.
     */
    public function registrationFees()
    {
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        $fees = RegistrationFee::where('school_id', $schoolId)
            ->with('academicYear')
            ->orderBy('academic_year_id', 'desc')
            ->get();
        
        return view('school-admin.fees.registration-fees', compact('years', 'fees'));
    }

    /**
     * Store registration fee.
     */
    public function storeRegistrationFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'medium' => 'required|in:Bengali,English,Hindi',
            'amount' => 'required|numeric|min:0',
            'registration_start_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $exists = RegistrationFee::where('school_id', auth()->user()->school_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('medium', $request->medium)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'This fee configuration already exists.')
                ->withInput();
        }

        RegistrationFee::create([
            'school_id' => auth()->user()->school_id,
            'academic_year_id' => $request->academic_year_id,
            'medium' => $request->medium,
            'amount' => $request->amount,
            'registration_start_date' => $request->registration_start_date,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Registration fee created successfully.');
    }

    /**
     * Edit registration fee form.
     */
    public function editRegistrationFee(RegistrationFee $registrationFee)
    {
        $this->authorizeSchool($registrationFee);
        
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        
        return view('school-admin.fees.registration-fees-edit', compact('registrationFee', 'years'));
    }

    /**
     * Update registration fee.
     */
    public function updateRegistrationFee(Request $request, RegistrationFee $registrationFee)
    {
        $this->authorizeSchool($registrationFee);
        
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'registration_start_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registrationFee->update([
            'amount' => $request->amount,
            'registration_start_date' => $request->registration_start_date,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Registration fee updated successfully.');
    }

    /**
     * Delete registration fee.
     */
    public function destroyRegistrationFee(RegistrationFee $registrationFee)
    {
        $this->authorizeSchool($registrationFee);
        $registrationFee->delete();
        
        return redirect()->back()->with('success', 'Registration fee deleted successfully.');
    }

    // ==================== CLASS FEES ====================
    
    /**
     * Class fees page.
     */
    public function classFees()
    {
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->where('status', 'active')->get();
        $fees = ClassFee::where('school_id', $schoolId)
            ->with(['academicYear', 'schoolClass'])
            ->orderBy('academic_year_id', 'desc')
            ->get();
        
        return view('school-admin.fees.class-fees', compact('years', 'classes', 'fees'));
    }

    /**
     * Store class fee.
     */
    public function storeClassFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'medium' => 'required|in:Bengali,English,Hindi',
            'tuition_fee' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $exists = ClassFee::where('school_id', auth()->user()->school_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('class_id', $request->class_id)
            ->where('medium', $request->medium)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Fee for this class, medium and year already exists.')
                ->withInput();
        }

        ClassFee::create([
            'school_id' => auth()->user()->school_id,
            'academic_year_id' => $request->academic_year_id,
            'class_id' => $request->class_id,
            'medium' => $request->medium,
            'tuition_fee' => $request->tuition_fee,
        ]);

        return redirect()->back()->with('success', 'Class fee created successfully.');
    }

    /**
     * Edit class fee form.
     */
    public function editClassFee(ClassFee $classFee)
    {
        $this->authorizeSchool($classFee);
        
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->where('status', 'active')->get();
        
        return view('school-admin.fees.class-fees-edit', compact('classFee', 'years', 'classes'));
    }

    /**
     * Update class fee.
     */
    public function updateClassFee(Request $request, ClassFee $classFee)
    {
        $this->authorizeSchool($classFee);
        
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'medium' => 'required|in:Bengali,English,Hindi',
            'tuition_fee' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $classFee->update([
            'academic_year_id' => $request->academic_year_id,
            'class_id' => $request->class_id,
            'medium' => $request->medium,
            'tuition_fee' => $request->tuition_fee,
        ]);

        return redirect()->back()->with('success', 'Class fee updated successfully.');
    }

    /**
     * Delete class fee.
     */
    public function destroyClassFee(ClassFee $classFee)
    {
        $this->authorizeSchool($classFee);
        $classFee->delete();
        
        return redirect()->back()->with('success', 'Class fee deleted successfully.');
    }

    // ==================== BUS FEES ====================
    
/**
     * Bus fees page - loads view for AJAX
     */
    public function busFees()
    {
        return view('school-admin.fees.bus-fees');
    }

    /**
     * Search bus fees (AJAX).
     */
    public function searchBusFees(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $query = BusFee::where('school_id', $schoolId);
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('destination', 'like', '%' . $search . '%')
                  ->orWhere('price', 'like', '%' . $search . '%');
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $fees = $query->orderBy('id', 'desc')->paginate(10);
        
        return response()->json($fees);
    }

    /**
     * Store bus fee.
     */
    public function storeBusFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        BusFee::create([
            'school_id' => auth()->user()->school_id,
            'destination' => $request->destination,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Bus fee created successfully.');
    }

    /**
     * Edit bus fee form.
     */
    public function editBusFee(BusFee $busFee)
    {
        $this->authorizeSchool($busFee);
        return view('school-admin.fees.bus-fees-edit', compact('busFee'));
    }

    /**
     * Update bus fee.
     */
    public function updateBusFee(Request $request, BusFee $busFee)
    {
        $this->authorizeSchool($busFee);
        
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $busFee->update([
            'destination' => $request->destination,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Bus fee updated successfully.');
    }

    /**
     * Delete bus fee.
     */
    public function destroyBusFee(BusFee $busFee)
    {
        $this->authorizeSchool($busFee);
        $busFee->delete();
        
        return redirect()->back()->with('success', 'Bus fee deleted successfully.');
    }

    /**
     * Import bus fees from Excel (update if exists, create if new).
     */
    public function importBusFees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $imported = 0;
            $updated = 0;
            $schoolId = auth()->user()->school_id;
            
            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    // Check if destination already exists for this school
                    $existingFee = BusFee::where('school_id', $schoolId)
                        ->where('destination', $row[0])
                        ->first();
                    
                    if ($existingFee) {
                        // Update existing record
                        $existingFee->update([
                            'price' => $row[1],
                        ]);
                        $updated++;
                    } else {
                        // Create new record
                        BusFee::create([
                            'school_id' => $schoolId,
                            'destination' => $row[0],
                            'price' => $row[1],
                            'status' => 'active',
                        ]);
                        $imported++;
                    }
                }
            }

            $message = "";
            if ($imported > 0) {
                $message .= "Created {$imported} new bus fees. ";
            }
            if ($updated > 0) {
                $message .= "Updated {$updated} existing bus fees.";
            }

            return redirect()->back()->with('success', $message ?: 'No records to import.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Export bus fees to Excel.
     */
    public function exportBusFees()
    {
        $schoolId = auth()->user()->school_id;
        $fees = BusFee::where('school_id', $schoolId)->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'Destination');
        $sheet->setCellValue('B1', 'Price');
        
        $row = 2;
        foreach ($fees as $fee) {
            $sheet->setCellValue('A' . $row, $fee->destination);
            $sheet->setCellValue('B' . $row, $fee->price);
            $row++;
        }

        $filename = 'bus-fees-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // ==================== BOOKSET PRICES ====================
    
    /**
     * Bookset prices page.
     */
    public function booksetPrices()
    {
        $schoolId = auth()->user()->school_id;
        $years = AcademicYear::where('school_id', $schoolId)->orderBy('year', 'desc')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->where('status', 'active')->get();
        $prices = BooksetPrice::where('school_id', $schoolId)
            ->with(['academicYear', 'schoolClass'])
            ->orderBy('academic_year_id', 'desc')
            ->get();
        
        return view('school-admin.fees.bookset-prices', compact('years', 'classes', 'prices'));
    }

    /**
     * Store bookset price.
     */
    public function storeBooksetPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'book_price' => 'required|numeric|min:0',
            'notebook_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $exists = BooksetPrice::where('school_id', auth()->user()->school_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('class_id', $request->class_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Bookset price for this class and year already exists.')
                ->withInput();
        }

        BooksetPrice::create([
            'school_id' => auth()->user()->school_id,
            'academic_year_id' => $request->academic_year_id,
            'class_id' => $request->class_id,
            'book_price' => $request->book_price,
            'notebook_price' => $request->notebook_price,
            'total_price' => $request->book_price + $request->notebook_price,
        ]);

        return redirect()->back()->with('success', 'Bookset price created successfully.');
    }

    // ==================== DISCOUNT RULES ====================
    
    /**
     * Discount rules page.
     */
    public function discountRules()
    {
        $schoolId = auth()->user()->school_id;
        $discount = DiscountRule::where('school_id', $schoolId)->first();
        
        return view('school-admin.fees.discount-rules', compact('discount'));
    }

    /**
     * Store/update discount rule.
     */
    public function storeDiscountRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'same_month_discount' => 'required|numeric|min:0',
            'next_month_discount' => 'required|numeric|min:0',
            'valid_till_day' => 'required|integer|min:1|max:31',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DiscountRule::updateOrCreate(
            ['school_id' => auth()->user()->school_id],
            [
                'same_month_discount' => $request->same_month_discount,
                'next_month_discount' => $request->next_month_discount,
                'valid_till_day' => $request->valid_till_day,
            ]
        );

        return redirect()->back()->with('success', 'Discount rule updated successfully.');
    }

    /**
     * Helper to authorize school access.
     */
    private function authorizeSchool($model)
    {
        if ($model->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized access.');
        }
    }
}
