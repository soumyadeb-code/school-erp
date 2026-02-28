<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\StudentAdmission;
use App\Models\StudentEnrollment;
use App\Models\StudentPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Display promotions dashboard.
     */
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        // Get all academic years for dropdown
        $academicYears = AcademicYear::where('school_id', $schoolId)
            ->orderBy('year', 'desc')
            ->get();
        
        // Get all classes
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('minimum_age')
            ->get();
        
        // Get students eligible for promotion (active students with completed registration)
        $eligibleStudents = collect([]);
        
        if ($currentYear) {
            // Get students with enrollments in current year
            $enrolledStudentIds = StudentEnrollment::where('academic_year_id', $currentYear->id)
                ->whereIn('status', ['admitted', 'enrolled', 'promoted'])
                ->pluck('student_id');
            
            $eligibleStudents = Student::with('schoolClass')
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->where('registration_status', 'completed')
                ->whereIn('id', $enrolledStudentIds)
                ->orderBy('name')
                ->get();
        }
        
        // Get promoted students count
        $promotedCount = StudentEnrollment::whereHas('student', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })
        ->where('status', 'promoted')
        ->count();
        
        return view('school-admin.promotions.index', compact(
            'currentYear',
            'academicYears',
            'classes',
            'eligibleStudents',
            'promotedCount'
        ));
    }

    /**
     * Show promotion form.
     */
    public function create(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        // Get active academic year
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        if (!$currentYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }
        
        // Get next academic year
        $nextYear = AcademicYear::where('school_id', $schoolId)
            ->where('year', '>', $currentYear->year)
            ->orderBy('year')
            ->first();
        
        if (!$nextYear) {
            return redirect()->back()->with('error', 'No next academic year found. Please create the next academic year first.');
        }
        
        // Get all classes
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('minimum_age')
            ->get();
        
        // Get students eligible for promotion (active students in current year)
        $studentIds = StudentEnrollment::where('academic_year_id', $currentYear->id)
            ->whereIn('status', ['admitted', 'enrolled'])
            ->pluck('student_id');
        
        $students = Student::with('schoolClass')
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereIn('id', $studentIds)
            ->orderBy('name')
            ->get();
        
        // Calculate next class for each student
        $students = $students->map(function($student) use ($classes) {
            $currentClass = $student->schoolClass;
            if ($currentClass) {
                // Find next class
                if ($currentClass->next_class_id) {
                    $nextClass = $classes->firstWhere('id', $currentClass->next_class_id);
                } else {
                    $nextClass = $classes->firstWhere('minimum_age', '>', $currentClass->minimum_age);
                }
                $student->nextClass = $nextClass;
            }
            return $student;
        });
        
        return view('school-admin.promotions.create', compact(
            'currentYear',
            'nextYear',
            'classes',
            'students'
        ));
    }

    /**
     * Store promotions.
     */
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'to_academic_year_id' => 'required|exists:academic_years,id',
            'to_class_id' => 'required|exists:classes,id',
            'promotion_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get current academic year
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        if (!$currentYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }

        // Get target academic year
        $toAcademicYear = AcademicYear::find($request->to_academic_year_id);
        
        // Get target class
        $toClass = SchoolClass::find($request->to_class_id);

        $promotionDate = Carbon::parse($request->promotion_date);
        $successCount = 0;
        $failedCount = 0;

        DB::transaction(function () use ($request, $schoolId, $currentYear, $toAcademicYear, $toClass, $promotionDate, &$successCount, &$failedCount) {
            foreach ($request->student_ids as $studentId) {
                try {
                    $student = Student::where('school_id', $schoolId)
                        ->where('id', $studentId)
                        ->first();
                    
                    if (!$student) {
                        $failedCount++;
                        continue;
                    }

                    // Get current enrollment
                    $currentEnrollment = StudentEnrollment::where('student_id', $studentId)
                        ->where('academic_year_id', $currentYear->id)
                        ->first();
                    
                    if (!$currentEnrollment) {
                        $failedCount++;
                        continue;
                    }

                    // Check if enrollment already exists for target year
                    $existingEnrollment = StudentEnrollment::where('student_id', $studentId)
                        ->where('academic_year_id', $toAcademicYear->id)
                        ->first();
                    
                    if ($existingEnrollment) {
                        $failedCount++;
                        continue;
                    }

                    // Create new enrollment
                    $newEnrollment = StudentEnrollment::create([
                        'student_id' => $studentId,
                        'academic_year_id' => $toAcademicYear->id,
                        'class_id' => $toClass->id,
                        'roll' => $student->roll,
                        'section' => $student->section,
                        'status' => 'promoted',
                    ]);

                    // Update current enrollment with link to next enrollment
                    $currentEnrollment->update([
                        'status' => 'promoted',
                        'promoted_to_enrollment_id' => $newEnrollment->id,
                    ]);

                    // Create promotion record
                    StudentPromotion::create([
                        'student_id' => $studentId,
                        'from_enrollment_id' => $currentEnrollment->id,
                        'to_enrollment_id' => $newEnrollment->id,
                        'from_academic_year_id' => $currentYear->id,
                        'to_academic_year_id' => $toAcademicYear->id,
                        'from_class_id' => $currentEnrollment->class_id,
                        'to_class_id' => $toClass->id,
                        'promotion_date' => $promotionDate,
                        'remarks' => $request->remarks ?? 'Bulk promotion',
                    ]);

                    // Update student's current class
                    $student->update([
                        'class_id' => $toClass->id,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                }
            }
        });

        $message = "Successfully promoted {$successCount} students.";
        if ($failedCount > 0) {
            $message .= " {$failedCount} students could not be promoted.";
        }

        return redirect()->route('school-admin.promotions.index')
            ->with('success', $message);
    }

    /**
     * Promote a single student.
     */
    public function promoteSingle(Request $request, Student $student)
    {
        $schoolId = auth()->user()->school_id;
        
        // Verify student belongs to school
        if ($student->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        // Get active academic year
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        if (!$currentYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }

        // Get next academic year
        $nextYear = AcademicYear::where('school_id', $schoolId)
            ->where('year', '>', $currentYear->year)
            ->orderBy('year')
            ->first();
        
        if (!$nextYear) {
            return redirect()->back()->with('error', 'No next academic year found. Please create the next academic year first.');
        }

        // Get next class
        $nextClass = $student->nextClass();
        
        if (!$nextClass) {
            return redirect()->back()->with('error', 'No promotion path defined for this student. Please set up next class in class settings.');
        }

        // Check if already enrolled for next year
        $existingEnrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('academic_year_id', $nextYear->id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'Student is already enrolled for the next academic year.');
        }

        // Get current enrollment
        $currentEnrollment = StudentEnrollment::where('student_id', $student->id)
            ->where('academic_year_id', $currentYear->id)
            ->first();

        DB::transaction(function () use ($student, $currentYear, $nextYear, $nextClass, $currentEnrollment) {
            // Create new enrollment
            $newEnrollment = StudentEnrollment::create([
                'student_id' => $student->id,
                'academic_year_id' => $nextYear->id,
                'class_id' => $nextClass->id,
                'roll' => $student->roll,
                'section' => $student->section,
                'status' => 'promoted',
            ]);

            // Update current enrollment
            if ($currentEnrollment) {
                $currentEnrollment->update([
                    'status' => 'promoted',
                    'promoted_to_enrollment_id' => $newEnrollment->id,
                ]);
            }

            // Create promotion record
            StudentPromotion::create([
                'student_id' => $student->id,
                'from_enrollment_id' => $currentEnrollment ? $currentEnrollment->id : null,
                'to_enrollment_id' => $newEnrollment->id,
                'from_academic_year_id' => $currentYear->id,
                'to_academic_year_id' => $nextYear->id,
                'from_class_id' => $student->class_id,
                'to_class_id' => $nextClass->id,
                'promotion_date' => now(),
                'remarks' => 'Single student promotion',
            ]);

            // Update student's current class
            $student->update([
                'class_id' => $nextClass->id,
            ]);
        });

        return redirect()->back()->with('success', "Student {$student->name} has been promoted to {$nextClass->class_name}.");
    }

    /**
     * Show promotion history.
     */
    public function history(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        
        // Get filter parameters
        $academicYearId = $request->academic_year;
        $studentId = $request->student_id;
        
        // Get all academic years
        $academicYears = AcademicYear::where('school_id', $schoolId)
            ->orderBy('year', 'desc')
            ->get();
        
        // Build query
        $promotionsQuery = StudentPromotion::with([
            'student',
            'fromAcademicYear',
            'toAcademicYear',
            'fromClass',
            'toClass'
        ])
        ->whereHas('student', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        });
        
        // Apply filters
        if ($academicYearId) {
            $promotionsQuery->where('from_academic_year_id', $academicYearId);
        }
        
        if ($studentId) {
            $promotionsQuery->where('student_id', $studentId);
        }
        
        $promotions = $promotionsQuery->orderBy('promotion_date', 'desc')
            ->paginate(20);
        
        return view('school-admin.promotions.history', compact(
            'promotions',
            'academicYears',
            'academicYearId',
            'studentId'
        ));
    }

    /**
     * Issue Transfer Certificate (TC).
     */
    public function issueTC(Request $request, Student $student)
    {
        $schoolId = auth()->user()->school_id;
        
        // Verify student belongs to school
        if ($student->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'tc_date' => 'required|date',
            'tc_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update student status to tc_issued
        $student->update([
            'status' => 'tc_issued',
        ]);

        // Update current enrollment
        $currentYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
        
        if ($currentYear) {
            StudentEnrollment::where('student_id', $student->id)
                ->where('academic_year_id', $currentYear->id)
                ->update(['status' => 'tc_issued']);
        }

        return redirect()->back()->with('success', "TC has been issued for {$student->name}. Student status updated to 'TC Issued'.");
    }

    /**
     * Show enrollment details for a student.
     */
    public function studentEnrollments(Student $student)
    {
        $schoolId = auth()->user()->school_id;
        
        // Verify student belongs to school
        if ($student->school_id !== $schoolId) {
            abort(403, 'Unauthorized access.');
        }

        $enrollments = StudentEnrollment::with(['academicYear', 'schoolClass'])
            ->where('student_id', $student->id)
            ->orderBy('academic_year_id', 'desc')
            ->get();
        
        $promotions = StudentPromotion::with(['fromAcademicYear', 'toAcademicYear', 'fromClass', 'toClass'])
            ->where('student_id', $student->id)
            ->orderBy('promotion_date', 'desc')
            ->get();
        
        $admission = StudentAdmission::with(['academicYear', 'schoolClass'])
            ->where('student_id', $student->id)
            ->first();

        return view('school-admin.promotions.student-enrollments', compact(
            'student',
            'enrollments',
            'promotions',
            'admission'
        ));
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
