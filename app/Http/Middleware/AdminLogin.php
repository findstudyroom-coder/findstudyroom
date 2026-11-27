<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) { 
            $user = Auth::user();
            // If user is exclusively a Coaching Admin, route them to their dedicated coaching dashboard
            if ($user->isCoachingAdmin() && !$user->isSuperAdmin()) {
                return redirect()->route('coaching.dashboard');
            }
            return $next($request);
        }
        

        
           return redirect()->route('admin.user.login')->withError(__('Admin unauthorized ,Please try again'));
      //  return redirect()->route('profile'); 
       
    }
}
