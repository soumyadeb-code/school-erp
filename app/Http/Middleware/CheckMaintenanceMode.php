<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MaintenanceSettings;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for certain routes
        $skipRoutes = [
            'maintenance-preview',
            'login',
            'logout',
            'register',
        ];
        
        // Check if we should skip maintenance mode for this route
        foreach ($skipRoutes as $route) {
            if ($request->routeIs($route) || $request->is($route)) {
                return $next($request);
            }
        }
        
        // Skip for super admin logged in users - they can still access the system
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }
        
        try {
            // Check if user is logged in and get their school_id
            $schoolId = null;
            if (auth()->check() && auth()->user()->role === 'school_admin') {
                $schoolId = auth()->user()->school_id;
            }
            
            // Check for global maintenance first (is_global = true)
            $globalMaintenance = MaintenanceSettings::where('is_global', true)
                ->where('is_active', true)
                ->first();
            
            if ($globalMaintenance) {
                return $this->showMaintenancePage($request, $globalMaintenance);
            }
            
            // Check for school-specific maintenance (only if user is school admin or on school routes)
            if ($schoolId) {
                $schoolMaintenance = MaintenanceSettings::where('school_id', $schoolId)
                    ->where('is_active', true)
                    ->first();
                
                if ($schoolMaintenance) {
                    return $this->showMaintenancePage($request, $schoolMaintenance);
                }
            }
            
        } catch (\Exception $e) {
            // If there's a database error, check if it's a connection error
            // and show maintenance page
            if ($e->getCode() == 2002 || strpos($e->getMessage(), 'Connection refused') !== false) {
                return $this->showDefaultMaintenancePage($request);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Show the maintenance page with settings
     */
    private function showMaintenancePage(Request $request, $settings): Response
    {
        // For AJAX/API requests, return JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server is under maintenance. Please try again later.',
                'code' => 'MAINTENANCE_MODE'
            ], 503);
        }
        
        // Show maintenance page
        return response()->view('errors.maintenance', [
            'page_title' => $settings->page_title ?? 'Site Under Maintenance',
            'school_title' => $settings->school_title ?? 'School Business ERP',
            'maintenance_message' => $settings->maintenance_message ?? "We're currently performing scheduled maintenance to improve our services.",
            'email' => $settings->email ?? null,
            'phone' => $settings->phone ?? null,
        ], 503);
    }
    
    /**
     * Show default maintenance page when database is unavailable
     */
    private function showDefaultMaintenancePage(Request $request): Response
    {
        // For AJAX/API requests, return JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server is under maintenance. Please try again later.',
                'code' => 'MAINTENANCE_MODE'
            ], 503);
        }
        
        // Show default maintenance page
        return response()->view('errors.maintenance', [
            'page_title' => 'Site Under Maintenance',
            'school_title' => 'School Business ERP',
            'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
            'email' => 'support@schoolerp.com',
            'phone' => '+1 (555) 123-4567',
        ], 503);
    }
}
