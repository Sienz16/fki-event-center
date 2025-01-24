<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the login request
        $request->validate([
            'matric_no' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string', 'in:admin,event_organizer,student'],
        ]);

        // Attempt to authenticate the user
        $credentials = $request->only('matric_no', 'password');

        if (!Auth::attempt($credentials)) {
            // Authentication failed
            return back()->withErrors([
                'matric_no' => 'The provided credentials do not match our records.',
            ])->onlyInput('matric_no');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $request->input('role');

        // Check if the user has the selected role
        $hasRole = $user->roles->contains('role_name', $role);

        if (!$hasRole) {
            // If the user does not have the selected role, logout and show an error
            Auth::logout();
            return back()->withErrors([
                'role' => 'You do not have the selected role.',
            ])->onlyInput('matric_no');
        }

        // Store the selected role in the session
        $request->session()->put('selected_role', $role);

        // Redirect based on the role
        switch ($role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'event_organizer':
                return redirect()->intended(route('organizer.dashboard'));
            case 'student':
                return redirect()->intended(route('student.dashboard'));
            default:
                return redirect('/');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
