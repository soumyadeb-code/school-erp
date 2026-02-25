<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if user has the required role.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Super admin can access everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Check specific role
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
