<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\AdmissionFee;
use App\Models\RegistrationFee;
use App\Models\ClassFee;
use App\Models\BusFee;
use App\Models\BooksetPrice;
use App\Models\DiscountRule;
use App\Models\StudentDue;
use App\Models\StudentAdvance;
use App\Models\Receipt;
use App\Models\MonthlyPayment;
use App\Models\StudentAcademicHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends Controller
{
    // ==================== STUDENTS LIST (AJAX) ====================
    
    /**
     * Display all students (for DataTables AJAX).
     */
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $query = Student::with('schoolClass')
            ->where('school_id', $schoolId);
        
        // Search filter
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('student_id', 'like', '%' . $search . '%');
            });
        }
        
        // Custom filters
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->has('medium') && $request->medium) {
            $query->where('medium', $request->medium);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Get total count before filtering
        $totalRecords = Student::where('school_id', $schoolId)->count();
        
        // Get filtered count
        $filteredRecords = $query->count();
        
        // Sorting
        $orderColumn = $request->columns[$request->order[0]['column']]['name'] ?? 'created_at';
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        
        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        
        $students = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();
        
        // Format data for DataTables
        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'student_id' => $student->student_id,
                'photo' => $student->photo ? '<img src="' . asset('storage/' . $student->photo) . '" width="40" height="40" class="rounded-circle">' : '<div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">' . substr($student->name, 0, 1) . '</div>',
                'name' => $student->name,
                'class_name' => $student->schoolClass ? $student->schoolClass->class_name : '-',
                'roll' => $student->roll ?? '-',
                'gender' => ucfirst($student->gender),
                'dob' => $student->dob ? Carbon::parse($student->dob)->format('d-m-Y') : '-',
                'medium' => $student->medium,
                'status' => '<span class="badge bg-' . ($student->status == 'active' ? 'success' : 'secondary') . '">' . ucfirst($student->status) . '</span>',
                'actions' => '<a href="' . route('students.show', $student->id) . '" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                             <a href="' . route('students.edit', $student->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>'
            ];
        }
        
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    // ==================== ADMISSION ====================
    
    /**
     * Show admission form.
     */
    public function admission()
    {
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
            
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('minimum_age')
            ->get();
            
        $mediums = ['Bengali', 'English', 'Hindi'];
        
        // Get admission fees
        $admissionFees = [];
        if ($academicYear) {
            $admissionFees = AdmissionFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'active')
                ->get()
                ->keyBy('medium');
        }
        
        // Get pending admission students
        $pendingStudents = Student::where('school_id', $schoolId)
            ->where('admission_status', 'pending')
            ->with('schoolClass')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('students.admission', compact(
            'academicYear',
            'classes',
            'mediums',
            'admissionFees',
            'pendingStudents'
        ));
    }

    /**
     * Store new student (pre-admission).
     */
    public function storeStudent(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $school = auth()->user()->school;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'medium' => 'required|in:Bengali,English,Hindi',
            'admission_date' => 'required|date',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check age criteria
        $class = SchoolClass::find($request->class_id);
        $studentAge = Carbon::parse($request->dob)->age;
        
        if ($studentAge < $class->minimum_age) {
            return redirect()->back()
                ->with('error', "Student age ({$studentAge} years) is less than minimum required age for {$class->class_name} ({$class->minimum_age}+ years).")
                ->withInput();
        }

        // Generate student ID
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
            
        $yearCode = $academicYear ? substr($academicYear->year, -2) : date('y');
        $schoolCode = $school->school_code ?? 'XX';
        
        // Get last student count for this year
        $lastStudent = Student::where('school_id', $schoolId)
            ->where('student_id', 'like', $yearCode . $schoolCode . '%')
            ->orderBy('student_id', 'desc')
            ->first();
            
        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->student_id, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        $studentId = $yearCode . $schoolCode . $newNumber;

        // Create student
        $student = Student::create([
            'school_id' => $schoolId,
            'student_id' => $studentId,
            'name' => $request->name,
            'dob' => $request->dob,
            'class_id' => $request->class_id,
            'medium' => $request->medium,
            'admission_date' => $request->admission_date,
            'whatsapp' => $request->whatsapp_number,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'admission_status' => 'pending',
            'status' => 'active',
            'academic_year' => $academicYear ? $academicYear->year : date('Y'),
        ]);

        // Create academic history
        StudentAcademicHistory::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear ? $academicYear->id : null,
            'class_id' => $request->class_id,
            'registration_status' => 'unregistered',
        ]);

        return redirect()->back()->with('success', 'Student added successfully. Please complete the admission billing.');
    }

    /**
     * Show admission billing form.
     */
    public function admissionBilling(Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
            
        // Get admission fee for this student
        $admissionFee = null;
        if ($academicYear) {
            $admissionFee = AdmissionFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('medium', $student->medium)
                ->where('status', 'active')
                ->first();
        }
            
        // Get old due
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
            
        // Get advance
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');
            
        // Generate receipt number
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'admission')
            ->orderBy('receipt_no', 'desc')
            ->first();
            
        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;
            
        return view('students.admission-billing', compact(
            'student',
            'academicYear',
            'admissionFee',
            'oldDue',
            'advance',
            'receiptNo'
        ));
    }

    /**
     * Process admission billing.
     */
    public function processAdmissionBilling(Request $request, Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'receipt_no' => 'required|integer|unique:receipts,receipt_no,NULL,id,school_id,' . $schoolId,
            'billing_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,online',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        // Get admission fee
        $admissionFee = null;
        $totalAmount = 0;
        if ($academicYear) {
            $admissionFee = AdmissionFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('medium', $student->medium)
                ->where('status', 'active')
                ->first();
            $totalAmount = $admissionFee ? $admissionFee->amount : 0;
        }
        
        $discount = $request->discount ?? 0;
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');

        $finalTotal = $totalAmount + $oldDue - $discount - $advance;
        $amountPaid = $request->amount_paid;

        // Calculate due/advance
        $newDue = max(0, $finalTotal - $amountPaid);
        $newAdvance = max(0, $amountPaid - $finalTotal);

        DB::transaction(function () use ($schoolId, $student, $request, $totalAmount, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $academicYear) {
            // Create receipt
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $request->receipt_no,
                'bill_type' => 'admission',
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'status' => 'active',
                'created_by' => auth()->id(),
            ]);

            // Update old due
            if ($oldDue > 0) {
                StudentDue::where('school_id', $schoolId)
                    ->where('student_id', $student->id)
                    ->delete();
            }

            // Update advance
            $currentAdvance = StudentAdvance::where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->first();
                
            if ($currentAdvance) {
                if ($newAdvance > 0) {
                    $currentAdvance->update(['total_advance' => $newAdvance]);
                } else {
                    $currentAdvance->delete();
                }
            } elseif ($newAdvance > 0 && $academicYear) {
                StudentAdvance::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'total_advance' => $newAdvance,
                ]);
            }

            // Create new due if any
            if ($newDue > 0 && $academicYear) {
                StudentDue::updateOrCreate(
                    ['school_id' => $schoolId, 'student_id' => $student->id, 'academic_year_id' => $academicYear->id],
                    ['total_due' => $newDue]
                );
            }

            // Update student status
            $student->update([
                'admission_status' => 'completed',
                'status' => 'active'
            ]);
        });

        return redirect()->route('students.admission')
            ->with('success', 'Admission billing completed successfully.');
    }

    /**
     * Show student profile.
     */
    public function show(Student $student)
    {
        $this->authorizeSchool($student);
        $student->load('schoolClass');
        
        return view('students.show', compact('student'));
    }

    /**
     * Show edit student form.
     */
    public function edit(Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        $classes = SchoolClass::where('school_id', $schoolId)->where('status', 'active')->get();
        $busFees = BusFee::where('school_id', $schoolId)->where('status', 'active')->get();
        
        return view('students.edit', compact('student', 'classes', 'busFees'));
    }

    /**
     * Update student.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeSchool($student);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'roll' => 'nullable|integer',
            'section' => 'nullable|string|max:10',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'social_category' => 'nullable|in:SC,ST,OBC,General,Others',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student->update($request->all());

        return redirect()->route('students.list')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student.
     */
    public function destroy(Student $student)
    {
        $this->authorizeSchool($student);
        
        // Check for active dues
        $due = StudentDue::where('student_id', $student->id)->sum('total_due');
        
        if ($due > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete student with pending dues.');
        }
        
        $student->update(['status' => 'deleted']);
        
        return redirect()->route('students.admission')
            ->with('success', 'Student deleted successfully.');
    }

    // ==================== FEE COLLECTION ====================
    
    /**
     * Show fee collection page.
     */
    public function feeCollection(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $query = Student::where('school_id', $schoolId)
            ->where('status', 'active')
            ->where('admission_status', 'completed');
            
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        $students = $query->orderBy('name')->paginate(20);
        
        return view('students.fee-collection', compact('students'));
    }

    /**
     * Show student payment history.
     */
    public function paymentHistory(Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Get all academic years
        $academicYears = AcademicYear::where('school_id', $schoolId)
            ->orderBy('year', 'desc')
            ->get();
            
        // Get current year
        $currentYear = $academicYears->where('is_active', true)->first();
        $selectedYear = request('year', $currentYear ? $currentYear->year : date('Y'));
        
        // Get class fee for student
        $classFee = null;
        $tuitionFee = 0;
        
        if ($currentYear) {
            $classFee = ClassFee::where('school_id', $schoolId)
                ->where('academic_year_id', $currentYear->id)
                ->where('class_id', $student->class_id)
                ->where('medium', $student->medium)
                ->first();
            $tuitionFee = $classFee ? $classFee->tuition_fee : 0;
        }
        
        // Get bus fee
        $busFee = 0;
        
        // Get old due
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
            
        // Get advance
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');
            
        // Get monthly payments
        $payments = MonthlyPayment::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->whereHas('academicYear', function($q) use ($selectedYear) {
                $q->where('year', $selectedYear);
            })
            ->orderBy('month')
            ->get();
            
        return view('students.payment-history', compact(
            'student',
            'academicYears',
            'selectedYear',
            'tuitionFee',
            'busFee',
            'oldDue',
            'advance',
            'payments'
        ));
    }

    /**
     * Show monthly bill generation form.
     */
    public function monthlyBill(Student $student, Request $request)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Get current academic year
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
            
        // Get class fee
        $classFee = null;
        $tuitionFee = 0;
        
        if ($academicYear) {
            $classFee = ClassFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('class_id', $student->class_id)
                ->where('medium', $student->medium)
                ->first();
            $tuitionFee = $classFee ? $classFee->tuition_fee : 0;
        }
        
        // Get bus fee
        $busFee = 0;
        
        // Get discount rule
        $discountRule = DiscountRule::where('school_id', $schoolId)->first();
        
        // Get old due
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
            
        // Get advance
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');
            
        // Get already paid months
        $paidMonths = [];
        if ($academicYear) {
            $paidMonths = MonthlyPayment::where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'paid')
                ->pluck('month')
                ->toArray();
        }
            
        // Generate receipt number
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'monthly')
            ->orderBy('receipt_no', 'desc')
            ->first();
            
        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;
        
        return view('students.monthly-bill', compact(
            'student',
            'academicYear',
            'tuitionFee',
            'busFee',
            'discountRule',
            'oldDue',
            'advance',
            'paidMonths',
            'receiptNo'
        ));
    }

    /**
     * Process monthly bill payment.
     */
    public function processMonthlyBill(Request $request, Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'receipt_no' => 'required|integer|unique:receipts,receipt_no,NULL,id,school_id,' . $schoolId,
            'billing_date' => 'required|date',
            'months' => 'required|array|min:1',
            'discount' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,online',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        // Get fees
        $classFee = null;
        $tuitionFee = 0;
        
        if ($academicYear) {
            $classFee = ClassFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('class_id', $student->class_id)
                ->where('medium', $student->medium)
                ->first();
            $tuitionFee = $classFee ? $classFee->tuition_fee : 0;
        }
        
        $busFee = 0;

        $selectedMonths = $request->months;
        $monthCount = count($selectedMonths);
        $subtotal = ($tuitionFee + $busFee) * $monthCount;
        $discount = $request->discount ?? 0;
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');

        $finalTotal = $subtotal + $oldDue - $discount - $advance;
        $amountPaid = $request->amount_paid;

        $newDue = max(0, $finalTotal - $amountPaid);
        $newAdvance = max(0, $amountPaid - $finalTotal);

        DB::transaction(function () use ($schoolId, $student, $request, $academicYear, $tuitionFee, $busFee, $subtotal, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $selectedMonths, $monthCount) {
            // Create receipt
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $request->receipt_no,
                'bill_type' => 'monthly',
                'total_amount' => $subtotal,
                'discount' => $discount,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'description' => 'Monthly fees for: ' . implode(', ', array_map(function($m) { 
                    return date('F', mktime(0, 0, 0, $m, 1)); 
                }, $selectedMonths)),
                'status' => 'active',
                'created_by' => auth()->id(),
            ]);

            // Create monthly payment records
            foreach ($selectedMonths as $month) {
                MonthlyPayment::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear ? $academicYear->id : null,
                    'month' => $month,
                    'tuition_fee' => $tuitionFee,
                    'bus_fee' => $busFee,
                    'total_fee' => $tuitionFee + $busFee,
                    'discount' => $discount / $monthCount,
                    'paid_amount' => $amountPaid / $monthCount,
                    'receipt_no' => $request->receipt_no,
                    'receipt_id' => $receipt->id,
                    'payment_date' => $request->billing_date,
                    'status' => 'paid',
                ]);
            }

            // Update old due
            if ($oldDue > 0) {
                StudentDue::where('school_id', $schoolId)
                    ->where('student_id', $student->id)
                    ->delete();
            }

            // Update advance
            $currentAdvance = StudentAdvance::where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->first();
                
            if ($currentAdvance) {
                if ($newAdvance > 0) {
                    $currentAdvance->update(['total_advance' => $newAdvance]);
                } else {
                    $currentAdvance->delete();
                }
            } elseif ($newAdvance > 0 && $academicYear) {
                StudentAdvance::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'total_advance' => $newAdvance,
                ]);
            }

            // Create new due if any
            if ($newDue > 0 && $academicYear) {
                StudentDue::updateOrCreate(
                    ['school_id' => $schoolId, 'student_id' => $student->id, 'academic_year_id' => $academicYear->id],
                    ['total_due' => $newDue]
                );
            }
        });

        return redirect()->route('students.payment-history', $student->id)
            ->with('success', 'Payment processed successfully.');
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
