<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSettings;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance settings page.
     * For Super Admin - shows all schools and global settings
     * For School Admin - shows only their school's settings
     */
    public function index()
    {
        // Ensure table exists
        $this->ensureTableExists();

        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            // Super admin sees all schools and global settings
            $globalSettings = MaintenanceSettings::global()->first();
            $schools = School::all();
            
            return view('super-admin.maintenance-settings', compact('globalSettings', 'schools'));
        } else {
            // School admin sees only their school's settings
            $schoolId = $user->school_id;
            $schoolSettings = MaintenanceSettings::where('school_id', $schoolId)->first();
            $school = School::find($schoolId);
            
            // Create default if not exists
            if (!$schoolSettings) {
                $schoolSettings = MaintenanceSettings::create([
                    'school_id' => $schoolId,
                    'school_title' => $school->school_name ?? 'School',
                    'page_title' => 'Site Under Maintenance',
                    'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
                    'email' => $school->email ?? 'support@schoolerp.com',
                    'phone' => $school->phone ?? '',
                    'is_active' => false,
                    'is_global' => false,
                ]);
            }
            
            return view('school-admin.maintenance-settings', compact('schoolSettings', 'school'));
        }
    }

    /**
     * Update the maintenance settings.
     */
    public function update(Request $request)
    {
        $this->ensureTableExists();

        $user = auth()->user();
        
        $request->validate([
            'page_title' => 'required|string|max:255',
            'school_title' => 'required|string|max:255',
            'maintenance_message' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
        ]);

        if ($user->role === 'super_admin') {
            // Update global settings
            $settings = MaintenanceSettings::global()->first();
            
            if (!$settings) {
                $settings = MaintenanceSettings::create([
                    'page_title' => $request->page_title,
                    'school_title' => $request->school_title,
                    'maintenance_message' => $request->maintenance_message,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'is_active' => false,
                    'is_global' => true,
                ]);
            } else {
                $settings->update([
                    'page_title' => $request->page_title,
                    'school_title' => $request->school_title,
                    'maintenance_message' => $request->maintenance_message,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }
            
            return redirect()->route('super-admin.maintenance.index')
                ->with('success', 'Global maintenance settings updated successfully!');
        } else {
            // Update school-specific settings
            $schoolId = $user->school_id;
            $schoolSettings = MaintenanceSettings::where('school_id', $schoolId)->first();
            
            if (!$schoolSettings) {
                $schoolSettings = MaintenanceSettings::create([
                    'school_id' => $schoolId,
                    'page_title' => $request->page_title,
                    'school_title' => $request->school_title,
                    'maintenance_message' => $request->maintenance_message,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'is_active' => false,
                    'is_global' => false,
                ]);
            } else {
                $schoolSettings->update([
                    'page_title' => $request->page_title,
                    'school_title' => $request->school_title,
                    'maintenance_message' => $request->maintenance_message,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }
            
            return redirect()->route('school-admin.maintenance.index')
                ->with('success', 'Maintenance settings updated successfully!');
        }
    }

    /**
     * Enable maintenance mode.
     */
    public function enable(Request $request)
    {
        $this->ensureTableExists();

        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            // Enable global maintenance
            $settings = MaintenanceSettings::global()->first();
            
            if (!$settings) {
                $settings = MaintenanceSettings::create([
                    'page_title' => 'Site Under Maintenance',
                    'school_title' => 'School Business ERP',
                    'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
                    'email' => 'support@schoolerp.com',
                    'phone' => '+1 (555) 123-4567',
                    'is_active' => true,
                    'is_global' => true,
                ]);
            } else {
                $settings->update(['is_active' => true]);
            }
            
            return redirect()->route('super-admin.maintenance.index')
                ->with('success', 'Global maintenance mode enabled! All users will see the maintenance page.');
        } else {
            // Enable school-specific maintenance
            $schoolId = $user->school_id;
            $schoolSettings = MaintenanceSettings::where('school_id', $schoolId)->first();
            $school = School::find($schoolId);
            
            if (!$schoolSettings) {
                $schoolSettings = MaintenanceSettings::create([
                    'school_id' => $schoolId,
                    'page_title' => 'Site Under Maintenance',
                    'school_title' => $school->school_name ?? 'School',
                    'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
                    'email' => $school->email ?? 'support@schoolerp.com',
                    'phone' => $school->phone ?? '',
                    'is_active' => true,
                    'is_global' => false,
                ]);
            } else {
                $schoolSettings->update(['is_active' => true]);
            }
            
            return redirect()->route('school-admin.maintenance.index')
                ->with('success', 'Maintenance mode enabled for your school!');
        }
    }

    /**
     * Disable maintenance mode.
     */
    public function disable(Request $request)
    {
        $this->ensureTableExists();

        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            // Disable global maintenance
            $settings = MaintenanceSettings::global()->first();
            
            if ($settings) {
                $settings->update(['is_active' => false]);
            }
            
            return redirect()->route('super-admin.maintenance.index')
                ->with('success', 'Global maintenance mode disabled!');
        } else {
            // Disable school-specific maintenance
            $schoolId = $user->school_id;
            $schoolSettings = MaintenanceSettings::where('school_id', $schoolId)->first();
            
            if ($schoolSettings) {
                $schoolSettings->update(['is_active' => false]);
            }
            
            return redirect()->route('school-admin.maintenance.index')
                ->with('success', 'Maintenance mode disabled for your school!');
        }
    }

    /**
     * Ensure the maintenance_settings table exists.
     */
    private function ensureTableExists()
    {
        if (!Schema::hasTable('maintenance_settings')) {
            Schema::create('maintenance_settings', function ($table) {
                $table->id();
                $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
                $table->string('page_title')->default('Site Under Maintenance');
                $table->string('school_title')->default('School Business ERP');
                $table->string('maintenance_message')->default('We\'re currently performing scheduled maintenance to improve our services.');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(false);
                $table->boolean('is_global')->default(true);
                $table->timestamps();
            });
        }
        
        // Check if we need to add columns (for existing tables)
        if (Schema::hasTable('maintenance_settings') && !Schema::hasColumn('maintenance_settings', 'school_id')) {
            Schema::table('maintenance_settings', function ($table) {
                $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
                $table->boolean('is_global')->default(true);
            });
        }
    }
}
