<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CoachingSetupService;
use App\Models\Coaching;

class CoachingAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        CoachingSetupService::ensureSchema();

        if (!Auth::check()) {
            return redirect()->route('coaching.login')->with('error', 'Please login to access your Coaching Admin Portal.');
        }

        $user = Auth::user();

        // Check if user status is active
        if ($user->status != 1) {
            Auth::logout();
            return redirect()->route('coaching.login')->with('error', 'Your coaching admin account has been deactivated. Please contact the main administrator.');
        }

        // Super Admin can view/manage any coaching
        if ($user->isSuperAdmin()) {
            $coachingId = $request->get('coaching_id') ?: ($user->coaching_id ?: Coaching::value('id'));
            $coaching = Coaching::find($coachingId) ?: Coaching::first();
            if ($coaching) {
                view()->share('currentCoaching', $coaching);
                view()->share('isSuperAdminPreview', true);
                return $next($request);
            }
        }

        // Normal Coaching Admin must have an assigned coaching
        if (!$user->coaching_id) {
            Auth::logout();
            return redirect()->route('coaching.login')->with('error', 'No coaching institute is linked to your account. Please contact the administrator.');
        }

        $coaching = $user->coaching;
        if (!$coaching) {
            Auth::logout();
            return redirect()->route('coaching.login')->with('error', 'The coaching institute linked to your account could not be found.');
        }

        if ($coaching->status == 0) {
            Auth::logout();
            return redirect()->route('coaching.login')->with('error', 'Your coaching institute is currently deactivated.');
        }

        view()->share('currentCoaching', $coaching);
        view()->share('isSuperAdminPreview', false);

        return $next($request);
    }
}
