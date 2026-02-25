<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SchoolIsolation
{
    /**
     * Handle an incoming request.
     * Ensures school admin can only access their own school's data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Super admin can access all schools
        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        // School admin must have a school_id
        if ($user && in_array($user->role, ['school_admin', 'accountant', 'receptionist'])) {
            if (!$user->school_id) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is not associated with any school.');
            }

            // Check if school's subscription is active
            $school = $user->school;
            if ($school && $school->isExpired()) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your subscription has expired. Please contact Super Admin.');
            }

            // Add school_id to request for easy access
            $request->merge(['school_id' => $user->school_id]);
            $request->attributes->set('school_id', $user->school_id);
        }

        return $next($request);
    }
}
