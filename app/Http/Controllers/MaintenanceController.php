<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSettings;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance settings page.
     */
    public function index()
    {
        $settings = MaintenanceSettings::first();
        
        // If no settings exist, create default settings
        if (!$settings) {
            $settings = MaintenanceSettings::create([
                'page_title' => 'Site Under Maintenance',
                'school_title' => 'School Business ERP',
                'maintenance_message' => 'We\'re currently performing scheduled maintenance to improve our services.',
                'email' => 'support@schoolerp.com',
                'phone' => '+1 (555) 123-4567',
                'is_active' => false,
            ]);
        }
        
        return view('super-admin.maintenance-settings', compact('settings'));
    }

    /**
     * Update the maintenance settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'page_title' => 'required|string|max:255',
            'school_title' => 'required|string|max:255',
            'maintenance_message' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
        ]);

        $settings = MaintenanceSettings::first();
        
        if (!$settings) {
            $settings = new MaintenanceSettings();
        }

        $settings->page_title = $request->page_title;
        $settings->school_title = $request->school_title;
        $settings->maintenance_message = $request->maintenance_message;
        $settings->email = $request->email;
        $settings->phone = $request->phone;
        $settings->save();

        return redirect()->route('maintenance.settings')
            ->with('success', 'Maintenance settings updated successfully!');
    }

    /**
     * Enable maintenance mode.
     */
    public function enable(Request $request)
    {
        $settings = MaintenanceSettings::first();
        
        if (!$settings) {
            $settings = MaintenanceSettings::create([
                'page_title' => 'Site Under Maintenance',
                'school_title' => 'School Business ERP',
                'maintenance_message' => 'We\'re currently performing scheduled maintenance to improve our services.',
                'email' => 'support@schoolerp.com',
                'phone' => '+1 (555) 123-4567',
                'is_active' => true,
            ]);
        } else {
            $settings->is_active = true;
            $settings->save();
        }

        return redirect()->route('maintenance.settings')
            ->with('success', 'Maintenance mode enabled!');
    }

    /**
     * Disable maintenance mode.
     */
    public function disable()
    {
        $settings = MaintenanceSettings::first();
        
        if ($settings) {
            $settings->is_active = false;
            $settings->save();
        }

        return redirect()->route('maintenance.settings')
            ->with('success', 'Maintenance mode disabled!');
    }
}
