<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response|RedirectResponse
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get the selected role from session
                $selectedRole = session('selected_role');
                
                // If no role is selected in session, redirect to dashboard for role selection
                if (!$selectedRole) {
                    return redirect()->route('dashboard');
                }

                // Convert event_organizer to organizer for the route
                $dashboardRoute = $selectedRole === 'event_organizer' 
                    ? 'organizer.dashboard' 
                    : $selectedRole . '.dashboard';

                return redirect()->route($dashboardRoute);
            }
        }

        return $next($request);
    }
}
