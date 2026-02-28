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
use App\Models\StudentAdmission;
use App\Models\StudentEnrollment;
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
        $user = auth()->user();
        
        // Super admin can see all students, school admins see only their school's students
        $query = Student::with('schoolClass');
        
        if ($user->role !== 'super_admin') {
            $query->where('school_id', $user->school_id);
        }
        
        // Search filter - handle both DataTables format and simple string
        $search = $request->search;
        if (is_array($search) && isset($search['value'])) {
            $search = $search['value'];
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('student_id', 'like', '%' . $search . '%');
            });
        }
        
        // Custom filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('medium')) {
            $query->where('medium', $request->medium);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get total count before filtering (respect role-based access)
        if ($user->role === 'super_admin') {
            $totalRecords = Student::count();
        } else {
            $totalRecords = Student::where('school_id', $user->school_id)->count();
        }
        
        // Get filtered count
        $filteredRecords = $query->count();
        
        // Sorting - with null checks for safety
        $orderColumn = 'created_at';
        $orderDir = 'desc';
        
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColIndex = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'desc';
            
            if ($request->has('columns') && is_array($request->columns) && isset($request->columns[$orderColIndex])) {
                $orderColumn = $request->columns[$orderColIndex]['name'] ?? 'created_at';
            }
        }
        
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

    /**
     * Search students with pagination and filters (AJAX).
     */
    public function search(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $query = Student::with('schoolClass')
            ->where('school_id', $schoolId);
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('student_id', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('father_name', 'like', '%' . $search . '%');
            });
        }
        
        // Class filter
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        // Gender filter
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }
        
        // Medium filter
        if ($request->has('medium') && $request->medium) {
            $query->where('medium', $request->medium);
        }
        
        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Get total count
        $totalRecords = Student::where('school_id', $schoolId)->count();
        
        // Get filtered count
        $filteredRecords = $query->count();
        
        // Pagination
        $perPage = $request->per_page ?? 10;
        $students = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'students' => $students,
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords
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
     * Show registration form.
     */
    public function registration()
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
        
        // Get registration fees
        $registrationFees = [];
        if ($academicYear) {
            $registrationFees = RegistrationFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'active')
                ->get()
                ->keyBy('medium');
        }
        
        // Get students with completed admission but pending registration (Unregistered tab)
        $unregisteredStudents = Student::where('school_id', $schoolId)
            ->where('admission_status', 'completed')
            ->where('registration_status', 'pending')
            ->with('schoolClass')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate next class based on age for each student
        $unregisteredStudents = $unregisteredStudents->map(function($student) {
            if ($student->dob) {
                $studentAge = Carbon::parse($student->dob)->age;
                // Find the next class based on minimum age
                $nextClass = SchoolClass::where('school_id', $student->school_id)
                    ->where('status', 'active')
                    ->where('minimum_age', '>', $studentAge)
                    ->orderBy('minimum_age')
                    ->first();
                $student->nextClass = $nextClass;
            }
            return $student;
        });
            
        // Get students with completed registration (Registered tab)
        $registeredStudents = Student::where('school_id', $schoolId)
            ->where('admission_status', 'completed')
            ->where('registration_status', 'completed')
            ->with('schoolClass')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('students.registration', compact(
            'academicYear',
            'classes',
            'mediums',
            'registrationFees',
            'unregisteredStudents',
            'registeredStudents'
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
        $schoolCode = $school->code ?? 'KR';
        
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
     * Show registration billing form.
     */
    public function registrationBilling(Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year (the NEW academic year for registration)
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        // If no active academic year, redirect back with error
        if (!$academicYear) {
            return redirect()->back()->with('error', 'No active academic year found. Please set up and activate an academic year first.');
        }
            
        // Get registration fee for this student
        $registrationFee = RegistrationFee::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYear->id)
            ->whereRaw('LOWER(medium) = ?', [strtolower($student->medium)])
            ->first();
        
        // If registration fee is not set for this student's medium, redirect to fee not set page
        if (!$registrationFee) {
            $medium = $student->medium;
            return view('students.registration-fee-not-set', compact('student', 'academicYear', 'medium'));
        }
            
        // Get old due from previous academic year
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
            
        // Get advance
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');
            
        // Generate receipt number
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'registration')
            ->orderBy('receipt_no', 'desc')
            ->first();
            
        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;
            
        return view('students.registration-billing', compact(
            'student',
            'academicYear',
            'registrationFee',
            'oldDue',
            'advance',
            'receiptNo'
        ));
    }

    /**
     * Process registration billing.
     */
    public function processRegistrationBilling(Request $request, Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Auto-generate receipt number
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'registration')
            ->orderBy('receipt_no', 'desc')
            ->first();
        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;
        
        $validator = Validator::make($request->all(), [
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

        // Get registration fee
        $registrationFee = null;
        $totalAmount = 0;
        if ($academicYear) {
            $registrationFee = RegistrationFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->whereRaw('LOWER(medium) = ?', [strtolower($student->medium)])
                ->where('status', 'active')
                ->first();
            $totalAmount = $registrationFee ? $registrationFee->amount : 0;
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
        
        $receipt = DB::transaction(function () use ($schoolId, $student, $request, $totalAmount, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $academicYear, $receiptNo, $advance) {
            // Create receipt
            $receiptStatus = $newDue > 0 ? 'due' : 'paid';
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $receiptNo,
                'bill_type' => 'registration',
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'less_advance' => $advance,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'status' => $receiptStatus,
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

            // Update student registration status to completed
            $student->update([
                'registration_status' => 'completed'
            ]);
            
            // Update academic history
            StudentAcademicHistory::where('student_id', $student->id)
                ->update(['registration_status' => 'registered']);
            
            return $receipt;
        });

        // Redirect to registration page with success message
        return redirect()->route('students.registration')
            ->with('success', 'Registration billing completed successfully.')
            ->with('receipt_id', $receipt->id);
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
        
        // If no active academic year, redirect back with error
        if (!$academicYear) {
            return redirect()->back()->with('error', 'No active academic year found. Please set up and activate an academic year first.');
        }
            
        // Get admission fee for this student - use case-insensitive comparison for medium
        $admissionFee = AdmissionFee::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYear->id)
            ->whereRaw('LOWER(medium) = ?', [strtolower($student->medium)])
            ->first();
        
        // If admission fee is not set for this student's medium, redirect to fee not set page
        if (!$admissionFee) {
            $medium = $student->medium;
            return view('students.admission-fee-not-set', compact('student', 'academicYear', 'medium'));
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

            // dd($lastReceipt);
            
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
        
        // Auto-generate receipt number - don't rely on form value
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'admission')
            ->orderBy('receipt_no', 'desc')
            ->first();
        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;
        
        $validator = Validator::make($request->all(), [
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
        
        $receipt = DB::transaction(function () use ($schoolId, $student, $request, $totalAmount, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $academicYear, $receiptNo, $advance) {
            // Create receipt - use 'paid' or 'due' based on payment status
            $receiptStatus = $newDue > 0 ? 'due' : 'paid';
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $receiptNo,
                'bill_type' => 'admission',
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'less_advance' => $advance,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'status' => $receiptStatus,
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

            // Update student status - set admission_status to completed
            $student->update([
                'status' => 'active',
                'admission_status' => 'completed'
            ]);
            
            return $receipt;
        });

        // Redirect with receipt ID to open in new window
        return redirect()->route('students.admission')
            ->with('success', 'Admission billing completed successfully.')
            ->with('receipt_id', $receipt->id);
    }

    /**
     * Generate admission PDF data (API endpoint for frontend PDF generation).
     */
    public function generateAdmissionData(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'receipt_no' => 'required',
            'student_name' => 'required',
            'students_school_id' => 'required',
            'class' => 'required',
            'fee_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'less_advance' => 'nullable|numeric',
            'old_due' => 'nullable|numeric',
            'new_due' => 'nullable|numeric',
            'amount_paid' => 'required|numeric',
            'payment_mode' => 'required',
            'billing_date' => 'required|date',
            'fee_type' => 'required',
            'academic_year' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        return response()->json([
            'receipt_no' => $request->receipt_no,
            'student_name' => $request->student_name,
            'students_school_id' => $request->students_school_id,
            'class' => $request->class,
            'fee_amount' => $request->fee_amount,
            'discount' => $request->discount ?? 0,
            'less_advance' => $request->less_advance ?? 0,
            'old_due' => $request->old_due ?? 0,
            'new_due' => $request->new_due ?? 0,
            'amount_paid' => $request->amount_paid,
            'payment_mode' => strtoupper($request->payment_mode),
            'total_amount' => $request->total_amount ?? 0,
            'billing_date' => $request->billing_date,
            'fee_type' => $request->fee_type,
            'academic_year' => $request->academic_year ?? date('Y'),
        ]);
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
     * Delete student permanently.
     */
    public function destroy(Student $student)
    {
        $this->authorizeSchool($student);
        
        // Delete all related data
        StudentDue::where('student_id', $student->id)->delete();
        StudentAdvance::where('student_id', $student->id)->delete();
        Receipt::where('student_id', $student->id)->delete();
        MonthlyPayment::where('student_id', $student->id)->delete();
        StudentAcademicHistory::where('student_id', $student->id)->delete();
        
        // Permanently delete the student
        $student->delete();
        
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
        
        // Get search parameters
        $searchType = $request->input('search_type', 'id');
        $query = $request->input('query', '');
        
        // Get all active students for the list
        $studentsQuery = Student::with('schoolClass')->where('school_id', $schoolId)
            ->where('status', 'active');
            
        if ($query) {
            if ($searchType === 'id') {
                $studentsQuery->where('student_id', 'like', '%' . $query . '%');
            } elseif ($searchType === 'name') {
                $studentsQuery->where('name', 'like', '%' . $query . '%');
            } elseif ($searchType === 'phone') {
                $studentsQuery->where('phone', 'like', '%' . $query . '%');
            }
        }
        
        $students = $studentsQuery->orderBy('name')->paginate(20);
        
        // Get selected student details
        $selectedStudent = null;
        $totalDue = 0;
        $totalAdvance = 0;
        $monthlyFee = 0;
        $busFee = 0;
        $academicYears = [];
        $selectedYear = null;
        $payments = collect([]);
        
        if ($request->student_id) {
            $selectedStudent = Student::with('schoolClass')
                ->where('school_id', $schoolId)
                ->where('id', $request->student_id)
                ->first();
            
            if ($selectedStudent) {
                // Get academic years
                $academicYears = AcademicYear::where('school_id', $schoolId)
                    ->orderBy('year', 'desc')
                    ->get();
                
                // Get current year
                $currentYear = $academicYears->where('is_active', true)->first();
                $selectedYear = $request->year ?? ($currentYear ? $currentYear->id : ($academicYears->first()->id ?? null));
                
                // Get current academic year object
                $selectedYearObj = $academicYears->firstWhere('id', $selectedYear);
                
                // Get fees
                if ($selectedYearObj) {
                    $classFee = ClassFee::where('school_id', $schoolId)
                        ->where('academic_year_id', $selectedYear)
                        ->where('class_id', $selectedStudent->class_id)
                        ->where('medium', $selectedStudent->medium)
                        ->first();
                    $monthlyFee = $classFee ? $classFee->tuition_fee : 0;
                    
                    if ($selectedStudent->bus_destination_id) {
                        $busFeeRecord = BusFee::where('school_id', $schoolId)
                            ->where('id', $selectedStudent->bus_destination_id)
                            ->first();
                        $busFee = $busFeeRecord ? $busFeeRecord->fee : 0;
                    }
                }
                
                // Get old due
                $totalDue = StudentDue::where('school_id', $schoolId)
                    ->where('student_id', $selectedStudent->id)
                    ->sum('total_due');
                
                // Get advance
                $totalAdvance = StudentAdvance::where('school_id', $schoolId)
                    ->where('student_id', $selectedStudent->id)
                    ->sum('total_advance');
                
                // Get payments
                if ($selectedYear) {
                    $payments = MonthlyPayment::where('school_id', $schoolId)
                        ->where('student_id', $selectedStudent->id)
                        ->where('academic_year_id', $selectedYear)
                        ->orderBy('month')
                        ->get();
                }
            }
        }
       return view('students.fee-collection', compact(
            'students',
            'selectedStudent',
            'totalDue',
            'totalAdvance',
            'monthlyFee',
            'busFee',
            'academicYears',
            'selectedYear',
            'payments'
        ));

         // return "Hello World - Fee Collection Page (Under Construction)";
        // return view('students.fee-collection');
        
    }

    /**
     * Show fee price list for a student.
     */
    public function feePriceList(Student $student)
    {
        $this->authorizeSchool($student);
        
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year
        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        // Get Admission Fee
        $admissionFeeAmount = 0;
        if ($academicYear) {
            $admissionFee = AdmissionFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('medium', $student->medium)
                ->where('status', 'active')
                ->first();
            $admissionFeeAmount = $admissionFee ? $admissionFee->amount : 0;
        }
        
        // Get Registration Fee
        $registrationFeeAmount = 0;
        if ($academicYear) {
            $registrationFee = RegistrationFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('medium', $student->medium)
                ->where('status', 'active')
                ->first();
            $registrationFeeAmount = $registrationFee ? $registrationFee->amount : 0;
        }
        
        // Get Class Fee (Monthly)
        $classFeeAmount = 0;
        if ($academicYear) {
            $classFee = ClassFee::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('class_id', $student->class_id)
                ->where('medium', $student->medium)
                ->first();
            $classFeeAmount = $classFee ? $classFee->tuition_fee : 0;
        }
        
        // Get Bus Fee
        $busFeeAmount = 0;
        if ($student->bus_destination_id) {
            $busFee = BusFee::where('school_id', $schoolId)
                ->where('id', $student->bus_destination_id)
                ->where('status', 'active')
                ->first();
            $busFeeAmount = $busFee ? $busFee->fee : 0;
        }
        
        // Get Bookset Price
        $booksetPriceAmount = 0;
        if ($academicYear) {
            $booksetPrice = BooksetPrice::where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('medium', $student->medium)
                ->where('status', 'active')
                ->first();
            $booksetPriceAmount = $booksetPrice ? $booksetPrice->total_price : 0;
        }
        
        // Get old due and advance
        $oldDue = StudentDue::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_due');
            
        $advance = StudentAdvance::where('school_id', $schoolId)
            ->where('student_id', $student->id)
            ->sum('total_advance');
        
        return view('students.fee-price-list', compact(
            'student',
            'admissionFeeAmount',
            'registrationFeeAmount',
            'classFeeAmount',
            'busFeeAmount',
            'booksetPriceAmount',
            'oldDue',
            'advance'
        ));
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

        DB::transaction(function () use ($schoolId, $student, $request, $academicYear, $tuitionFee, $busFee, $subtotal, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $selectedMonths, $monthCount, $advance) {
            // Create receipt - use 'paid' or 'due' based on payment status
            $receiptStatus = $newDue > 0 ? 'due' : 'paid';
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $request->receipt_no,
                'bill_type' => 'monthly',
                'total_amount' => $subtotal,
                'discount' => $discount,
                'less_advance' => $advance,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'description' => 'Monthly fees for: ' . implode(', ', array_map(function($m) { 
                    return date('F', mktime(0, 0, 0, $m, 1)); 
                }, $selectedMonths)),
                'status' => $receiptStatus,
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
     * Process fee collection from fee collection page.
     */
    public function collectFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'months' => 'required|array|min:1',
            'billing_date' => 'required|date',
            'payment_mode' => 'required|in:cash,online,cheque',
            'discount' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $schoolId = auth()->user()->school_id;
        $student = Student::where('school_id', $schoolId)
            ->where('id', $request->student_id)
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $academicYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        if (!$academicYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }

        // Get fees
        $classFee = ClassFee::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYear->id)
            ->where('class_id', $student->class_id)
            ->where('medium', $student->medium)
            ->first();
        $tuitionFee = $classFee ? $classFee->tuition_fee : 0;

        $busFee = 0;
        if ($student->bus_destination_id) {
            $busFeeRecord = BusFee::where('school_id', $schoolId)
                ->where('id', $student->bus_destination_id)
                ->first();
            $busFee = $busFeeRecord ? $busFeeRecord->fee : 0;
        }

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

        // Generate receipt number
        $lastReceipt = Receipt::where('school_id', $schoolId)
            ->where('bill_type', 'monthly')
            ->orderBy('receipt_no', 'desc')
            ->first();

        $receiptNo = $lastReceipt ? $lastReceipt->receipt_no + 1 : 1;

        DB::transaction(function () use ($schoolId, $student, $request, $academicYear, $tuitionFee, $busFee, $subtotal, $discount, $amountPaid, $oldDue, $newDue, $newAdvance, $selectedMonths, $monthCount, $receiptNo, $advance) {
            // Create receipt
            $receiptStatus = $newDue > 0 ? 'due' : 'paid';
            $receipt = Receipt::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'receipt_no' => $receiptNo,
                'bill_type' => 'monthly',
                'total_amount' => $subtotal,
                'discount' => $discount,
                'less_advance' => $advance,
                'paid_amount' => $amountPaid,
                'due_amount' => $newDue,
                'advance_amount' => $newAdvance,
                'old_due_paid' => $oldDue,
                'payment_mode' => $request->payment_mode,
                'billing_date' => $request->billing_date,
                'description' => 'Monthly fees for: ' . implode(', ', array_map(function($m) { 
                    return date('F', mktime(0, 0, 0, $m, 1)); 
                }, $selectedMonths)),
                'status' => $receiptStatus,
                'created_by' => auth()->id(),
            ]);

            // Create monthly payment records
            foreach ($selectedMonths as $month) {
                MonthlyPayment::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'month' => $month,
                    'tuition_fee' => $tuitionFee,
                    'bus_fee' => $busFee,
                    'total_fee' => $tuitionFee + $busFee,
                    'discount' => $monthCount > 0 ? $discount / $monthCount : 0,
                    'paid_amount' => $monthCount > 0 ? $amountPaid / $monthCount : 0,
                    'receipt_no' => $receiptNo,
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
            } elseif ($newAdvance > 0) {
                StudentAdvance::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'total_advance' => $newAdvance,
                ]);
            }

            // Create new due if any
            if ($newDue > 0) {
                StudentDue::updateOrCreate(
                    ['school_id' => $schoolId, 'student_id' => $student->id, 'academic_year_id' => $academicYear->id],
                    ['total_due' => $newDue]
                );
            }
        });

        return redirect()->route('students.fee-collection', ['student_id' => $student->id])
            ->with('success', 'Payment processed successfully. Receipt No: ' . $receiptNo);
    }

    /**
     * Show receipt for a payment.
     */
    public function showReceipt(MonthlyPayment $payment)
    {
        $this->authorizeSchool($payment);
        
        $payment->load('student.schoolClass', 'academicYear');
        
        return view('students.receipt', compact('payment'));
    }

    /**
     * Show bill history - all bills/receipts for the school.
     */
    public function billHistory(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        // Get filter parameters
        $searchType = $request->input('search_type', 'receipt');
        $query = $request->input('query', '');
        $billType = $request->input('bill_type', '');
        $status = $request->input('status', '');
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');
        
        // Build the receipts query
        $receiptsQuery = Receipt::with(['student.schoolClass'])
            ->where('school_id', $schoolId);
        
        // Apply search filter
        if ($query) {
            if ($searchType === 'receipt') {
                $receiptsQuery->where('receipt_no', 'like', '%' . $query . '%');
            } elseif ($searchType === 'student') {
                $receiptsQuery->whereHas('student', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                });
            } elseif ($searchType === 'phone') {
                $receiptsQuery->whereHas('student', function($q) use ($query) {
                    $q->where('phone', 'like', '%' . $query . '%');
                });
            }
        }
        
        // Apply bill type filter
        if ($billType) {
            $receiptsQuery->where('bill_type', $billType);
        }
        
        // Apply status filter
        if ($status) {
            $receiptsQuery->where('status', $status);
        }
        
        // Apply date filters
        if ($fromDate) {
            $receiptsQuery->where('billing_date', '>=', $fromDate);
        }
        
        if ($toDate) {
            $receiptsQuery->where('billing_date', '<=', $toDate);
        }
        
        // Get paginated receipts
        $receipts = $receiptsQuery->orderBy('billing_date', 'desc')
            ->orderBy('receipt_no', 'desc')
            ->paginate(20);
        
        // Calculate summary statistics
        $totalReceipts = Receipt::where('school_id', $schoolId)->count();
        $totalAmount = Receipt::where('school_id', $schoolId)->sum('total_amount');
        $totalPaid = Receipt::where('school_id', $schoolId)->sum('paid_amount');
        $totalDue = Receipt::where('school_id', $schoolId)->sum('due_amount');
        
        return view('students.bill-history', compact(
            'receipts',
            'totalReceipts',
            'totalAmount',
            'totalPaid',
            'totalDue',
            'searchType',
            'query',
            'billType',
            'status',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Show receipt by receipt ID (from bill history).
     */
    public function showReceiptById(Receipt $receipt)
    {
        $this->authorizeSchool($receipt);
        
        $receipt->load('student.schoolClass');
        
        // Get school data for the receipt
        $school = \App\Models\School::find($receipt->school_id);
        
        // Check if it's an admission bill - use the new print format
        if ($receipt->bill_type === 'admission') {
            return view('students.admission-receipt-print', compact('receipt', 'school'));
        }
        
        return view('students.receipt', compact('receipt'));
    }

    /**
     * AJAX: Get bill history with filters (real-time filtering).
     */
    public function billHistoryAjax(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        // Get filter parameters
        $searchType = $request->input('search_type', 'receipt');
        $query = $request->input('query', '');
        $billType = $request->input('bill_type', '');
        $status = $request->input('status', '');
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');
        
        // Build the receipts query for filtering
        $receiptsQuery = Receipt::query()
            ->where('school_id', $schoolId);
        
        // Apply search filter
        if ($query) {
            if ($searchType === 'receipt') {
                $receiptsQuery->where('receipt_no', 'like', '%' . $query . '%');
            } elseif ($searchType === 'student') {
                $receiptsQuery->whereHas('student', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                });
            } elseif ($searchType === 'phone') {
                $receiptsQuery->whereHas('student', function($q) use ($query) {
                    $q->where('phone', 'like', '%' . $query . '%');
                });
            }
        }
        
        // Apply bill type filter
        if ($billType) {
            $receiptsQuery->where('bill_type', $billType);
        }
        
        // Apply status filter
        if ($status) {
            $receiptsQuery->where('status', $status);
        }
        
        // Apply date filters
        if ($fromDate) {
            $receiptsQuery->where('billing_date', '>=', $fromDate);
        }
        
        if ($toDate) {
            $receiptsQuery->where('billing_date', '<=', $toDate);
        }
        
        // Get total counts and sums before pagination
        $totalReceipts = (clone $receiptsQuery)->count();
        $totalAmount = (clone $receiptsQuery)->sum('total_amount');
        $totalPaid = (clone $receiptsQuery)->sum('paid_amount');
        $totalDue = (clone $receiptsQuery)->sum('due_amount');
        
        // Get paginated receipts with relationships
        $receipts = $receiptsQuery->with(['student', 'student.schoolClass'])
            ->orderBy('billing_date', 'desc')
            ->orderBy('receipt_no', 'desc')
            ->paginate(20);
        
        // Transform receipts to array with relationships
        $receiptsArray = $receipts->toArray();
        $receiptsData = $receiptsArray['data'];
        
        // Reformat for JSON with proper relationship data
        $formattedReceipts = array_map(function($receipt) {
            return [
                'id' => $receipt['id'],
                'receipt_no' => $receipt['receipt_no'],
                'billing_date' => $receipt['billing_date'],
                'bill_type' => $receipt['bill_type'],
                'total_amount' => $receipt['total_amount'],
                'paid_amount' => $receipt['paid_amount'],
                'due_amount' => $receipt['due_amount'],
                'status' => $receipt['status'],
                'student' => $receipt['student'] ? [
                    'id' => $receipt['student']['id'],
                    'name' => $receipt['student']['name'],
                    'student_id' => $receipt['student']['student_id'],
                    'school_class' => $receipt['student']['school_class'] ? [
                        'class_name' => $receipt['student']['school_class']['class_name']
                    ] : null
                ] : null
            ];
        }, $receiptsData);
        
        return response()->json([
            'receipts' => $formattedReceipts,
            'pagination' => [
                'current_page' => $receipts->currentPage(),
                'last_page' => $receipts->lastPage(),
                'per_page' => $receipts->perPage(),
                'total' => $receipts->total(),
                'next_page_url' => $receipts->nextPageUrl(),
                'prev_page_url' => $receipts->previousPageUrl(),
            ],
            'summary' => [
                'totalReceipts' => $totalReceipts,
                'totalAmount' => $totalAmount,
                'totalPaid' => $totalPaid,
                'totalDue' => $totalDue,
            ]
        ]);
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
