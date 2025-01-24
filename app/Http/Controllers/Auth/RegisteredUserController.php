<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matric_no' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:event_organizer,student'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Check if a user with the same email or matric_no already exists
        $user = User::where('email', $request->email)
                    ->orWhere('matric_no', $request->matric_no)
                    ->first();
    
        if ($user) {
            // Check if the user already has the role
            $existingRole = $user->roles()->where('role_name', $request->role)->first();
            if ($existingRole) {
                return redirect()->back()->withErrors(['role' => 'You already have this role.']);
            } else {
                // Assign the new role to the existing user
                $role = Role::where('role_name', $request->role)->first();
                $user->roles()->attach($role, ['created_at' => now(), 'updated_at' => now()]);
    
                // Insert into the respective role-specific table
                $this->insertRoleSpecificData($user, $request);
    
                Auth::login($user);
    
                // Store the selected role in the session
                $request->session()->put('selected_role', $request->role);
    
                return $this->redirectBasedOnRole($request->role);
            }
        } else {
            // Create a new user if they don't exist
            $user = User::create([
                'name' => $request->name,
                'matric_no' => $request->matric_no,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            // Assign the new role to the new user
            $role = Role::where('role_name', $request->role)->first();
            $user->roles()->attach($role);
    
            // Insert into the respective role-specific table
            $this->insertRoleSpecificData($user, $request);
    
            Auth::login($user);
            
            // Store the selected role in the session
            $request->session()->put('selected_role', $request->role);
    
            return $this->redirectBasedOnRole($request->role);
        }
    }    

    /**
     * Insert role-specific data into corresponding tables.
     */
    protected function insertRoleSpecificData(User $user, Request $request): void
    {
        switch ($request->role) {
            case 'event_organizer':
                // Insert into event_organizers table
                DB::table('event_organizers')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'org_name' => $request->name,
                        'org_age' => 0,  
                        'org_course' => '',  
                        'org_position' => '',  
                        'org_phoneNo' => '',  
                        'org_detail' => '',  
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                break;
            case 'student':
                // Insert into students table
                DB::table('students')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'stud_name' => $request->name,
                        'stud_age' => 0,  
                        'stud_course' => '',  
                        'stud_phoneNo' => '',  
                        'stud_detail' => '',  
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                break;
        }
    }

    /**
     * Redirect based on user role.
     */
    /**
     * Redirect users based on their role.
     */
    /*protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        $role = $user->roles->first()->role_name ?? 'student'; // Default to 'student' if no role is found

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard'); // Adjust route name for admin dashboard
            case 'event_organizer':
                return redirect()->route('organizer.dashboard'); // Adjust route name for event organizer dashboard
            case 'student':
            default:
                return redirect()->route('student.dashboard'); // Adjust route name for student dashboard
        }
    }*/

    protected function redirectBasedOnRole(string $role): RedirectResponse
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'event_organizer':
                return redirect()->route('organizer.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            default:
                return redirect('/');
        }
    }
}