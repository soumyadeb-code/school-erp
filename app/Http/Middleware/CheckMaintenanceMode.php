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
            // Check if maintenance mode is enabled
            $maintenanceEnabled = cache()->remember('maintenance_mode', 60, function () {
                return MaintenanceSettings::where('is_active', true)->exists();
            });
            
            if ($maintenanceEnabled) {
                $settings = cache()->remember('maintenance_settings', 60, function () {
                    return MaintenanceSettings::where('is_active', true)->first();
                });
                
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
        } catch (\Exception $e) {
            // If there's a database error, let it pass through
            // The exception handler will catch it and show maintenance page
        }
        
        return $next($request);
    }
}
