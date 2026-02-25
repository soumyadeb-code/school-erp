<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'school.isolation' => \App\Http\Middleware\SchoolIsolation::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        
        // Ensure web middleware group includes CSRF and session
        $middleware->web([
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);
        
        // Add maintenance check middleware to all web routes
        $middleware->middlewarePriority([
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom handler for database connection errors
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            // Check if it's a connection error (error code 2002)
            if ($e->getCode() == 2002 || strpos($e->getMessage(), 'Connection refused') !== false) {
                // For AJAX/API requests, return JSON response
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Server is under maintenance. Please try again later.',
                        'code' => 'MAINTENANCE_MODE'
                    ], 503);
                }
                
                // For regular requests, show the maintenance page
                return response()->view('errors.maintenance', [
                    'page_title' => 'Site Under Maintenance',
                    'school_title' => 'School Business ERP',
                    'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
                    'email' => null,
                    'phone' => null,
                ], 503);
            }
        });
        
        // Handle generic PDO exceptions
        $exceptions->render(function (\PDOException $e, $request) {
            if ($e->getCode() == 2002 || strpos($e->getMessage(), 'Connection refused') !== false) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Server is under maintenance. Please try again later.',
                        'code' => 'MAINTENANCE_MODE'
                    ], 503);
                }
                
                return response()->view('errors.maintenance', [
                    'page_title' => 'Site Under Maintenance',
                    'school_title' => 'School Business ERP',
                    'maintenance_message' => "We're currently performing scheduled maintenance to improve our services.",
                    'email' => null,
                    'phone' => null,
                ], 503);
            }
        });
    })->create();
