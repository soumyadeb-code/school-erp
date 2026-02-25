<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard.
     */
    public function dashboard()
    {
        $totalSchools = School::count();
        $activeSchools = School::where('status', 'active')->count();
        $expiredSchools = School::whereDate('expiry_date', '<', now())->count();
        
        // Calculate total collections from all schools
        $totalCollections = \App\Models\Receipt::where('status', 'active')
            ->sum('paid_amount');
        
        // Recent schools
        $recentSchools = School::latest()->take(10)->get();
        
        return view('super-admin.dashboard', compact(
            'totalSchools',
            'activeSchools', 
            'expiredSchools',
            'totalCollections',
            'recentSchools'
        ));
    }

    /**
     * List all schools.
     */
    public function schools()
    {
        $schools = School::with('users')->latest()->paginate(10);
        return view('super-admin.schools.index', compact('schools'));
    }

    /**
     * Show create school form.
     */
    public function createSchool()
    {
        return view('super-admin.schools.create');
    }

    /**
     * Store new school.
     */
    public function storeSchool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:schools',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:schools',
            'joining_date' => 'required|date',
            'expiry_date' => 'required|date|after:joining_date',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create school
        $school = School::create([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'joining_date' => $request->joining_date,
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
            'created_by' => auth()->id(),
        ]);

        // Create school admin user
        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'school_admin',
            'school_id' => $school->id,
        ]);

        return redirect()->route('super-admin.schools.index')
            ->with('success', 'School created successfully with admin credentials.');
    }

    /**
     * Show school details.
     */
    public function showSchool(School $school)
    {
        $school->load(['users', 'students', 'receipts']);
        return view('super-admin.schools.show', compact('school'));
    }

    /**
     * Show edit school form.
     */
    public function editSchool(School $school)
    {
        return view('super-admin.schools.edit', compact('school'));
    }

    /**
     * Update school.
     */
    public function updateSchool(Request $request, School $school)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:schools,code,' . $school->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'joining_date' => 'required|date',
            'expiry_date' => 'required|date|after:joining_date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $school->update($request->only(['name', 'code', 'address', 'phone', 'email', 'joining_date', 'expiry_date', 'status']));

        return redirect()->route('super-admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Delete school.
     */
    public function destroySchool(School $school)
    {
        // Check if school has active students
        if ($school->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete school with active students.');
        }

        // Delete school admin users
        $school->users()->delete();
        
        // Delete school (cascades to other records)
        $school->delete();

        return redirect()->route('super-admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    /**
     * Create school admin credentials.
     */
    public function createAdmin(Request $request, School $school)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:school_admin,accountant,receptionist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $school->id,
        ]);

        return redirect()->back()
            ->with('success', 'User created successfully.');
    }

    /**
     * View all reports.
     */
    public function reports()
    {
        $schools = School::with(['receipts' => function($query) {
            $query->where('status', 'active');
        }])->get()->map(function($school) {
            $school->total_collection = $school->receipts->sum('paid_amount');
            $school->total_students = $school->students()->count();
            return $school;
        });

        return view('super-admin.reports', compact('schools'));
    }

    /**
     * Check if school code is unique.
     */
    public function checkCode(Request $request)
    {
        $code = $request->input('code');
        $excludeId = $request->input('exclude_id');
        
        $query = School::where('code', $code);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $exists = $query->exists();
        
        return response()->json(['exists' => $exists]);
    }
}
