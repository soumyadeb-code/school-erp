<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Check school expiry for school admin
        if ($user->role === 'school_admin' && $user->school) {
            if ($user->school->expiry_date && now()->gt($user->school->expiry_date)) {
                return back()->withErrors([
                    'email' => 'Your subscription has expired. Please contact Super Admin.',
                ])->onlyInput('email');
            }
            
            if ($user->school->status === 'inactive') {
                return back()->withErrors([
                    'email' => 'Your school account is inactive. Please contact Super Admin.',
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if ($user->role === 'super_admin') {
                return redirect()->route('super-admin.dashboard');
            }
            
            return redirect()->route('school-admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
