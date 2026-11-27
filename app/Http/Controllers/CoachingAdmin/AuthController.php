<?php

namespace App\Http\Controllers\CoachingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Services\CoachingSetupService;

class AuthController extends Controller
{
    public function showLogin()
    {
        CoachingSetupService::ensureSchema();

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isCoachingAdmin() || $user->isSuperAdmin()) {
                return redirect()->route('coaching.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('coaching_admin.auth.login');
    }

    public function login(Request $request)
    {
        CoachingSetupService::ensureSchema();

        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $credentials = [
            'email'    => trim($request->email),
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Status check
            if ($user->status != 1) {
                Auth::logout();
                return back()->withInput()->with('error', 'Your coaching admin account has been deactivated. Please contact the main administrator.');
            }

            // Must be coaching admin or super admin
            if (!$user->isCoachingAdmin() && !$user->isSuperAdmin()) {
                Auth::logout();
                return back()->withInput()->with('error', 'Unauthorized. This portal is strictly reserved for coaching institute administrators.');
            }

            $request->session()->regenerate();
            $coachingName = $user->coaching ? $user->coaching->coaching_name : 'Coaching';

            return redirect()->route('coaching.dashboard')->with('success', "Welcome back! Logged in as {$coachingName} Administrator.");
        }

        return back()->withInput()->with('error', 'Invalid email or password. Please verify your coaching credentials and try again.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('coaching.login')->with('success', 'You have been safely logged out of the coaching portal.');
    }
}
